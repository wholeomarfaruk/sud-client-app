<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
               // 1. Admin Panel: accessible via /admin
            Route::middleware(['web', 'auth', 'panel:admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
            'panel' => \App\Http\Middleware\PanelMiddleware::class,
            'client.auth' => \App\Http\Middleware\EnsureClientIsAuthenticated::class,
        ]);

        // The service worker (public/service-worker.js) fires these from a
        // background push/notificationclick event with no page context to
        // read a CSRF meta tag from. Both routes are session-scoped POSTs
        // (push-subscriptions.store, notifications.read) acting only on the
        // authenticated client's own resources.
        $middleware->validateCsrfTokens(except: [
            'push-subscriptions',
            'notifications/*/read',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
