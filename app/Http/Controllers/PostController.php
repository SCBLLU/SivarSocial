<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /* para proteger que no se pueda abrir el muro en otra página */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(User $user)
    {
        $postsPerPage = config('pagination.posts_per_page', 6);

        $posts = Post::where('user_id', $user->id)
            ->with('comentarios')
            ->latest()
            ->paginate($postsPerPage);

        // Verifica si el usuario autenticado es el mismo que el del muro
        return view('layouts.dashboard', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // Validación base
        $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'tipo' => 'required|in:imagen,musica',
        ]);

        // Validación condicional según el tipo
        if ($request->tipo === 'imagen') {
            $request->validate([
                'imagen' => 'required|string',
            ]);
        } else if ($request->tipo === 'musica') {
            $request->validate([
                'spotify_track_id' => 'required|string',
                'spotify_track_name' => 'required|string',
                'spotify_artist_name' => 'required|string',
                'spotify_album_name' => 'required|string',
                'spotify_album_image' => 'required|string',
                'spotify_external_url' => 'required|string',
            ]);
        }

        $postData = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'user_id' => Auth::id(),
        ];

        // Añadir campos específicos según el tipo
        if ($request->tipo === 'imagen') {
            $postData['imagen'] = $request->imagen;
        } else if ($request->tipo === 'musica') {
            $postData['spotify_track_id'] = $request->spotify_track_id;
            $postData['spotify_track_name'] = $request->spotify_track_name;
            $postData['spotify_artist_name'] = $request->spotify_artist_name;
            $postData['spotify_album_name'] = $request->spotify_album_name;
            $postData['spotify_album_image'] = $request->spotify_album_image;
            $postData['spotify_preview_url'] = $request->spotify_preview_url;
            $postData['spotify_external_url'] = $request->spotify_external_url;
            $postData['dominant_color'] = $request->dominant_color ?? '#1DB954';
        }

        Post::create($postData);

        return redirect()->route('posts.index', ['user' => Auth::user()])
            ->with('success', 'Post creado correctamente');
    }

    public function show(User $user, Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'user' => $user,
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        //eliminar la imagen
        $imagePath = public_path('uploads/' . $post->imagen);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Redirigir al muro del usuario autenticado
        return redirect()->route('posts.index', ['user' => Auth::user()])
            ->with('success', 'Post eliminado correctamente');
    }
}
