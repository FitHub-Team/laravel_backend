<?php

use App\Http\Middleware\CoachMiddleware;
use App\Http\Middleware\CoachTraineeAccess;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        // الثقة في البروكسي الخاص بـ Railway مع تفعيل كافة الـ Headers
        $middleware->trustProxies(
            at: '*',
            headers:
            SymfonyRequest::HEADER_X_FORWARDED_FOR |
            SymfonyRequest::HEADER_X_FORWARDED_HOST |
            SymfonyRequest::HEADER_X_FORWARDED_PORT |
            SymfonyRequest::HEADER_X_FORWARDED_PROTO
        );
        $middleware->alias([
            'admin.access' => EnsureAdmin::class,
            'coach' => CoachMiddleware::class,
            'trainee.access' => CoachTraineeAccess::class,
            'user' => UserMiddleware::class
        ]);
        $middleware->prependToPriorityList(SubstituteBindings::class, EnsureAdmin::class);
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/admin', 'api/admin/*')) {
                return null;
            }

            return $request->is('admin', 'admin/*')
                ? route('admin.login')
                : route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*')
                || ($request->is('admin', 'admin/*') && $request->expectsJson()),
        );
    })->create();
