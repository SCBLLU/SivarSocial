<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Comentario;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentsSection extends Component
{
    use WithPagination;

    public $post;
    public $comentario = '';
    public $selectedGif = null;
    public $showGifModal = false;
    public $successMessage = '';
    public $showLoadMore = false;
    public $commentsPerPage = 10;
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

    public function toggleGifModal()
    {
        $this->showGifModal = !$this->showGifModal;
        if (!$this->showGifModal) {
            $this->selectedGif = null;
            // Emitir evento para asegurar limpieza de scroll
            $this->dispatch('modal-closed');
        }
    }

    public function selectGif($gifUrl)
    {
        $this->selectedGif = $gifUrl;
        $this->showGifModal = false;        
        $this->dispatch('gif-selected');
    }

    public function removeSelectedGif()
    {
        $this->selectedGif = null;
    }

    public function getGiphyApiKeyProperty()
    {
        return config('services.giphy.api_key');
    }

    public function store()
    {
        // Solo usuarios autenticados pueden comentar
        if (!Auth::check()) {
            // Emitir evento para mostrar modal de registro/login
            $this->dispatch('show-auth-modal', action: 'comment');
            return;
        }

        // Validación - al menos uno debe estar presente
        if (empty(trim($this->comentario)) && empty($this->selectedGif)) {
            $this->addError('comentario', 'Debes escribir un comentario o seleccionar un GIF.');
            return;
        }

        // Validación del texto si está presente
        if (!empty(trim($this->comentario))) {
            $this->validate([
                'comentario' => 'string|max:500|min:1',
            ], [
                'comentario.max' => 'El comentario no puede exceder los 500 caracteres.',
                'comentario.min' => 'El comentario debe tener al menos 1 caracter.',
            ]);
        }

        try {
            // Crear el comentario
            $comentarioText = !empty(trim($this->comentario)) ? trim($this->comentario) : null;
            
            $nuevoComentario = Comentario::create([
                'user_id' => Auth::id(),
                'post_id' => $this->post->id,
                'comentario' => $comentarioText,
                'gif_url' => $this->selectedGif,
            ]);

            // Crear notificación de comentario
            $notificationService = new NotificationService();
            $commentContent = $comentarioText ?: 'envió un GIF';
            $notificationService->createCommentNotification(Auth::user(), $this->post, $commentContent);

            // Emitir eventos para actualizar notificaciones
            $this->dispatch('notification-created');
            $this->dispatch('refreshNotifications');

            // Limpiar los campos
            $this->comentario = '';
            $this->selectedGif = null;

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
