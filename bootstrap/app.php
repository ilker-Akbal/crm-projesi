<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health:   '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // Şirket sahipliği kontrolü
            'company.owner' => \App\Http\Middleware\EnsureCompanyOwner::class,
            // Admin panel erişimi kontrolü
            'isAdmin'       => \App\Http\Middleware\IsAdmin::class,
            // Admin login sayfası için misafir yönlendirmesi
            'admin.guest'   => \App\Http\Middleware\RedirectIfAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
