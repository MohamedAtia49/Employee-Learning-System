<?php

use App\Http\Middleware\CheckEmployee;
use App\Http\Middleware\JwtMiddleware;
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
    ->withMiddleware(function (Middleware $middleware) {
        // Check Authentication Middleware for Admins apis
        $middleware->alias([
            'jwt_auth' => JwtMiddleware::class
        ]);
        // Check Authentication Middleware for Employee
        $middleware->alias([
            'check_employee' => CheckEmployee::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
