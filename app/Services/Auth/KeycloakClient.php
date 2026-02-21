<?php

namespace App\Services\Auth;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class KeycloakClient
{
    private function request(): PendingRequest
    {
        $baseUrl = rtrim((string) config('kori.keycloak.base_url'), '/');

        return Http::baseUrl($baseUrl)
            ->acceptJson()
            ->asForm()
            ->timeout(10);
    }

    private function realm(): string
    {
        return (string) config('kori.keycloak.realm');
    }

    private function tokenEndpoint(): string
    {
        return '/realms/' . $this->realm() . '/protocol/openid-connect/token';
    }

    public function exchangeCodeForTokens(string $code): array
    {
        $clientId = (string) config('kori.keycloak.client_id');
        $clientSecret = (string) config('kori.keycloak.client_secret');
        $redirectUri = (string) config('kori.keycloak.redirect_uri');

        if ($clientSecret === '') {
            throw new RuntimeException('KEYCLOAK_CLIENT_SECRET is missing');
        }

        $response = $this->request()->post($this->tokenEndpoint(), [
            'grant_type'    => 'authorization_code',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
        ]);

        $json = $response->json();
        if (!is_array($json)) {
            return ['status' => $response->status(), 'error' => 'invalid_response'];
        }

        if ($response->failed()) {
            $json['status'] = $response->status();
            return $json;
        }

        return $json;
    }

    public function refreshTokens(string $refreshToken): array
    {
        $clientId = (string) config('kori.keycloak.client_id');
        $clientSecret = (string) config('kori.keycloak.client_secret');

        if ($clientSecret === '') {
            throw new RuntimeException('KEYCLOAK_CLIENT_SECRET is missing');
        }

        $response = $this->request()->post($this->tokenEndpoint(), [
            'grant_type'    => 'refresh_token',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
        ]);

        $json = $response->json();
        if (!is_array($json)) {
            return ['status' => $response->status(), 'error' => 'invalid_response'];
        }

        if ($response->failed()) {
            $json['status'] = $response->status();
            return $json;
        }

        return $json;
    }

    public function buildLogoutUrl(?string $idTokenHint = null): string
    {
        $baseUrl = rtrim((string) config('kori.keycloak.base_url'), '/');
        $realm = $this->realm();

        // Après logout Keycloak, on revient sur /login
        $postLogoutRedirect = (string) config('kori.keycloak.post_logout_redirect_uri');

        $params = [
            'post_logout_redirect_uri' => $postLogoutRedirect,
            'client_id' => (string) config('kori.keycloak.client_id'),
        ];

        if ($idTokenHint) {
            $params['id_token_hint'] = $idTokenHint;
        }

        return $baseUrl
            . '/realms/' . $realm
            . '/protocol/openid-connect/logout?'
            . http_build_query($params);
    }
}
