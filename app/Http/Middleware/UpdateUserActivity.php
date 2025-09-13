<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo actualizar si el usuario está autenticado
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Solo actualizar si la última actividad fue hace más de 1 minuto
            // para no sobrecargar la base de datos
            $shouldUpdate = !$user->last_activity ||
                $user->last_activity < now()->subMinute();

            if ($shouldUpdate) {
                try {
                    // Actualizar directamente en la base de datos
                    $user->timestamps = false; // No actualizar updated_at
                    $user->last_activity = now();
                    $user->is_online = true;
                    $user->save();
                    $user->timestamps = true; // Restablecer timestamps

                    // El sistema de presencia de Chatify se encarga de las notificaciones

                } catch (\Exception $e) {
                    // Log del error si es necesario, pero no interrumpir la request
                    Log::error('Error updating user activity: ' . $e->getMessage());
                }
            }
        }

        return $next($request);
    }
}
