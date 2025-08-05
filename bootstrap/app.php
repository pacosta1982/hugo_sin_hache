<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\FirebaseServiceProvider::class,
        App\Providers\RateLimitServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'firebase.auth' => \App\Http\Middleware\FirebaseAuth::class,
            'require.admin' => \App\Http\Middleware\RequireAdmin::class,
            'json.api' => \App\Http\Middleware\JsonApiValidation::class,
        ]);
        
        // Configure rate limiting for API endpoints
        $middleware->throttleApi();
        
        // Global rate limiting for all requests
        $middleware->web(append: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
        ]);
        
        // Apply JSON API validation to all API routes
        $middleware->api(prepend: [
            \App\Http\Middleware\JsonApiValidation::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle validation exceptions with consistent JSON responses
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
            }
        });

        // Handle authentication exceptions
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado'
                ], 401);
            }
        });

        // Handle authorization exceptions
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }
        });

        // Handle model not found exceptions
        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recurso no encontrado'
                ], 404);
            }
        });

        // Handle general exceptions in production
        $exceptions->render(function (\Exception $e, $request) {
            if ($request->expectsJson() && !config('app.debug')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor'
                ], 500);
            }
        });
    })->create();