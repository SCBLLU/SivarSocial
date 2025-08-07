<div>
    <!-- Lista de comentarios con scroll independiente -->
    <div class="px-4 py-3 overflow-y-auto flex-1 min-h-0" id="comments-list">
        @if ($this->comentarios->count())
            @foreach ($this->comentarios as $comentario)
                <div class="mb-4 last:mb-0">
                    <div class="bg-gray-100 rounded-2xl p-3 sm:p-4 shadow-sm">
                        <div class="flex items-start gap-3">
                            <img src="{{ $comentario->user && $comentario->user->imagen ? asset('perfiles/' . $comentario->user->imagen) : asset('img/img.jpg') }}"
                                alt="Avatar de {{ $comentario->user->username }}"
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-[#3B25DD] flex-shrink-0"
                                onerror="this.src='{{ asset('img/img.jpg') }}'">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-1 gap-1">
                                    <a href="{{ route('posts.index', $comentario->user->username) }}"
                                        class="font-semibold text-black text-xs sm:text-sm truncate">
                                        {{ $comentario->user->username }}
                                    </a>
                                    <span
                                        class="text-xs text-gray-500 flex-shrink-0">{{ ucfirst($comentario->created_at->diffForHumans()) }}</span>
                                </div>
                                <p class="text-gray-700 text-xs sm:text-sm break-words">
                                    {{ $comentario->comentario }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="flex flex-col items-center justify-center h-full py-8">
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="text-gray-500 text-xs sm:text-sm text-center">No hay comentarios aún</p>
            </div>
        @endif
    </div>

    <!-- Formulario de comentario FIJO al final -->
    <div class="px-4 py-3 border-t border-gray-200 flex-shrink-0 bg-white rounded-b-2xl">
        @auth
            @if ($successMessage)
                <div class="bg-green-500 text-white p-2 rounded-lg mb-4 text-center text-sm transition-opacity duration-500">
                    {{ $successMessage }}
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
                        class="flex-1 bg-transparent border-none outline-none text-xs sm:text-sm placeholder-gray-500 {{ $errors->has('comentario') ? 'text-red-500' : 'text-gray-800' }}"
                        placeholder="Agrega un comentario..." maxlength="255" required wire:loading.attr="disabled"
                        wire:target="store">
                    <button type="submit" wire:loading.attr="disabled" wire:target="store"
                        class="text-gray-800 hover:text-black hover:bg-gray-100 rounded-full p-1 sm:p-2 transition-all duration-200 transform hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-300 disabled:opacity-50"
                        title="Enviar comentario">
                        <div wire:loading wire:target="store" class="w-4 h-4 sm:w-5 sm:h-5">
                            <svg class="animate-spin w-full h-full text-gray-600" fill="none" viewBox="0 0 24 24">
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
        // Auto-ocultar mensaje de éxito después de 3 segundos
        Livewire.on('auto-hide-message', () => {
            setTimeout(() => {
                @this.clearSuccessMessage();
            }, 3000);
        });

        // Scroll automático al último comentario cuando se agrega uno nuevo
        Livewire.on('comment-added', () => {
            setTimeout(() => {
                const commentsList = document.getElementById('comments-list');
                if (commentsList) {
                    commentsList.scrollTop = commentsList.scrollHeight;
                }
            }, 100);
        });
    });
</script>