<?php

use App\Exceptions\KoriApiException;
use App\Services\Auth\TokenService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.portal' => \App\Http\Middleware\RequireAuth::class,
            'role'        => \App\Http\Middleware\RequireRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (KoriApiException $e, $request) {
            $error = [
                'status'  => $e->status,
                'message' => $e->getMessage(),
                'payload' => $e->payload,
            ];

            if ($request->hasSession()) {
                $request->session()->flash('api_error', $error);
            }

            // IMPORTANT: pas de redirect /login automatique ici.
            // 401 = token refusé par l'API => on affiche une page claire, sinon boucle infinie.
            if (in_array($e->status, [401, 403], true)) {
                return response()->view('errors.api', ['error' => $error], $e->status);
            }

            $previous = url()->previous();
            $current  = $request->fullUrl();

            if ($previous === $current) {
                return response()->view('errors.api', ['error' => $error], 502);
            }

            return redirect()->back();
        });
    })->create();
