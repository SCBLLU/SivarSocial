<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends \Illuminate\Routing\Controller
{
    /* para proteger que no se pueda abrir el muro en otra página */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user)
    {
        return view('layouts.dashboard', [
            'user' => $user,
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required|string',
        ]);

        Post::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('posts.index', ['user' => Auth::user()])
            ->with('success', 'Post creado correctamente');
    }
}

