<?php

namespace App\Http\Middleware;

use App\Services\Auth\TokenService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var TokenService $tokens */
        $tokens = app(TokenService::class);

        if (!$tokens->hasAccessToken()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}