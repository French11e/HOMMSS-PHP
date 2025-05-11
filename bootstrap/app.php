<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configure string trimming behavior
        $middleware->trimStrings(except: [
            // Routes that should skip string trimming
            fn (Request $request) => $request->is('admin/*'),
        ]);
        
        // Other middleware configuration...
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

