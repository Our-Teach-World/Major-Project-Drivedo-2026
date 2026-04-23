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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.teacher' => \App\Http\Middleware\AuthTeacher::class,
            'auth.student' => \App\Http\Middleware\AuthStudent::class,
            'auth.admin' => \App\Http\Middleware\AuthAdmin::class,
            'auth.principal' => \App\Http\Middleware\AuthPrincipal::class,
        ]);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('app:remind-attendance')->saturdays()->at('18:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
