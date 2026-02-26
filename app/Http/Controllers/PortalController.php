<?php

namespace App\Http\Controllers;

use App\Services\Auth\RoleService;
use App\Services\Auth\TokenService;
use Illuminate\Http\RedirectResponse;

class PortalController extends Controller
{
    public function home(TokenService $tokens, RoleService $roles)
    {
        if (!$tokens->hasAccessToken()) {
            return view('welcome');
        }

        return $this->postLoginRedirect($roles);
    }

    public function postLoginRedirect(RoleService $roles): RedirectResponse
    {
        if ($roles->has('ADMIN')) {
            return redirect()->route('admin.home');
        }

        if ($roles->has('MERCHANT')) {
            return redirect()->route('merchant.home');
        }

        return redirect()
            ->route('auth.success')
            ->with('api_error', [
                'status' => 403,
                'message' => 'Aucun rôle portail autorisé (ADMIN ou MERCHANT) n’a été trouvé dans le token.',
                'payload' => ['roles' => $roles->roles()],
            ]);
    }
}
