<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ExtendSessionLifetime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extender la duración de la sesión para usuarios autenticados
        if (Auth::check()) {
            // Extender la sesión por 30 días para usuarios autenticados
            config(['session.lifetime' => 43200]); // 30 días en minutos
            
            // Regenerar el ID de sesión periódicamente para seguridad
            if (!Session::has('last_regenerated') || 
                Session::get('last_regenerated') < now()->subHours(24)) {
                Session::regenerate();
                Session::put('last_regenerated', now());
            }
        }

        $response = $next($request);

        // Configurar cookies de sesión para mayor persistencia
        if (Auth::check()) {
            // Configurar cookie con mayor duración
            $response->headers->setCookie(
                cookie(
                    name: config('session.cookie'),
                    value: Session::getId(),
                    minutes: 43200, // 30 días
                    path: config('session.path'),
                    domain: config('session.domain'),
                    secure: config('session.secure'),
                    httpOnly: config('session.http_only'),
                    sameSite: config('session.same_site')
                )
            );
        }

        return $response;
    }
}
