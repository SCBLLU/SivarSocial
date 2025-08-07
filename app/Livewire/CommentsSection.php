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
    public $showLoadMore = false;
    public $commentsPerPage = 5;
    public $loadedComments = 0;
    public $totalComments = 0;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->totalComments = $this->post->comentarios()->count();
        $this->loadedComments = min(10, $this->totalComments); // Cargar máximo 10 inicialmente
        $this->showLoadMore = $this->totalComments > $this->loadedComments;
    }

    public function getComentariosProperty()
    {
        // Obtener comentarios limitados por la cantidad cargada
        $comments = $this->post->comentarios()
            ->with('user')
            ->orderBy('created_at', 'asc') // Orden cronológico (más antiguos primero)
            ->take($this->loadedComments)
            ->get();

        return $comments;
    }

    public function loadMoreComments()
    {
        // Cargar 5 comentarios más
        $this->loadedComments = min($this->loadedComments + $this->commentsPerPage, $this->totalComments);

        // Actualizar el estado del botón "Ver más"
        $this->showLoadMore = $this->loadedComments < $this->totalComments;

        // Disparar evento para notificar que se cargaron más comentarios
        $this->dispatch('comments-loaded');
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

            // Recalcular contadores desde la base de datos para asegurar consistencia
            $this->totalComments = $this->post->comentarios()->count();
            // Si tenemos menos comentarios cargados que el total, cargar uno más para mostrar el nuevo
            if ($this->loadedComments < $this->totalComments) {
                $this->loadedComments++;
            }
            // Actualizar el estado del botón "Ver más"
            $this->showLoadMore = $this->loadedComments < $this->totalComments;

            // Forzar la actualización del post para refrescar la relación
            $this->post->refresh();

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
            // Puede eliminar si es el autor del comentario O el autor del post
            $isCommentOwner = (int)$comentario->user_id === (int)Auth::id();
            $isPostOwner = (int)$this->post->user_id === (int)Auth::id();
            $canDelete = $isCommentOwner || $isPostOwner;

            if (!$canDelete) {
                $this->addError('comentario', 'No tienes permisos para eliminar este comentario.');
                return;
            }

            // Verificar que el comentario pertenece al post actual
            if ($comentario->post_id !== $this->post->id) {
                $this->addError('comentario', 'Error: El comentario no pertenece a este post.');
                return;
            }

            $comentario->delete();            // Recalcular contadores desde la base de datos para asegurar consistencia
            $this->totalComments = $this->post->comentarios()->count();
            $this->loadedComments = min($this->loadedComments, $this->totalComments);

            // Actualizar el estado del botón "Ver más"
            $this->showLoadMore = $this->loadedComments < $this->totalComments;

            // Forzar la actualización del post para refrescar la relación
            $this->post->refresh();

            // Actualizar contador en el componente padre
            $this->dispatch('comment-deleted', $this->post->id);

            $this->successMessage = 'Comentario eliminado correctamente';
            $this->dispatch('auto-hide-message');
        } catch (\Exception $e) {
            Log::error('Error al eliminar comentario: ' . $e->getMessage(), [
                'comment_id' => $commentId,
                'user_id' => Auth::id(),
                'post_id' => $this->post->id,
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('comentario', 'Error al eliminar el comentario.');
        }
    }

    public function clearSuccessMessage()
    {
        $this->successMessage = '';
    }

    public function refreshCommentCounts()
    {
        // Método para recalcular los contadores si es necesario
        $actualTotal = $this->post->comentarios()->count();
        $this->totalComments = $actualTotal;
        $this->loadedComments = min($this->loadedComments, $this->totalComments);
        $this->showLoadMore = $this->loadedComments < $this->totalComments;
    }
    public function render()
    {
        // Validar consistencia de datos antes de renderizar
        $actualTotal = $this->post->comentarios()->count();
        if ($this->totalComments !== $actualTotal) {
            $this->refreshCommentCounts();
        }

        return view('livewire.comments-section');
    }
}
