<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LikePost extends Component
{
    public $post;
    public $isLiked;
    public $likes;
    public $color;

    public function mount($post, $color = 'purple')
    {
        $this->post = $post;
        $this->isLiked = Auth::check() ? $post->checkLike(Auth::user()) : false;
        $this->likes = $post->likes->count();
        $this->color = $color;
    }

    public function clickLike()
    {
        // Solo permitir interacción si el usuario está logueado
        if (!Auth::check()) {
            return;
        }

        try {
            // Permitir dar like a cualquier post, incluidos los propios
            if ($this->post->checkLike(Auth::user())) {
                // Quitar like
                $this->post->likes()->where('user_id', Auth::user()->id)->delete();
                $this->isLiked = false;
                $this->likes--;
            } else {
                // Dar like
                $this->post->likes()->create([
                    'user_id' => Auth::user()->id,
                ]);
                $this->isLiked = true;
                $this->likes++;
            }
            
            // Refrescar la relación likes
            $this->post->load('likes');
            
        } catch (\Exception $e) {
            // Log del error para debugging
            Log::error('Error en LikePost: ' . $e->getMessage());
            // También podemos agregar una sesión flash para mostrar el error
            session()->flash('like_error', 'Error al procesar el like: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.like-post');
    }
}
