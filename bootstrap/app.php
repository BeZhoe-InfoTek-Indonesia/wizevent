<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function ($schedule) {
        $schedule->command('activity:cleanup --days=30 --force')->daily();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role.redirect' => \App\Http\Middleware\RedirectBasedOnRole::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
