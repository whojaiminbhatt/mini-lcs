<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                $statusCode = 500;
                $message = 'Internal Server Error';
                $errors = null;

                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    $statusCode = 422;
                    $message = $e->getMessage();
                    $errors = $e->errors();
                } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $statusCode = 404;
                    $message = 'Resource not found';
                } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    $statusCode = 401;
                    $message = 'Unauthenticated';
                } elseif ($e instanceof \Illuminate\Auth\Access\AuthorizationException || $e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
                    $statusCode = 403;
                    $message = 'Unauthorized action';
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                    $statusCode = $e->getStatusCode();
                    $message = $e->getMessage();
                } else {
                    $message = $e->getMessage(); // Show actual message in development, maybe hide in production
                }

                $response = [
                    'success' => false,
                    'message' => $message,
                ];

                if ($errors) {
                    $response['errors'] = $errors;
                }

                return response()->json($response, $statusCode);
            }
        });
    })->create();
