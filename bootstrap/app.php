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
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
        $exceptions->renderable(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, Request $request) {
            return redirect()->back()->withInput()->withErrors([
                'images' => 'Ukuran total file yang diunggah melebihi batas maksimal server. Harap kurangi jumlah atau ukuran gambar.',
            ]);
        });
    })->create();
