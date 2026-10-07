<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // A throttled contact form shows a friendly message in the form instead of an error page.
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if (! $request->routeIs('contact.store')) {
                return null;
            }

            $wait = (int) ($e->getHeaders()['Retry-After'] ?? 60);
            $when = $wait >= 120 ? 'in about '.(int) ceil($wait / 60).' minutes' : ($wait >= 60 ? 'in about a minute' : 'in a few seconds');

            return back()
                ->withInput($request->except('recaptcha_token'))
                ->withErrors(['throttle' => "You've sent a few messages already. Please try again {$when}."]);
        });
    })->create();
