<?php

namespace App\Http\Middleware;

use App\Exceptions\KoriApiException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleApiErrors
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (KoriApiException $e) {
            $error = [
                'status'  => $e->status,
                'message' => $e->getMessage(),
                'payload' => $e->payload,
            ];

            $request->session()->flash('api_error', $error);

            // Évite la boucle si "back" == page courante
            $previous = url()->previous();
            $current  = $request->fullUrl();

            if ($previous === $current) {
                return response()->view('errors.api', [
                    'error' => $error,
                ], 502);
            }

            return redirect()->back()->withInput();
        }
    }
}
