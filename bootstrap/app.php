<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SendCompanyAnniversaryEmails;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health:   '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'company.owner' => \App\Http\Middleware\EnsureCompanyOwner::class,
            'isAdmin'       => \App\Http\Middleware\IsAdmin::class,
            'admin.guest'   => \App\Http\Middleware\RedirectIfAdmin::class,
        ]);
    })
    ->withCommands([
        SendCompanyAnniversaryEmails::class,
    ])
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('crm:send-anniversary-mails')
                 ->dailyAt('08:00')
                 ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
