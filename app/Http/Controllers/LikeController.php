<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

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
    public function store(Request $request, Post $post)
    {
        $user = $request->user();

        // Verificar si ya le dio like
        $existingLike = $post->likes()->where('user_id', $user->id)->first();
        
        if ($existingLike) {
            return back()->with('error', 'Ya le diste like a este post');
        }

        // Crear el like
        $post->likes()->create([
            'user_id' => $user->id,
        ]);

        // Crear notificación (solo si no es el dueño del post)
        if ($post->user_id !== $user->id) {
            $this->notificationService->createLikeNotification($user, $post);
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        $request->user()->likes()->where('post_id', $post->id)->delete();

        return back();
    }
}
