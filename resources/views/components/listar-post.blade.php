<div class="flex flex-col items-center w-full justify-center">
    @if ($posts->count())
        @foreach ($posts as $post)
            <div class="bg-white rounded-2xl shadow-lg mb-6 sm:mb-10 w-full max-w-md sm:max-w-lg flex flex-col items-center">

                <!-- Encabezado de la publicación -->
                <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
                    <a href="{{ $post->user ? route('posts.index', $post->user) : '#' }}" class="flex items-center group">
                        <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/default-avatar.png') }}"
                            alt="Avatar de {{ $post->user ? $post->user->username : 'usuario' }}"
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-[#3B25DD] group-hover:border-[#120073] transition">
                        <span class="ml-3 font-bold text-black group-hover:underline text-sm sm:text-base">
                            {{ $post->user ? ($post->user->name ?? $post->user->username) : 'usuario' }}
                        </span>
                    </a>
                    <span class="text-xs text-gray-500 ml-auto">{{ ucfirst($post->created_at->diffForHumans()) }}</span>
                </div>

                <!-- Contenido de la publicación -->
                @if ($post->tipo === 'imagen')
                    <!-- Imagen del post mejorada para no recortar -->
                    <a href="{{ route('posts.show', ['user' => $post->user ? $post->user->username : 'usuario', 'post' => $post->id]) }}"
                        class="w-full flex justify-center bg-neutral-900 rounded-b-none rounded-t-none min-h-60 sm:min-h-80"
                        style="min-height:15rem;">
                        <img src="{{ asset('uploads') . '/' . $post->imagen }}"
                            alt="Imagen del post {{ $post->titulo ?? 'sin título' }}"
                            class="object-contain w-full max-h-80 sm:max-h-96 bg-neutral-900 mb-0"
                            style="background-color:#1a1a1a;margin-bottom:0;" loading="lazy">
                    </a>
                @elseif ($post->tipo === 'musica')
                    <!-- Publicacion de musica -->
                    <div class="w-full relative">
                        <div class="w-full p-3 sm:p-4 bg-[#000000] hover:bg-[#121212] transition-colors duration-200">

                            <div class="flex items-center gap-3 sm:gap-4 text-white py-4">
                                <!-- Enlace al post (solo imagen y texto) -->
                                <a href="{{ route('posts.show', ['user' => $post->user ? $post->user->username : 'usuario', 'post' => $post->id]) }}"
                                    class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">

                                    <!-- Imagen del álbum estilo Spotify/Instagram -->
                                    <div class="relative flex-shrink-0">
                                        @php
                                            $albumImage = null;
                                            $trackName = '';
                                            $artistName = '';
                                            $albumName = '';
                                            $externalUrl = '';
                                            $previewUrl = '';

                                            // Ahora solo usamos iTunes para las búsquedas principales
                                            $albumImage = $post->itunes_artwork_url;
                                            $trackName = $post->itunes_track_name;
                                            $artistName = $post->itunes_artist_name;
                                            $albumName = $post->itunes_collection_name;
                                            $externalUrl = $post->itunes_track_view_url;
                                            $previewUrl = $post->itunes_preview_url;
                                        @endphp

                                        <img src="{{ $albumImage ?: asset('img/img.jpg') }}"
                                            alt="{{ $albumName ?: 'Portada del álbum' }}"
                                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-[4px] object-cover shadow-lg">
                                    </div>

                                    <!-- Información de la canción estilo Spotify -->
                                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                                        <h4 class="font-semibold text-base sm:text-lg truncate text-white leading-tight">
                                            {{ $trackName ?: 'Canción desconocida' }}
                                        </h4>
                                        <p class="text-gray-300 text-xs sm:text-sm truncate mt-1">
                                            {{ $artistName ?: 'Artista desconocido' }}
                                        </p>
                                    </div>
                                </a>

                                <!-- Botón de reproducir vista previa - SEPARADO del enlace -->
                                @if($previewUrl)
                                    <div class="flex-shrink-0">
                                        <button type="button"
                                            class="play-button-{{ $post->id }} bg-white/20 hover:bg-white/30 text-white rounded-full p-2 sm:p-3 shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                                            onclick="toggleMusicPreview('{{ $previewUrl }}', '{{ $post->id }}', 'itunes')"
                                            title="Reproducir vista previa">
                                            <!-- Icono play -->
                                            <svg class="play-icon-{{ $post->id }} w-5 h-5 sm:w-6 sm:h-6 transition-all duration-200"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 5v14l11-7z" />
                                            </svg>
                                            <!-- Icono pause (oculto por defecto) -->
                                            <svg class="pause-icon-{{ $post->id }} w-5 h-5 sm:w-6 sm:h-6 hidden transition-all duration-200"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 9v6m4-6v6" />
                                            </svg>
                                            <!-- Icono loading (oculto por defecto) -->
                                            <svg class="loading-icon-{{ $post->id }} w-5 h-5 sm:w-6 sm:h-6 hidden animate-spin text-gray-300"
                                                fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                @endif

                <!-- Acciones de la publicación -->
                <div class="w-full px-4 pt-2 pb-3">
                    <!-- Layout unificado para móviles y PC: título y acciones en la misma línea -->
                    <div class="flex items-center justify-between mb-2">
                        @if($post->tipo === 'musica')
                            <!-- Para posts de música: lógica especial para descripción -->
                            @if($post->titulo)
                                <!-- Si tiene título, mostrar solo el título en esta línea -->
                                <span class="font-semibold text-black text-base sm:text-lg">{{ $post->titulo }}</span>
                            @elseif($post->descripcion)
                                <!-- Si NO tiene título pero SÍ descripción, descripción va en línea del título -->
                                <span class="text-gray-700 text-base sm:text-lg">{{ $post->descripcion }}</span>
                            @else
                                <span></span> <!-- Espacio vacío para mantener el layout -->
                            @endif
                        @else
                            <!-- Para posts de imagen: solo título -->
                            @if($post->titulo)
                                <span class="font-semibold text-black text-base sm:text-lg">{{ $post->titulo }}</span>
                            @else
                                <span></span> <!-- Espacio vacío para mantener el layout -->
                            @endif
                        @endif
                        <div class="flex items-center gap-4">
                            <livewire:comment-post :post="$post" color="gray" />
                            <livewire:like-post :post="$post" color="red" />
                        </div>
                    </div>

                    <!-- Descripción abajo solo para posts de música que tienen título Y descripción, o posts de imagen -->
                    @if(($post->tipo === 'musica' && $post->titulo && $post->descripcion) || ($post->tipo === 'imagen' && $post->descripcion))
                        <div class="mb-3">
                            <p class="text-gray-700 text-xs sm:text-sm">{{ $post->descripcion }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Paginación -->
        <!-- <div class="w-full max-w-md sm:max-w-lg mt-8"> -->
        {{ $posts->links('custom.pagination') }}
        <!-- </div> -->

    @else
        <p class="text-center text-gray-500 text-sm sm:text-base px-4">No hay post, sigue a alguien para ver sus posts</p>
    @endif
</div>

<script>
    // Limpiar estados de audio local al cargar la página de lista
    document.addEventListener('DOMContentLoaded', function () {
        // Limpiar cualquier estado de audio local de show.blade.php para evitar reproducciones no deseadas
        sessionStorage.removeItem('sivarsocial_show_audio_state');
    });

    // Pausar audio al salir de la página
    window.addEventListener('beforeunload', function () {
        if (window.pauseAllAudio) {
            window.pauseAllAudio();
        }
    });

    // Pausar audio al cambiar de página (para SPAs como Livewire)
    document.addEventListener('livewire:navigating', function () {
        if (window.pauseAllAudio) {
            window.pauseAllAudio();
        }
    });

    // Pausar audio cuando la página se oculta 
    document.addEventListener('visibilitychange', function () {
        if (document.hidden && window.pauseAllAudio) {
            window.pauseAllAudio();
        } else if (!document.hidden && window.restoreAudioState) {
            setTimeout(() => {
                window.restoreAudioState();
            }, 200);
        }
    });

</script>