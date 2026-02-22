<?php

namespace App\Services\Auth;

class JwtDecoder
{
    /**
     * Decode JWT payload WITHOUT signature verification (UI-only).
     */
    public function decodePayload(?string $jwt): ?array
    {
        if (!is_string($jwt) || $jwt === '') {
            return null;
        }

        $parts = explode('.', $jwt);
        if (count($parts) < 2) {
            return null;
        }

        $payloadB64 = $parts[1];

        // base64url -> base64
        $payloadB64 = strtr($payloadB64, '-_', '+/');
        $payloadB64 .= str_repeat('=', (4 - strlen($payloadB64) % 4) % 4);

        $json = base64_decode($payloadB64, true);
        if ($json === false) {
            return null;
        }

        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }
}