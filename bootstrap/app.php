<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'throttle.login' => \App\Http\Middleware\ThrottleLogin::class,
            'two-factor' => \App\Http\Middleware\RequireTwoFactor::class,
            'sanitize' => \App\Http\Middleware\SanitizeInput::class,
            'feature' => \App\Http\Middleware\CheckFeatureAccess::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\SanitizeInput::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->appendToGroup('api', [
            \App\Http\Middleware\SecurityHeaders::class,
            'throttle:60,1',
        ]);

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            return response()->view('errors.404', [], 404);
        });
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e) {
            return response()->view('errors.403', [], 403);
        });
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            if ($e->getStatusCode() === 419) {
                return response()->view('errors.419', [], 419);
            }
            if ($e->getStatusCode() === 429) {
                return response()->view('errors.429', [], 429);
            }
            if ($e->getStatusCode() === 500 || $e->getStatusCode() === 503) {
                return response()->view('errors.500', [], $e->getStatusCode());
            }
        });
    })->create();
