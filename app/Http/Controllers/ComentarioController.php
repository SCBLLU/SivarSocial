<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
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
    public function store(Request $request, User $user, Post $post)
    {
        // Validar los datos del comentario
        $request->validate([
            'comentario' => 'required|string|max:255',
            'post_id' => 'required|exists:posts,id',
        ]);
        // almacenar el comentario
        Comentario::create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'comentario' => $request->comentario,

        ]);
        // imprimir el comentario en la vista
        return back()->with('success', 'Comentario agregado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comentario $comentario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comentario $comentario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comentario $comentario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comentario $comentario)
    {
        // Verificar que el usuario autenticado es el propietario del comentario
        if ($comentario->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes permisos para eliminar este comentario');
        }

        $comentario->delete();

        return back()->with('success', 'Comentario eliminado correctamente');
    }
}
