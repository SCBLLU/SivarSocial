<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Comentario;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentsSection extends Component
{
    use WithPagination;

    public $post;
    public $comentario = '';
    public $successMessage = '';
    public $showLoadMore = true;
    public $commentsPerPage = 5;
    public $currentPage = 1;
    public $initialLoad = true;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function getComentariosProperty()
    {
        $totalComments = $this->post->comentarios()->count();

        // En la primera carga, mostrar los primeros 10 comentarios
        // Después, cargar de 5 en 5
        $commentsToShow = $this->initialLoad ? 10 : (10 + ($this->currentPage - 1) * 5);

        $comments = $this->post->comentarios()
            ->with('user')
            ->orderBy('created_at', 'asc') // Orden cronológico (más antiguos primero)
            ->take($commentsToShow)
            ->get();

        // Verificar si hay más comentarios para cargar
        $this->showLoadMore = $comments->count() < $totalComments;

        return $comments;
    }

    public function loadMoreComments()
    {
        if ($this->initialLoad) {
            $this->initialLoad = false;
        }
        $this->currentPage++;
    }

    public function store()
    {
        // Validación
        $this->validate([
            'comentario' => 'required|string|max:500|min:1',
        ], [
            'comentario.required' => 'El comentario es obligatorio.',
            'comentario.max' => 'El comentario no puede exceder los 500 caracteres.',
            'comentario.min' => 'El comentario debe tener al menos 1 caracter.',
        ]);

        // Solo usuarios autenticados pueden comentar
        if (!Auth::check()) {
            return;
        }

        // Verificar que el comentario no esté vacío o solo contenga espacios
        if (trim($this->comentario) === '') {
            $this->addError('comentario', 'El comentario no puede estar vacío.');
            return;
        }

        try {
            // Crear el comentario
            $nuevoComentario = Comentario::create([
                'user_id' => Auth::id(),
                'post_id' => $this->post->id,
                'comentario' => trim($this->comentario),
            ]);

            // Limpiar el campo de comentario
            $this->comentario = '';

            // Resetear para mostrar el comentario nuevo
            $this->currentPage = 1;
            $this->initialLoad = true;

            // Actualizar el contador de comentarios en el componente padre
            $this->dispatch('comment-added', $this->post->id);

            // Mostrar mensaje de éxito
            $this->successMessage = 'Comentario agregado correctamente';

            // Auto-ocultar mensaje después de 3 segundos
            $this->dispatch('auto-hide-message');

            // Scroll automático al último comentario
            $this->dispatch('comment-added');
        } catch (\Exception $e) {
            Log::error('Error al crear comentario: ' . $e->getMessage());
            // En caso de error, mostrar mensaje de error
            $this->addError('comentario', 'Error al agregar el comentario. Inténtalo de nuevo.');
        }
    }

    public function deleteComment($commentId)
    {
        try {
            $comentario = Comentario::findOrFail($commentId);

            // Verificar que el usuario puede eliminar este comentario
            if ($comentario->user_id !== Auth::id() && $this->post->user_id !== Auth::id()) {
                return;
            }

            $comentario->delete();

            // Actualizar contador
            $this->dispatch('comment-deleted', $this->post->id);

            $this->successMessage = 'Comentario eliminado correctamente';
            $this->dispatch('auto-hide-message');
        } catch (\Exception $e) {
            Log::error('Error al eliminar comentario: ' . $e->getMessage());
            $this->addError('comentario', 'Error al eliminar el comentario.');
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
