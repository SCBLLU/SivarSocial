<!-- Contenedor principal de comentarios -->
<div class="flex flex-col h-auto lg:h-full">
    <!-- Header de comentarios -->
    <div class="px-4 py-3 border-b border-gray-200 flex-shrink-0">
        <h2 class="text-lg font-bold text-center text-black">Comentarios</h2>
    </div>

    <!-- Lista de comentarios -->
    <div class="@if($this->comentarios->count()) flex-1 overflow-y-auto @else flex-shrink-0 lg:flex-1 @endif px-4 py-4 space-y-1"
        id="comments-list">
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
                                        class="font-semibold text-sm text-gray-900 hover:text-blue-600 transition-colors truncate flex items-center gap-1">
                                        {{ $comentario->user->username }}
                                        <x-user-badge :badge="$comentario->user->insignia ?? null" size="medium" />
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
                                                            Eliminar
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
                            @if($comentario->hasOnlyGif())
                                <!-- Solo GIF -->
                                <div class="mt-2">
                                    <img src="{{ $comentario->gif_url }}" alt="GIF"
                                        class="max-w-full h-auto rounded-xl shadow-sm max-h-48 object-contain border border-gray-100">
                                </div>
                            @elseif($comentario->hasGif())
                                <!-- Texto + GIF -->
                                @if(!empty($comentario->comentario))
                                    <p class="text-sm text-gray-700 leading-relaxed break-words mb-2">
                                        {{ $comentario->comentario }}
                                    </p>
                                @endif
                                <div class="mt-2">
                                    <img src="{{ $comentario->gif_url }}" alt="GIF"
                                        class="max-w-full h-auto rounded-xl shadow-sm max-h-48 object-contain border border-gray-100">
                                </div>
                            @else
                                <!-- Solo texto -->
                                <p class="text-sm text-gray-700 leading-relaxed break-words">
                                    {{ $comentario->comentario }}
                                </p>
                            @endif
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
            <div class="flex flex-col items-center justify-center py-6 lg:py-12 px-4 text-center">
                <div class="bg-gray-100 rounded-full p-3 lg:p-4 mb-3 lg:mb-4">
                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-base lg:text-lg font-medium text-gray-900 mb-1 lg:mb-2">No hay comentarios</h3>
                <p class="text-gray-500 text-sm">Sé el primero en comentar esta publicación</p>
            </div>
        @endif
    </div>

    <!-- Formulario de comentario fijo -->
    <div class="px-4 py-4 border-t border-gray-100 flex-shrink-0 bg-white rounded-b-2xl">
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

            <!-- Preview del GIF seleccionado -->
            @if($selectedGif)
                <div class="mb-3 p-2 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="flex items-start gap-2">
                        <img src="{{ $selectedGif }}" alt="GIF seleccionado"
                            class="w-12 h-12 object-cover rounded-lg shadow-sm">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-700">GIF seleccionado</p>
                            <p class="text-xs text-gray-500 truncate">Se enviará con tu comentario</p>
                        </div>
                        <button type="button" wire:click="removeSelectedGif"
                            class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="store" autocomplete="off">
                <div
                    class="bg-white border border-gray-200 rounded-full p-2 flex items-center gap-2 sm:gap-3 hover:border-gray-300 transition-colors">
                    @if(auth()->user()->imagen)
                        <img src="{{ asset('perfiles/' . auth()->user()->imagen) }}" alt="Tu avatar"
                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover border-2 border-gray-100 flex-shrink-0"
                            onerror="this.src='{{ asset('img/img.jpg') }}'">
                    @else
                        <img src="{{ asset('img/img.jpg') }}" alt="Tu avatar por defecto"
                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover border-2 border-gray-100 flex-shrink-0">
                    @endif
                    <input type="text" wire:model="comentario"
                        class="flex-1 min-w-0 bg-transparent border-none outline-none text-sm placeholder-gray-400 text-gray-900 {{ $errors->has('comentario') ? 'text-red-500' : '' }}"
                        placeholder="{{ $selectedGif ? 'Agrega un comentario (opcional)...' : 'Agrega un comentario...' }}"
                        maxlength="500" wire:loading.attr="disabled" wire:target="store">

                    <!-- Botón GIF  -->
                    <button type="button" wire:click="toggleGifModal"
                        class="flex items-center justify-center text-xs font-bold text-black bg-gray-200 rounded-lg px-2.5 py-1 transition-all duration-200 transform hover:scale-110 active:scale-95 focus:outline-none {{ $selectedGif ? 'bg-gray-300' : '' }}"
                        title="Agregar GIF">
                        GIF
                    </button>



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
                            class="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-200 hover:rotate-50 transform rotate-90"
                            fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.1"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>
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

    <!-- Modal de Selección de GIFs -->
    @if($showGifModal)
        <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
            wire:click="toggleGifModal" x-data="{ show: true }" x-show="show"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @keydown.escape.window="$wire.toggleGifModal()">
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-hidden" wire:click.stop
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Seleccionar GIF</h3>
                        <button wire:click="toggleGifModal"
                            class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-full hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6" x-data="giphySelector('{{ $this->giphyApiKey }}')">
                    <!-- Buscador -->
                    <div class="mb-6">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" x-model="searchTerm" @input="searchGifs" placeholder="Buscar GIFs..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#3B25DD] focus:border-transparent text-sm bg-gray-50 hover:bg-white transition-colors">
                        </div>
                    </div>

                    <!-- Categorías populares -->
                    <div x-show="searchTerm === ''" class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-3">Populares</p>
                        <div class="flex flex-wrap gap-2">
                            <template
                                x-for="category in ['😀 Feliz', '😂 Divertido', '❤️ Amor', '🎉 Emocionado', '👍 Bien', '👏 Aplausos']">
                                <button @click="quickSearch(category.split(' ')[1])"
                                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-full text-sm text-gray-700 transition-all duration-200 hover:scale-105 font-medium">
                                    <span x-text="category"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Loading -->
                    <div x-show="loading" class="text-center py-8">
                        <div
                            class="animate-spin w-8 h-8 border-2 border-[#3B25DD] border-t-transparent rounded-full mx-auto">
                        </div>
                        <p class="text-gray-500 text-sm mt-2">Cargando GIFs...</p>
                    </div>

                    <!-- Error -->
                    <div x-show="error && !loading" class="text-center py-8">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-red-500 text-sm mb-2">Error al cargar GIFs</p>
                        <button @click="loadTrendingGifs()" class="text-[#3B25DD] hover:text-[#120073] text-sm font-medium">
                            Intentar de nuevo
                        </button>
                    </div>

                    <!-- Grid de GIFs -->
                    <div x-show="!loading && !error && gifs.length > 0"
                        class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-96 overflow-y-auto">
                        <template x-for="gif in gifs" :key="gif.id">
                            <div class="cursor-pointer rounded-2xl overflow-hidden hover:opacity-90 transition-all duration-200 hover:scale-105 transform shadow-sm hover:shadow-md"
                                @click="selectGif(gif.images.fixed_height.url)">
                                <img :src="gif.images.fixed_height_small.url" :alt="gif.title"
                                    class="w-full h-28 object-cover" loading="lazy">
                            </div>
                        </template>
                    </div>

                    <!-- Estado vacío -->
                    <div x-show="!loading && !error && gifs.length === 0 && searchTerm !== ''" class="text-center py-8">
                        <p class="text-gray-500 text-sm">No se encontraron GIFs</p>
                    </div>

                    <!-- Estado inicial -->
                    <div x-show="!loading && !error && gifs.length === 0 && searchTerm === ''" class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500 text-sm">Busca un GIF para agregar a tu comentario</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Componente Alpine.js para el selector de GIFs
    function giphySelector(apiKey) {
        return {
            searchTerm: '',
            gifs: [],
            loading: false,
            searchTimeout: null,
            error: false,

            init() {
                this.loadTrendingGifs();
            },

            async loadTrendingGifs() {
                this.loading = true;
                this.error = false;
                try {
                    const response = await fetch(`https://api.giphy.com/v1/gifs/trending?api_key=${apiKey}&limit=20&rating=pg`);
                    if (!response.ok) throw new Error('Error en la respuesta de la API');
                    const data = await response.json();
                    this.gifs = data.data || [];
                } catch (error) {
                    console.error('Error loading trending GIFs:', error);
                    this.error = true;
                    this.gifs = [];
                } finally {
                    this.loading = false;
                }
            },

            searchGifs() {
                // Debounce la búsqueda
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.performSearch();
                }, 500);
            },

            async performSearch() {
                if (this.searchTerm.trim() === '') {
                    this.loadTrendingGifs();
                    return;
                }

                this.loading = true;
                this.error = false;
                try {
                    const response = await fetch(`https://api.giphy.com/v1/gifs/search?api_key=${apiKey}&q=${encodeURIComponent(this.searchTerm)}&limit=20&rating=pg`);
                    if (!response.ok) throw new Error('Error en la búsqueda de GIFs');
                    const data = await response.json();
                    this.gifs = data.data || [];
                } catch (error) {
                    console.error('Error searching GIFs:', error);
                    this.error = true;
                    this.gifs = [];
                } finally {
                    this.loading = false;
                }
            },

            selectGif(gifUrl) {
                @this.selectGif(gifUrl);
            },

            quickSearch(term) {
                this.searchTerm = term;
                this.performSearch();
            }
        }
    }
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
        });
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