<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\BypassAuthenticate;
use App\Http\Middleware\BypassRedirectIfAuthenticated;
use App\Http\Middleware\CustomerApiTokenMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web/web.php',
        api: __DIR__.'/../routes/api/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => BypassAuthenticate::class,
            'guest' => BypassRedirectIfAuthenticated::class,
            'customer.api.token' => CustomerApiTokenMiddleware::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
