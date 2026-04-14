<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'=> \App\Http\Middleware\RoleManager::class,
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        ]);

        // Apply PreventBackHistory globally to all web requests
        $middleware->append(\App\Http\Middleware\PreventBackHistory::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            // Check for specific database connection error (SQLSTATE[HY000] [2002])
            if ($e->getCode() === 2002 || str_contains($e->getMessage(), 'actively refused')) {
                return response()->view('errors.503', [
                    'message' => 'The trail database is temporarily unreachable. Please ensure the park services (MySQL) are active.'
                ], 503);
            }
        });
    })->create();
