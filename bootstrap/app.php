<?php

use App\Http\Middleware\LogAnalytics;
use App\Http\Middleware\LogRequestToFile;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
 
        $middleware->alias([
            'logger' => LogRequestToFile::class,
            'analytics' => LogAnalytics::class,
        ]);

        // $middleware->append(LogRequestToFile::class);
 
        $middleware->web(append:[
            LogAnalytics::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        if(app()->isProduction()){
            Integration::handles($exceptions);
        }
    })->create();
