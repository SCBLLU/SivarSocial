<div class="flex flex-col items-center w-full">
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
                    <!-- Imagen del post -->
                    <a href="{{ route('posts.show', ['user' => $post->user ? $post->user->username : 'usuario', 'post' => $post->id]) }}"
                        class="w-full flex justify-center bg-black rounded-b-none rounded-t-none">
                        <img src="{{ asset('uploads') . '/' . $post->imagen }}"
                            alt="Imagen del post {{ $post->titulo ?? 'sin título' }}"
                            class="object-cover w-full max-h-80 sm:max-h-96 aspect-square rounded-none">
                    </a>
                @elseif ($post->tipo === 'musica')
                    <!-- Publicacion de musica -->
                    <div class="w-full relative">
                        <div class="w-full p-3 sm:p-4 bg-[#000000] hover:bg-[#121212] transition-colors duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    @php
                                        $isItunes = $post->music_source === 'itunes' || !empty($post->itunes_track_id);
                                    @endphp

                                    @if ($isItunes)
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z" />
                                        </svg>
                                        <span class="text-white text-xs sm:text-sm font-medium">iTunes</span>
                                    @else
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#1DB954]" fill="currentColor" viewBox="0 0 168 168">
                                            <path
                                                d="M83.996 0C37.588 0 0 37.588 0 83.996s37.588 83.996 83.996 83.996 83.996-37.588 83.996-83.996S130.404 0 83.996 0zm38.404 121.17c-1.506 2.467-4.718 3.24-7.177 1.737-19.640-12.002-44.389-14.729-73.524-8.075-2.818.646-5.674-1.115-6.32-3.934-.646-2.818 1.115-5.674 3.934-6.32 31.9-7.291 59.263-4.15 81.337 9.34 2.46 1.51 3.24 4.72 1.75 7.18zm10.25-22.802c-1.89 3.075-5.91 4.045-8.98 2.155-22.51-13.839-56.823-17.846-83.448-9.764-3.453 1.043-7.1-.903-8.148-4.35-1.04-3.453.907-7.093 4.354-8.143 30.413-9.228 68.222-4.758 94.072 11.127 3.07 1.89 4.04 5.91 2.15 8.976zm.88-23.744c-26.99-16.031-71.52-17.505-97.289-9.684-4.138 1.255-8.514-1.081-9.768-5.219-1.254-4.14 1.08-8.513 5.221-9.771 29.581-8.98 78.756-7.245 109.83 11.202 3.722 2.209 4.943 7.016 2.737 10.733-2.2 3.722-7.02 4.949-10.73 2.739z" />
                                        </svg>
                                        <span class="text-[#1DB954] text-xs sm:text-sm font-medium">Spotify</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3 sm:gap-4 text-white">
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
                                            $isItunes = $post->music_source === 'itunes' || !empty($post->itunes_track_id);

                                            if ($isItunes) {
                                                $albumImage = $post->itunes_artwork_url;
                                                $trackName = $post->itunes_track_name;
                                                $artistName = $post->itunes_artist_name;
                                                $albumName = $post->itunes_collection_name;
                                                $externalUrl = $post->itunes_track_view_url;
                                                $previewUrl = $post->itunes_preview_url;
                                            } else {
                                                $albumImage = $post->spotify_album_image;
                                                $trackName = $post->spotify_track_name;
                                                $artistName = $post->spotify_artist_name;
                                                $albumName = $post->spotify_album_name;
                                                $externalUrl = $post->spotify_external_url;
                                                $previewUrl = $post->spotify_preview_url;
                                            }
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
                                            onclick="toggleMusicPreview('{{ $previewUrl }}', '{{ $post->id }}', '{{ $isItunes ? 'itunes' : 'spotify' }}')"
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
                <div class="w-full px-4 py-3">
                    <!-- Layout para móviles: título → descripción → acciones -->
                    <div class="block sm:hidden">
                        @if($post->tipo === 'musica')
                            <!-- Para posts de música: lógica especial para descripción -->
                            @if($post->titulo)
                                <!-- Si tiene título, mostrar título en su línea -->
                                <div class="mb-2">
                                    <span class="font-semibold text-black text-base">{{ $post->titulo }}</span>
                                </div>
                                <!-- Y descripción abajo si existe -->
                                @if($post->descripcion)
                                    <div class="mb-3">
                                        <p class="text-gray-700 text-xs">{{ $post->descripcion }}</p>
                                    </div>
                                @endif
                            @elseif($post->descripcion)
                                <!-- Si NO tiene título pero SÍ descripción, descripción va en línea del título -->
                                <div class="mb-2">
                                    <span class="text-gray-700 text-base">{{ $post->descripcion }}</span>
                                </div>
                            @endif
                        @else
                            <!-- Para posts de imagen: título y descripción separados -->
                            <!-- Título (solo si existe) -->
                            @if($post->titulo)
                                <div class="mb-2">
                                    <span class="font-semibold text-black text-base">{{ $post->titulo }}</span>
                                </div>
                            @endif

                            <!-- Descripción (solo si existe) -->
                            @if($post->descripcion)
                                <div class="mb-3">
                                    <p class="text-gray-700 text-xs">{{ $post->descripcion }}</p>
                                </div>
                            @endif
                        @endif

                        <!-- Acciones -->
                        <div class="flex items-center justify-start gap-4">
                            <livewire:comment-post :post="$post" color="gray" />
                            <livewire:like-post :post="$post" color="red" />
                        </div>
                    </div>

                    <!-- Layout para PC: título y acciones en la misma línea, descripción abajo -->
                    <div class="hidden sm:block">
                        <div class="flex items-center justify-between mb-2">
                            @if($post->tipo === 'musica')
                                <!-- Para posts de música: lógica especial para descripción -->
                                @if($post->titulo)
                                    <!-- Si tiene título, mostrar solo el título en esta línea -->
                                    <span class="font-semibold text-black text-lg">{{ $post->titulo }}</span>
                                @elseif($post->descripcion)
                                    <!-- Si NO tiene título pero SÍ descripción, descripción va en línea del título -->
                                    <span class="text-gray-700 text-lg">{{ $post->descripcion }}</span>
                                @else
                                    <span></span> <!-- Espacio vacío para mantener el layout -->
                                @endif
                            @else
                                <!-- Para posts de imagen: solo título -->
                                @if($post->titulo)
                                    <span class="font-semibold text-black text-lg">{{ $post->titulo }}</span>
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
                                <p class="text-gray-700 text-sm">{{ $post->descripcion }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Paginación -->
        <div class="w-full max-w-md sm:max-w-lg mt-8">
            {{ $posts->links('custom.pagination') }}
        </div>

    @else
        <p class="text-center text-gray-500 text-sm sm:text-base px-4">No hay post, sigue a alguien para ver sus posts</p>
    @endif
</div>