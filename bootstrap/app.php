<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'cart_access' => \App\Http\Middleware\EnsureCartSessionOrAuth::class,
            'auth_or_session' => \App\Http\Middleware\AllowAuthOrSession::class,
            'force.json' => \App\Http\Middleware\ForceJsonResponse::class,
        ]);
        
        // Add ForceJsonResponse to API middleware group
        $middleware->api(append: [
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);
        
        // Replace default CSRF middleware with custom one that excludes API routes
        $middleware->web(replace: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class => \App\Http\Middleware\VerifyCsrfToken::class,
        ]);
        
        // Configure authentication redirects only for web routes
        $middleware->redirectGuestsTo(function ($request) {
            // Only redirect web requests, not API requests
            if ($request->is('api/*') || $request->expectsJson()) {
                return null; // Let the exception handler deal with API requests
            }
            return route('admin.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle API exceptions to return JSON
        $exceptions->render(function (Throwable $e, $request) {
            // Only handle API routes
            if ($request->is('api/*') || 
                $request->expectsJson() || 
                $request->wantsJson() ||
                $request->header('Accept') === 'application/json' ||
                str_contains($request->header('Accept', ''), 'application/json')) {
                
                // Handle authentication exceptions
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'message' => 'Unauthenticated.',
                        'status' => 'error',
                        'code' => 'UNAUTHENTICATED'
                    ], 401);
                }
                
                // Handle authorization exceptions
                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return response()->json([
                        'message' => 'Unauthorized.',
                        'status' => 'error',
                        'code' => 'UNAUTHORIZED'
                    ], 403);
                }
                
                // Handle validation exceptions
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'message' => 'Validation failed.',
                        'errors' => $e->validator->errors(),
                        'status' => 'error',
                        'code' => 'VALIDATION_ERROR'
                    ], 422);
                }
                
                // Handle model not found exceptions
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return response()->json([
                        'message' => 'Resource not found.',
                        'status' => 'error',
                        'code' => 'NOT_FOUND'
                    ], 404);
                }
                
                // Handle method not allowed exceptions
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return response()->json([
                        'message' => 'Method not allowed.',
                        'status' => 'error',
                        'code' => 'METHOD_NOT_ALLOWED'
                    ], 405);
                }
                
                // Handle not found exceptions
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->json([
                        'message' => 'Endpoint not found.',
                        'status' => 'error',
                        'code' => 'ENDPOINT_NOT_FOUND'
                    ], 404);
                }
                
                // For other exceptions in API routes, return JSON error
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                return response()->json([
                    'message' => $e->getMessage() ?: 'An error occurred.',
                    'status' => 'error',
                    'code' => 'INTERNAL_ERROR'
                ], $statusCode);
            }
            
            // Return null to let Laravel handle non-API requests normally
            return null;
        });
    })->create();
