<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        // Bu, AuthServiceProvider'ın boot() metodunun
        // her zaman çalışmasını ve admin kuralımızın aktif olmasını garanti eder.
        App\Providers\AuthServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // --- BU SATIR ÇOK ÖNEMLİ ---
        // Bu, /api/* ile başlayan rotaların da web gibi
        // session ve cookie kullanarak kimlik doğrulaması yapmasını sağlar.
        $middleware->statefulApi(); 

        $middleware->alias([
            'accounting.access' => \App\Http\Middleware\CheckAccountingAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

