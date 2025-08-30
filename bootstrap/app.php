<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Agregar middleware para extender duración de sesión
        $middleware->web(append: [
            \App\Http\Middleware\ExtendSessionLifetime::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configurar manejo personalizado de errores HTTP
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\HttpException $e, Illuminate\Http\Request $request) {
            // Solo personalizar errores HTTP para solicitudes web (no API)
            if ($request->accepts('text/html') && !$request->ajax() && !$request->wantsJson()) {
                $statusCode = $e->getStatusCode();

                // Verificar si existe una vista personalizada para este código de estado
                if (view()->exists("errors.{$statusCode}")) {
                    return response()->view("errors.{$statusCode}", [
                        'exception' => $e
                    ], $statusCode);
                }
            }

            return null; // Continuar con el manejo por defecto
        });
    })->create();
