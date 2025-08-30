<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        // Elimina el middleware 'auth' para permitir acceso público a home
    }

    public function __invoke()
    {
        $postsPerPage = config('pagination.posts_per_page', 6);

        // Si el usuario está autenticado, mostrar feed personalizado
        if (Auth::check()) {
            $user = Auth::user();
            $followingIds = $user->following->pluck('id')->toArray();
            
            // Feed unificado para usuarios autenticados:
            // 1. Publicaciones públicas de cualquier usuario
            // 2. Publicaciones privadas de usuarios que sigue
            // 3. Sus propias publicaciones (públicas y privadas)
            $posts = Post::where(function ($query) use ($followingIds, $user) {
                // Publicaciones públicas de cualquier usuario
                $query->where('visibility', 'public')
                      // O publicaciones privadas de usuarios que sigue
                      ->orWhere(function ($subQuery) use ($followingIds) {
                          $subQuery->where('visibility', 'followers')
                                   ->whereIn('user_id', $followingIds);
                      })
                      // O sus propias publicaciones
                      ->orWhere('user_id', $user->id);
            })
            ->with(['user', 'comentarios'])
            ->latest()
            ->paginate($postsPerPage);
        } else {
            // Si no está autenticado, solo mostrar publicaciones públicas
            $posts = Post::where('visibility', 'public')
                ->with(['user', 'comentarios'])
                ->latest()
                ->paginate($postsPerPage);
        }
        
        $authUser = Auth::user();
        // Obtener todos los usuarios para mostrar perfiles
        $users = \App\Models\User::latest()->get();
        
        return view('home', [
            'posts' => $posts,
            'users' => $users,
            'authUser' => $authUser,
        ]);
    }
}
