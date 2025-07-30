<div class="flex flex-col items-center w-full">
    @if ($posts->count())
        @foreach ($posts as $post)
            <div class="bg-white rounded-2xl shadow-lg mb-10 w-full max-w-md flex flex-col items-center">
                <!-- Header: perfil y username -->
                <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
                    <a href="{{ $post->user ? route('posts.index', $post->user) : '#' }}" class="flex items-center group">
                        <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/default-avatar.png') }}"
                            alt="Avatar de {{ $post->user ? $post->user->username : 'usuario' }}"
                            class="w-10 h-10 rounded-full object-cover border-2 border-[#3B25DD] group-hover:border-[#120073] transition">
                        <span class="ml-3 font-bold text-black group-hover:underline">
                            {{ $post->user ? ($post->user->name ?? $post->user->username) : 'usuario' }}

                        </span>

                    </a>
                    <span class="text-xs text-gray-500 ml-auto">{{ ucfirst($post->created_at->diffForHumans()) }}</span>
                </div>

                <!-- Contenido del post -->
                @if ($post->tipo === 'imagen')
                    <!-- Imagen del post -->
                    <a href="{{ route('posts.show', ['user' => $post->user ? $post->user->username : 'usuario', 'post' => $post->id]) }}"
                        class="w-full flex justify-center bg-black rounded-b-none rounded-t-none">
                        <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}"
                            class="object-cover w-full max-h-96 aspect-square rounded-none">
                    </a>
                @elseif ($post->tipo === 'musica')
                    <!-- Post musical -->
                    <div class="w-full p-4 bg-black border border-gray-600 rounded-lg">
                        <div class="flex items-center gap-4 text-white">
                            <!-- Imagen del álbum -->
                            <div class="relative flex-shrink-0">
                                <img src="{{ $post->spotify_album_image ?? asset('img/usuario.svg') }}" 
                                     alt="{{ $post->spotify_album_name }}"
                                     class="w-20 h-20 rounded-lg object-cover shadow-lg">
                                <!-- Indicador de música -->
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-white rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM15.657 6.343a1 1 0 011.414 0A9.972 9.972 0 0119 12a9.972 9.972 0 01-1.929 5.657 1 1 0 11-1.414-1.414A7.971 7.971 0 0017 12a7.971 7.971 0 00-1.343-4.243 1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        <path fill-rule="evenodd" d="M13.828 8.172a1 1 0 011.414 0A5.983 5.983 0 0117 12a5.983 5.983 0 01-1.758 3.828 1 1 0 11-1.414-1.414A3.987 3.987 0 0015 12a3.987 3.987 0 00-1.172-2.828 1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Información de la canción -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-lg truncate text-white">{{ $post->spotify_track_name }}</h3>
                                <p class="text-gray-300 text-sm truncate">{{ $post->spotify_artist_name }}</p>
                                <p class="text-gray-400 text-xs truncate">{{ $post->spotify_album_name }}</p>
                            </div>
                            
                            <!-- Controles de reproducción -->
                            <div class="flex flex-col items-center gap-2">
                                @if ($post->spotify_preview_url)
                                    <button onclick="togglePostPreview('{{ $post->spotify_preview_url }}', this, {{ $post->id }})" 
                                            class="w-12 h-12 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-all duration-300 group">
                                        <svg class="w-6 h-6 text-white play-icon group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg class="w-6 h-6 text-white pause-icon hidden group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                @endif
                                
                                @if ($post->spotify_external_url)
                                    <a href="{{ $post->spotify_external_url }}" target="_blank" 
                                       class="text-gray-400 hover:text-white text-xs transition-colors border border-gray-600 px-2 py-1 rounded hover:border-gray-400">
                                        Ver en Spotify ↗
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Detalles debajo de la imagen -->
                <div class="w-full px-4 py-3">
                    <!-- Título del post y botones de interacción en la misma línea -->
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-black text-lg">{{ $post->titulo }}</span>
                        <div class="flex items-center gap-4">
                            <!-- Componente de comentarios (siempre visible) -->
                            <livewire:comment-post :post="$post" color="gray" />

                            <!-- Componente de likes (siempre visible, pero interacción solo si está logueado) -->
                            <livewire:like-post :post="$post" color="red" />
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <p class="text-gray-700 text-sm">{{ $post->descripcion }}</p>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="my-10 text-black font-bold">
            {{ $posts->links() }}
        </div>
    @else
        <p class="text-center text-gray-500">No hay post, sigue a alguien para ver sus posts</p>
    @endif
</div>