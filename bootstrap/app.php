<?php

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Helpers\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        if (env('APP_ENV', 'production') === 'production' || env('APP_API_JSON_RESPONSE', false) === true) {
            $exceptions->render(function (Throwable $e, Request $request) {
                if ($request->is('api/*')) {
                    return Response::SetAndGet(Response::INTERNAL_SERVER_ERROR, $e->getMessage());
                }
            });
        }
    })->create();
