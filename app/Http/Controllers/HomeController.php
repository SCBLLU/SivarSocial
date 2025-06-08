<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        // Protege la ruta para que solo usuarios autenticados puedan acceder
        $this->middleware('auth');
    }

    public function __invoke()
    {
        // obtener a quien sigo
        $ids = Auth::user()->following->pluck('id')->toArray();  
        $posts = Post::whereIn ('user_id', $ids)
            ->with('user')
            ->latest()
            ->paginate(10)
            ->onEachSide(2);
        
        // Obtener todos los usuarios para mostrar perfiles
        $users = \App\Models\User::latest()->get();
        
        return view('home', [
            'posts' => $posts,
            'users' => $users,
        ]);
    }
}
