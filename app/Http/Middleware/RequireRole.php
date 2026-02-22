<?php

namespace App\Http\Middleware;

use App\Services\Auth\RoleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        /** @var RoleService $roleService */
        $roleService = app(RoleService::class);

        if (empty($roles) || !$roleService->any($roles)) {
            // 403 portail (UI) — pas une erreur API
            return response()->view('errors.forbidden', [
                'required' => $roles,
                'roles' => $roleService->roles(),
            ], 403);
        }

        return $next($request);
    }
}