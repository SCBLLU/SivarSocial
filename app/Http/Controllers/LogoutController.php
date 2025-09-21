<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function store()
    {
        // Marcar usuario como offline antes de cerrar sesión
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            try {
                $user->timestamps = false;
                $user->is_online = false;
                $user->last_seen = now();
                $user->save();
                $user->timestamps = true;

                // El sistema de presencia de Chatify se encarga de las notificaciones

            } catch (\Exception $e) {
                // Log del error pero continuar con el logout
                Log::error('Error marking user as offline during logout: ' . $e->getMessage());
            }
        }

        auth()->guard()->logout();
        return redirect()->route('login');
    }

    public function storeus()
    {
        if (Auth::guard('super')->check()) {
            Auth::guard('super')->logout(); 
        }

        return redirect()->route('login'); 
    }
}
