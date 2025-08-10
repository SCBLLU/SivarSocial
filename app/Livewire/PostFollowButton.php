<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PostFollowButton extends Component
{
    public Post $post;
    public ?User $postAuthor = null;
    public bool $shouldShowFollowButton = false;
    public bool $isFollowing = false;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->initializeComponent();
    }

    private function initializeComponent()
    {
        try {
            // Cargar la relación del usuario si no está cargada
            if (!$this->post->relationLoaded('user')) {
                $this->post->load('user');
            }
            
            $this->postAuthor = $this->post->user;
            $this->checkIfShouldShowButton();
        } catch (\Exception $e) {
            // En caso de error, no mostrar el botón
            $this->shouldShowFollowButton = false;
            $this->isFollowing = false;
        }
    }

    public function checkIfShouldShowButton()
    {
        $authUser = Auth::user();
        
        // Inicializar valores por defecto
        $this->shouldShowFollowButton = false;
        $this->isFollowing = false;
        
        // No mostrar botón si:
        // - No hay usuario autenticado
        // - Es el propio usuario
        // - No hay autor del post
        if (!$authUser || !$this->postAuthor || $authUser->id === $this->postAuthor->id) {
            return;
        }

        // Verificar si ya sigue al usuario
        try {
            $this->isFollowing = $authUser->isFollowing($this->postAuthor);
            
            // Solo mostrar si no lo sigue
            $this->shouldShowFollowButton = !$this->isFollowing;
        } catch (\Exception $e) {
            // En caso de error, no mostrar el botón
            $this->shouldShowFollowButton = false;
        }
    }

    public function followUser()
    {
        $authUser = Auth::user();

        if (!$authUser) {
            $this->dispatch('show-auth-modal', action: 'follow');
            return;
        }

        if (!$this->postAuthor || $authUser->id === $this->postAuthor->id) {
            return;
        }

        try {
            // Seguir al usuario
            $authUser->following()->attach($this->postAuthor->id);
            $this->isFollowing = true;
            $this->shouldShowFollowButton = false;

            // Notificar a otros componentes
            $this->dispatch('follow-updated', userId: $this->postAuthor->id, isFollowing: true);
            
            // Mostrar mensaje de éxito
            $this->dispatch('show-toast', message: "Ahora sigues a {$this->postAuthor->name}!", type: 'success');
        } catch (\Exception $e) {
            // Manejar error silenciosamente
            return;
        }
    }

    public function render()
    {
        return view('livewire.post-follow-button');
    }
}
