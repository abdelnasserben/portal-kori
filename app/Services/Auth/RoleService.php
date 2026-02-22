<?php

namespace App\Services\Auth;

class RoleService
{
    public function __construct(private readonly JwtDecoder $decoder) {}

    /**
     * Retourne les rôles du JWT (realm + client roles), sans vérification crypto.
     */
    public function roles(): array
    {
        $payload = $this->decoder->decodePayload(session('access_token'));

        if (!is_array($payload)) {
            return [];
        }

        $roles = [];

        // Realm roles: realm_access.roles
        $realmRoles = $payload['realm_access']['roles'] ?? [];
        if (is_array($realmRoles)) {
            $roles = array_merge($roles, array_filter($realmRoles, 'is_string'));
        }

        // Client roles: resource_access[client].roles
        $resourceAccess = $payload['resource_access'] ?? [];
        if (is_array($resourceAccess)) {
            foreach ($resourceAccess as $client => $data) {
                if (!is_array($data)) continue;
                $clientRoles = $data['roles'] ?? [];
                if (is_array($clientRoles)) {
                    $roles = array_merge($roles, array_filter($clientRoles, 'is_string'));
                }
            }
        }

        // Normalisation
        $roles = array_values(array_unique(array_map('strval', $roles)));

        return $roles;
    }

    public function has(string $role): bool
    {
        $role = strtoupper(trim($role));
        if ($role === '') return false;

        $roles = array_map('strtoupper', $this->roles());
        return in_array($role, $roles, true);
    }

    public function any(array $roles): bool
    {
        foreach ($roles as $r) {
            if (is_string($r) && $this->has($r)) {
                return true;
            }
        }
        return false;
    }
}