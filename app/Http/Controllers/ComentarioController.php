<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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
            'parent_id' => 'nullable|integer|exists:comentarios,id',
        ]);

        $parentId = $request->input('parent_id', null);

        try {
            DB::beginTransaction();

            $depth = 0;

            if ($parentId !== null) {
                // Bloquear fila padre para evitar race conditions sobre reply_count
                $parent = Comentario::where('id', $parentId)->lockForUpdate()->first();

                if (!$parent) {
                    throw ValidationException::withMessages(['parent_id' => 'El comentario padre no existe.']);
                }

                if ($parent->post_id !== (int)$request->post_id) {
                    throw ValidationException::withMessages(['parent_id' => 'El comentario padre no pertenece a esta publicación.']);
                }

                $depth = $parent->depth + 1;
            }

            $comentario = Comentario::create([
                'user_id' => Auth::id(),
                'post_id' => $request->post_id,
                'comentario' => $request->comentario,
                'parent_id' => $parentId,
                'depth' => $depth,
                'reply_count' => 0,
            ]);

            if ($parentId !== null) {
                // increment() evita condiciones de carrera cuando se usa dentro de la transacción con lockForUpdate
                Comentario::where('id', $parentId)->increment('reply_count');
            }

            DB::commit();

            return back()->with('success', 'Comentario agregado correctamente');
        } catch (ValidationException $ve) {
            DB::rollBack();
            return back()->withErrors($ve->errors())->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            // Loguear el error para diagnóstico 
            Log::error('Error creando comentario: ' . $e->getMessage(), [
                'post_id' => $request->post_id,
                'parent_id' => $parentId,
                'user_id' => Auth::id(),
            ]);
            return back()->with('error', 'Ocurrió un error al guardar el comentario. Intente de nuevo.');
        }
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
