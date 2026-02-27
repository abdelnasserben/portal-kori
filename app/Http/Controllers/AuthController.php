<?php

namespace App\Http\Controllers;

use App\Services\Auth\KeycloakClient;
use App\Services\Auth\TokenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request, TokenService $tokens): RedirectResponse
    {
        if ($tokens->hasAccessToken()) {
            return redirect()->route('post-login.redirect');
        }

        $config = config('kori.keycloak');

        $state = Str::random(40);
        session(['oidc_state' => $state]);

        $query = http_build_query([
            'client_id'     => $config['client_id'],
            'redirect_uri'  => $config['redirect_uri'],
            'response_type' => 'code',
            'scope'         => 'openid profile email',
            'state'         => $state,
        ]);

        $authUrl = rtrim($config['base_url'], '/')
            . '/realms/' . $config['realm']
            . '/protocol/openid-connect/auth?'
            . $query;

        return redirect()->away($authUrl);
    }

    public function callback(Request $request, KeycloakClient $keycloak): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()
                ->route('auth.success')
                ->with('api_error', [
                    'status' => 401,
                    'message' => 'Keycloak login failed: ' . $request->string('error')->toString(),
                    'payload' => [
                        'error' => $request->string('error')->toString(),
                        'error_description' => $request->string('error_description')->toString(),
                    ],
                ]);
        }

        $expectedState = (string) session('oidc_state');
        $state = (string) $request->query('state', '');
        $code  = (string) $request->query('code', '');

        if ($expectedState === '' || $state === '' || !hash_equals($expectedState, $state)) {
            return redirect()
                ->route('auth.success')
                ->with('api_error', [
                    'status' => 400,
                    'message' => 'Invalid OIDC state (CSRF).',
                    'payload' => null,
                ]);
        }

        if ($code === '') {
            return redirect()
                ->route('auth.success')
                ->with('api_error', [
                    'status' => 400,
                    'message' => 'OIDC code missing.',
                    'payload' => null,
                ]);
        }

        session()->forget('oidc_state');

        $tokens = $keycloak->exchangeCodeForTokens($code);

        if (!isset($tokens['access_token']) || !is_string($tokens['access_token'])) {
            return redirect()
                ->route('auth.success')
                ->with('api_error', [
                    'status' => (int) ($tokens['status'] ?? 401),
                    'message' => 'Unable to retrieve tokens from Keycloak.',
                    'payload' => $tokens,
                ]);
        }

        $expiresIn = (int) ($tokens['expires_in'] ?? 0);
        $expiresAt = now()->addSeconds(max(0, $expiresIn - 30))->timestamp;

        session([
            'access_token'  => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'] ?? null,
            'id_token'      => $tokens['id_token'] ?? null, // important pour logout Keycloak
            'expires_at'    => $expiresAt,
        ]);

        return redirect()->route('post-login.redirect');
    }

    public function logout(Request $request, TokenService $tokens, KeycloakClient $keycloak): RedirectResponse
    {
        $idToken = session('id_token');

        // 1) Logout local
        $tokens->logoutLocal();
        $request->session()->forget('id_token');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 2) Logout Keycloak (SSO)
        return redirect()->away(
            $keycloak->buildLogoutUrl(is_string($idToken) ? $idToken : null)
        );
    }
}
