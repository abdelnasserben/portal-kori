<?php

namespace App\Services\Auth;

use RuntimeException;

class TokenService
{
    public function __construct(private readonly KeycloakClient $keycloak) {}

    public function hasAccessToken(): bool
    {
        $t = session('access_token');
        return is_string($t) && $t !== '';
    }

    /**
     * Retourne un access token VALIDE (refresh si besoin) ou null si pas loggé / refresh impossible.
     */
    public function getValidAccessToken(): ?string
    {
        $access = session('access_token');
        if (!is_string($access) || $access === '') {
            return null;
        }

        $expiresAt = (int) (session('expires_at') ?? 0);
        $now = now()->timestamp;

        // Si encore valide (avec marge déjà appliquée lors du stockage), on garde
        if ($expiresAt > $now) {
            return $access;
        }

        // Expiré -> tenter refresh
        $refresh = session('refresh_token');
        if (!is_string($refresh) || $refresh === '') {
            return null;
        }

        $tokens = $this->keycloak->refreshTokens($refresh);

        if (!isset($tokens['access_token']) || !is_string($tokens['access_token'])) {
            // refresh invalide => session à nettoyer
            $this->logoutLocal();
            return null;
        }

        $expiresIn = (int) ($tokens['expires_in'] ?? 0);
        $newExpiresAt = now()->addSeconds(max(0, $expiresIn - 30))->timestamp;

        session([
            'access_token'  => $tokens['access_token'],
            // Keycloak renvoie souvent un nouveau refresh_token, mais pas toujours
            'refresh_token' => $tokens['refresh_token'] ?? $refresh,
            'expires_at'    => $newExpiresAt,
        ]);

        return $tokens['access_token'];
    }

    public function logoutLocal(): void
    {
        session()->forget(['access_token', 'refresh_token', 'expires_at']);
    }
}
