<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Comentario;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentsSection extends Component
{
    public $post;
    public $comentario = '';
    public $successMessage = '';

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function getComentariosProperty()
    {
        // Usar una computed property en lugar de almacenar en una variable
        return $this->post->comentarios()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function store()
    {
        // Validación
        $this->validate([
            'comentario' => 'required|string|max:255',
        ], [
            'comentario.required' => 'El comentario es obligatorio.',
            'comentario.max' => 'El comentario no puede exceder los 255 caracteres.',
        ]);

        // Solo usuarios autenticados pueden comentar
        if (!Auth::check()) {
            return;
        }

        try {
            // Crear el comentario
            Comentario::create([
                'user_id' => Auth::id(),
                'post_id' => $this->post->id,
                'comentario' => $this->comentario,
            ]);

            // Limpiar el campo de comentario
            $this->comentario = '';

            // Actualizar el contador de comentarios en el componente padre
            $this->dispatch('comment-added', $this->post->id);

            // Mostrar mensaje de éxito
            $this->successMessage = 'Comentario agregado correctamente';

            // Auto-ocultar mensaje después de 3 segundos
            $this->dispatch('auto-hide-message');
            
            // Scroll automático al último comentario
            $this->dispatch('comment-added');
            
        } catch (\Exception $e) {
            // En caso de error, mostrar mensaje de error
            $this->addError('comentario', 'Error al agregar el comentario. Inténtalo de nuevo.');
        }
    }

    public function clearSuccessMessage()
    {
        $this->successMessage = '';
    }

    public function render()
    {
        return view('livewire.comments-section');
    }
}
