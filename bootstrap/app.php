<?php

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EmployeeAuthenticate;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\APIAuthenticate;
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
        $middleware->alias([
            'jwt' => APIAuthenticate::class,
            'employee' => EmployeeAuthenticate::class,
        ]);
        $middleware->group('api-admin', [
            APIAuthenticate::class,
            AdminAuthenticate::class,
        ]);
        $middleware->group('api-employee', [
            APIAuthenticate::class,
            EmployeeAuthenticate::class,
        ]);
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
