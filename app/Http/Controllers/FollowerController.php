<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $user)
    {
        // El usuario autenticado sigue al usuario recibido
        $user->followers()->attach(Auth::id());
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Follower $follower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Follower $follower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Follower $follower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // El usuario autenticado deja de seguir al usuario recibido
        $user->followers()->detach(Auth::id());
        return back();
    }

    /**
     * Store a newly created resource by ID (for AJAX requests)
     */
    public function storeById(User $user)
    {
        try {
            // Verificar que el usuario no se siga a sí mismo
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes seguirte a ti mismo.'
                ], 400);
            }

            // Verificar que no ya lo esté siguiendo
            if ($user->followers()->where('follower_id', Auth::id())->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya sigues a este usuario.'
                ], 400);
            }

            // El usuario autenticado sigue al usuario recibido
            $user->followers()->attach(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Ahora sigues a este usuario.',
                'action' => 'followed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al seguir al usuario.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource by ID (for AJAX requests)
     */
    public function destroyById(User $user)
    {
        try {
            // Verificar que el usuario no se dessiga a sí mismo
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes dejar de seguirte a ti mismo.'
                ], 400);
            }

            // Verificar que lo esté siguiendo
            if (!$user->followers()->where('follower_id', Auth::id())->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No sigues a este usuario.'
                ], 400);
            }

            // El usuario autenticado deja de seguir al usuario recibido
            $user->followers()->detach(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Dejaste de seguir a este usuario.',
                'action' => 'unfollowed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al dejar de seguir al usuario.'
            ], 500);
        }
    }
}
