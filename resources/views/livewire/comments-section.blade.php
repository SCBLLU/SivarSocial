<!-- Contenedor principal de comentarios -->
<div class="flex flex-col h-full">
    <!-- Header de comentarios -->
    <div class="px-4 py-3 border-b border-gray-200 flex-shrink-0">
        <h2 class="text-lg font-bold text-center text-black">Comentarios</h2>
    </div>

    <!-- Lista de comentarios -->
    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1" id="comments-list">
        @if ($this->comentarios->count())
            @foreach ($this->comentarios as $index => $comentario)
                <div wire:key="comment-{{ $comentario->id }}" class="group relative" x-data="{ showOptions: false }"
                    @click.away="showOptions = false">

                    <!-- Comentario card -->
                    <div class="bg-white border border-gray-200 rounded-[20px] p-4 mb-3 shadow-sm">

                        <!-- Header del comentario con avatar y usuario -->
                        <div class="flex items-center gap-3 mb-3">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <img src="{{ $comentario->user && $comentario->user->imagen ? asset('perfiles/' . $comentario->user->imagen) : asset('img/img.jpg') }}"
                                    alt="Avatar de {{ $comentario->user->username }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-100"
                                    onerror="this.src='{{ asset('img/img.jpg') }}'">
                            </div>

                            <!-- Usuario, badge, timestamp y opciones -->
                            <div class="flex-1 min-w-0 flex items-center justify-between">
                                <div class="flex items-center gap-2 min-w-0">
                                    <a href="{{ route('posts.index', $comentario->user->username) }}"
                                        class="font-semibold text-sm text-gray-900 hover:text-blue-600 transition-colors truncate">
                                        {{ $comentario->user->username }}
                                    </a>
                                    @if($comentario->user_id === $post->user_id)
                                        <span
                                            class="px-2 py-1 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700 flex-shrink-0">Autor</span>
                                    @endif
                                </div>

                                <!-- Timestamp y opciones -->
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <!-- Timestamp -->
                                    <span class="text-xs text-gray-500">
                                        {{ $comentario->created_at->diffForHumans() }}
                                    </span>

                                    <!-- Botón de opciones -->
                                    @auth
                                        {{-- El botón de opciones se muestra si:
                                        - El usuario autenticado es el autor del comentario, O
                                        - El usuario autenticado es el autor del post --}}
                                        @if($comentario->user_id === auth()->id() || $post->user_id === auth()->id())
                                            <div class="relative">
                                                <button @click="showOptions = !showOptions"
                                                    class="p-1.5 rounded-full hover:bg-gray-100 transition-all duration-200 opacity-70 hover:opacity-100">
                                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                    </svg>
                                                </button>

                                                <!-- Menú de opciones -->
                                                <div x-show="showOptions" x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                    class="absolute top-8 right-0 bg-white rounded-2xl shadow-lg border border-gray-200 py-1 z-50 min-w-[140px]"
                                                    x-cloak>

                                                    <button wire:click.prevent="deleteComment({{ $comentario->id }})"
                                                        wire:confirm="¿Estás seguro de eliminar este comentario?"
                                                        wire:loading.attr="disabled" wire:target="deleteComment"
                                                        @click="showOptions = false"
                                                        class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2 rounded-xl mx-1 disabled:opacity-50">

                                                        <!-- Spinner de carga -->
                                                        <div wire:loading wire:target="deleteComment({{ $comentario->id }})"
                                                            class="w-4 h-4 border-2 border-red-600 border-t-transparent rounded-full animate-spin">
                                                        </div>

                                                        <!-- Icono de eliminar -->
                                                        <svg wire:loading.remove wire:target="deleteComment" class="w-4 h-4" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>

                                                        <span wire:loading.remove wire:target="deleteComment">
                                                            Eliminar comentario
                                                        </span>
                                                        <span wire:loading wire:target="deleteComment({{ $comentario->id }})">
                                                            Eliminando...
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- Contenido del comentario -->
                        <div>
                            <p class="text-sm text-gray-700 leading-relaxed break-words">
                                {{ $comentario->comentario }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach


            <!-- Botón cargar más comentarios -->
            @if($showLoadMore)
                <div class="py-3 text-center">
                    <button wire:click="loadMoreComments" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 transition-all duration-200 disabled:opacity-50">

                        <svg wire:loading.remove wire:target="loadMoreComments" class="w-4 h-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        <div wire:loading wire:target="loadMoreComments"
                            class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>

                        <span wire:loading.remove wire:target="loadMoreComments">Ver más comentarios</span>
                        <span wire:loading wire:target="loadMoreComments">Cargando...</span>
                    </button>
                </div>
            @endif
        @else
            <!-- Estado vacío -->
            <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                <div class="bg-gray-100 rounded-full p-4 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay comentarios</h3>
                <p class="text-gray-500 text-sm">Sé el primero en comentar esta publicación</p>
            </div>
        @endif
    </div>

    <!-- Formulario de comentario fijo -->
    <div class="px-4 py-3 border-t border-gray-200 flex-shrink-0 bg-white rounded-b-2xl">
        @auth
            @if ($successMessage)
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center gap-2 text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm font-medium">{{ $successMessage }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->has('comentario'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center gap-2 text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium">{{ $errors->first('comentario') }}</span>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="store" autocomplete="off">
                <div class="bg-gray-100 rounded-full p-2 flex items-center gap-2 sm:gap-3">
                    @if(auth()->user()->imagen)
                        <img src="{{ asset('perfiles/' . auth()->user()->imagen) }}" alt="Tu avatar"
                            class="w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover border-2 border-[#3B25DD] flex-shrink-0"
                            onerror="this.src='{{ asset('img/img.jpg') }}'">
                    @else
                        <img src="{{ asset('img/img.jpg') }}" alt="Tu avatar por defecto"
                            class="w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover border-2 border-[#3B25DD] flex-shrink-0">
                    @endif
                    <input type="text" wire:model="comentario"
                        class="flex-1 bg-transparent border-none outline-none text-xs sm:text-sm placeholder-gray-500 text-gray-800 {{ $errors->has('comentario') ? 'text-red-500' : '' }}"
                        placeholder="Agrega un comentario..." maxlength="500" required wire:loading.attr="disabled"
                        wire:target="store">
                    <button type="submit" wire:loading.attr="disabled" wire:target="store"
                        class="text-gray-800 hover:text-black hover:bg-gray-100 rounded-full p-1 sm:p-2 transition-all duration-200 transform hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-300 disabled:opacity-50"
                        title="Enviar comentario">
                        <div wire:loading wire:target="store" class="w-4 h-4 sm:w-5 sm:h-5">
                            <svg class="animate-spin w-full h-full" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </div>
                        <svg wire:loading.remove wire:target="store"
                            class="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-200 hover:rotate-12"
                            fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>
                @error('comentario')
                    <p class="text-red-500 text-xs mt-2 ml-8 sm:ml-11">{{ $message }}</p>
                @enderror
            </form>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-3 sm:p-4 text-center">
                <div class="mb-3">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-1">¿Quieres comentar?</p>
                    <p class="text-gray-500 text-xs">Inicia sesión para poder comentar esta publicación</p>
                </div>
                <a href="{{ route('login') }}"
                    class="inline-block bg-[#3B25DD] hover:bg-[#120073] text-white px-4 py-2 sm:px-6 sm:py-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    Iniciar Sesión
                </a>
            </div>
        @endauth
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-ocultar mensaje de éxito después de 4 segundos
        Livewire.on('auto-hide-message', () => {
            setTimeout(() => {
                @this.clearSuccessMessage();
            }, 4000);
        });

        // Scroll suave al último comentario cuando se agrega uno nuevo
        Livewire.on('comment-added', () => {
            setTimeout(() => {
                const commentsList = document.getElementById('comments-list');
                if (commentsList) {
                    // Scroll suave al final
                    commentsList.scrollTo({
                        top: commentsList.scrollHeight,
                        behavior: 'smooth'
                    });
                }

                // Disparar evento global para que otros componentes se actualicen
                document.dispatchEvent(new CustomEvent('comment-count-updated', {
                    detail: { postId: @js($post->id) }
                }));
            }, 150);
        });

        // Contador de caracteres para el input
        const inputField = document.querySelector('input[wire\\:model="comentario"]');
        const charCount = document.getElementById('char-count');

        if (inputField) {
            // Función para actualizar contador si existe
            function updateCharCount() {
                if (charCount) {
                    const currentLength = inputField.value.length;
                    charCount.textContent = currentLength;

                    // Cambiar color según límite usando clases de Tailwind
                    if (currentLength > 450) {
                        charCount.className = 'text-red-500 font-medium';
                    } else if (currentLength > 350) {
                        charCount.className = 'text-orange-500';
                    } else {
                        charCount.className = 'text-gray-500';
                    }
                }
            }

            // Actualizar contador al escribir
            inputField.addEventListener('input', updateCharCount);
            inputField.addEventListener('keyup', updateCharCount);
            inputField.addEventListener('paste', () => setTimeout(updateCharCount, 10));

            // Limpiar contador cuando se envía el comentario
            Livewire.on('comment-added', () => {
                if (charCount) {
                    charCount.textContent = '0';
                    charCount.className = 'text-gray-500';
                }
                inputField.value = '';
            });

            // Actualizar contador inicial
            updateCharCount();
        }

        // Scroll automático para nuevos comentarios cargados
        Livewire.on('comments-loaded', () => {
            const commentsList = document.getElementById('comments-list');
            if (commentsList) {
                // Mantener scroll en posición actual al cargar más comentarios
                const currentScrollPos = commentsList.scrollTop;
                setTimeout(() => {
                    commentsList.scrollTop = currentScrollPos;
                }, 100);
            }
        });

        // Cerrar menús de opciones al hacer clic fuera o al eliminar
        document.addEventListener('click', function (e) {
            // Cerrar todos los menús de opciones abiertos
            document.querySelectorAll('[x-data*="showOptions"]').forEach(element => {
                if (element.__x && element.__x.$data && !element.contains(e.target)) {
                    element.__x.$data.showOptions = false;
                }
            });
        });

        // Cerrar menús con tecla ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[x-data*="showOptions"]').forEach(element => {
                    if (element.__x && element.__x.$data) {
                        element.__x.$data.showOptions = false;
                    }
                });
            }
        });

        // Escuchar evento de comentario eliminado para actualizar UI
        Livewire.on('comment-deleted', (postId) => {
            // Cerrar todos los menús después de eliminar
            document.querySelectorAll('[x-data*="showOptions"]').forEach(element => {
                if (element.__x && element.__x.$data) {
                    element.__x.$data.showOptions = false;
                }
            });

            // Forzar una actualización del componente padre si existe
            if (window.Livewire) {
                setTimeout(() => {
                    // Disparar evento global para que otros componentes se actualicen
                    document.dispatchEvent(new CustomEvent('comment-count-updated', {
                        detail: { postId: postId }
                    }));
                }, 100);
            }

            console.log('Comentario eliminado del post:', postId);
        });

        // Mejorar el autosize del textarea (no aplicable para input)
        // Removido ya que ahora usamos input en lugar de textarea

        // El input no necesita autosize
    });

    // Función para enfocar el input (disponible globalmente)
    function focusCommentInput() {
        const inputField = document.querySelector('input[wire\\:model="comentario"]');
        if (inputField) {
            inputField.focus();
            // Posicionar cursor al final
            inputField.setSelectionRange(inputField.value.length, inputField.value.length);
        }
    }

    // Función para manejar atajos de teclado
    document.addEventListener('keydown', function (e) {
        // Enter para enviar comentario (ya que es un input)
        if (e.key === 'Enter') {
            const inputField = document.querySelector('input[wire\\:model="comentario"]:focus');
            if (inputField) {
                const form = inputField.closest('form');
                if (form) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                }
            }
        }
    });

    // Exponer función globalmente
    window.focusCommentInput = focusCommentInput;
</script>