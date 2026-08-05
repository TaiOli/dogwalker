<?php

use App\Exceptions\AppException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn() => null);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {

                return response()->json([
                    'message' => 'Não autenticado.',
                    'error' => $e->getMessage(),
                ], 401);
            }
        });

        $exceptions->render(function (AppException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->httpStatus());
        });

        $exceptions->render(function (ThrottleRequestsException $e) {

            return response()->json([
                'message' => 'Muitas tentativas. Tente novamente em instantes.',
            ], 429, $e->getHeaders());
        });
    })->create();