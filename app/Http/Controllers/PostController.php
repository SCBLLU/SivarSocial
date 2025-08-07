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

        $posts = $user->posts()
            ->with('comentarios')
            ->latest()
            ->paginate($postsPerPage);

        // Obtener el total de publicaciones del usuario (sin paginación)
        $totalPosts = $user->posts()->count();

        $users = \App\Models\User::latest()->get();

        // Verifica si el usuario autenticado es el mismo que el del muro
        return view('layouts.dashboard', [
            'user' => $user,
            'posts' => $posts,
            'totalPosts' => $totalPosts,
            'users' => $users,
        ]);
    }

    public function create()
    {
        $users = User::latest()->get();
        return view('posts.create', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        // Validación condicional según el tipo
        if ($request->tipo === 'imagen') {
            $request->validate([
                'titulo' => 'required|max:255',
                'descripcion' => 'required',
                'tipo' => 'required|in:imagen,musica',
                'imagen' => 'required|string',
            ]);
        } else if ($request->tipo === 'musica') {
            $request->validate([
                'titulo' => 'nullable|max:255',
                'descripcion' => 'nullable',
                'tipo' => 'required|in:imagen,musica',
                'music_source' => 'required|in:itunes,spotify',
                // Campos iTunes
                'itunes_track_id' => 'nullable|string',
                'itunes_track_name' => 'nullable|string',
                'itunes_artist_name' => 'nullable|string',
                'itunes_collection_name' => 'nullable|string',
                'itunes_artwork_url' => 'nullable|string',
                'itunes_preview_url' => 'nullable|string',
                'itunes_track_view_url' => 'nullable|string',
                'itunes_track_time_millis' => 'nullable|integer',
                'itunes_country' => 'nullable|string',
                'itunes_primary_genre_name' => 'nullable|string',
            ]);

            // Validar que tenga datos de iTunes
            if (empty($request->itunes_track_id)) {
                return back()->withErrors(['music' => 'Debes seleccionar una canción'])->withInput();
            }
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
            $postData['music_source'] = $request->music_source ?? 'itunes';

            // Campos iTunes
            if ($request->music_source === 'itunes' || !empty($request->itunes_track_id)) {
                $postData['itunes_track_id'] = $request->itunes_track_id;
                $postData['itunes_track_name'] = $request->itunes_track_name;
                $postData['itunes_artist_name'] = $request->itunes_artist_name;
                $postData['itunes_collection_name'] = $request->itunes_collection_name;
                $postData['itunes_artwork_url'] = $request->itunes_artwork_url;
                $postData['itunes_preview_url'] = $request->itunes_preview_url;
                $postData['itunes_track_view_url'] = $request->itunes_track_view_url;
                $postData['itunes_track_time_millis'] = $request->itunes_track_time_millis;
                $postData['itunes_country'] = $request->itunes_country;
                $postData['itunes_primary_genre_name'] = $request->itunes_primary_genre_name;

                // Generar enlaces cruzados a Spotify para canciones de iTunes
                $searchTerms = \App\Services\CrossPlatformMusicService::cleanSearchTerms(
                    $request->itunes_artist_name,
                    $request->itunes_track_name
                );
                $postData['artist_search_term'] = $searchTerms['artist'];
                $postData['track_search_term'] = $searchTerms['track'];
                $postData['spotify_web_url'] = \App\Services\CrossPlatformMusicService::generateSpotifySearchUrl(
                    $searchTerms['artist'],
                    $searchTerms['track']
                );
            }
        }

        Post::create($postData);

        return redirect()->route('posts.index', ['user' => Auth::user()])
            ->with('success', 'Post creado correctamente');
    }

    public function show(User $user, Post $post)
    {
        // Cargar las relaciones necesarias para el post
        $post->load(['likes', 'comentarios', 'user']);

        $users = \App\Models\User::latest()->get();
        return view('posts.show', [
            'post' => $post,
            'user' => $user,
            'users' => $users,
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Eliminar la imagen ANTES de eliminar el post
        if ($post->imagen && !empty($post->imagen)) {
            $imagePath = public_path('uploads/' . $post->imagen);
            if (file_exists($imagePath) && is_file($imagePath)) {
                unlink($imagePath);
            }
        }

        // Eliminar el post después de eliminar la imagen
        $post->delete();

        // Redirigir al muro del usuario autenticado
        return redirect()->route('posts.index', ['user' => Auth::user()])
            ->with('success', 'Post eliminado correctamente');
    }
}
