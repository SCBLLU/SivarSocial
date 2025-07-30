<div class="flex flex-col items-center w-full">
    @if ($posts->count())
        @foreach ($posts as $post)
            <div class="bg-white rounded-2xl shadow-lg mb-10 w-full max-w-md flex flex-col items-center">

                <!-- Encabezado de la publicación -->
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

                <!-- Contenido de la publicación -->
                @if ($post->tipo === 'imagen')
                    <!-- Imagen del post -->
                    <a href="{{ route('posts.show', ['user' => $post->user ? $post->user->username : 'usuario', 'post' => $post->id]) }}"
                        class="w-full flex justify-center bg-black rounded-b-none rounded-t-none">
                        <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}"
                            class="object-cover w-full max-h-96 aspect-square rounded-none">
                    </a>
                @elseif ($post->tipo === 'musica')
                    <!-- Publicacion de musica -->
                    <a href="{{ route('posts.show', ['user' => $post->user ? $post->user->username : 'usuario', 'post' => $post->id]) }}"
                        class="w-full block">
                        <div class="w-full p-4 bg-[#000000] hover:bg-[#121212] transition-colors duration-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#1DB954]" fill="currentColor" viewBox="0 0 168 168">
                                        <path
                                            d="M83.996 0C37.588 0 0 37.588 0 83.996s37.588 83.996 83.996 83.996 83.996-37.588 83.996-83.996S130.404 0 83.996 0zm38.404 121.17c-1.506 2.467-4.718 3.24-7.177 1.737-19.640-12.002-44.389-14.729-73.524-8.075-2.818.646-5.674-1.115-6.32-3.934-.646-2.818 1.115-5.674 3.934-6.32 31.9-7.291 59.263-4.15 81.337 9.34 2.46 1.51 3.24 4.72 1.75 7.18zm10.25-22.802c-1.89 3.075-5.91 4.045-8.98 2.155-22.51-13.839-56.823-17.846-83.448-9.764-3.453 1.043-7.1-.903-8.148-4.35-1.04-3.453.907-7.093 4.354-8.143 30.413-9.228 68.222-4.758 94.072 11.127 3.07 1.89 4.04 5.91 2.15 8.976zm.88-23.744c-26.99-16.031-71.52-17.505-97.289-9.684-4.138 1.255-8.514-1.081-9.768-5.219-1.254-4.14 1.08-8.513 5.221-9.771 29.581-8.98 78.756-7.245 109.83 11.202 3.722 2.209 4.943 7.016 2.737 10.733-2.2 3.722-7.02 4.949-10.73 2.739z" />
                                    </svg>
                                    <span class="text-[#1DB954] text-sm font-medium">Spotify</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 text-white">

                                <!-- Imagen del álbum con esquinas redondeadas según lineamientos -->
                                <div class="relative flex-shrink-0">
                                    <img src="{{ $post->spotify_album_image ?? asset('img/usuario.svg') }}"
                                        alt="{{ $post->spotify_album_name }}"
                                        class="w-20 h-20 rounded-[4px] object-cover shadow-lg">
                                </div>

                                <!-- Información de la canción (metadata exacta de Spotify) -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-lg truncate text-white leading-tight">
                                        {{ $post->spotify_track_name }}
                                    </h3>
                                    <p class="text-gray-300 text-sm truncate mt-1">{{ $post->spotify_artist_name }}</p>
                                    <p class="text-gray-400 text-xs truncate">{{ $post->spotify_album_name }}</p>
                                </div>

                                <!-- Controles de reproducción a spotify -->
                                <div class="flex flex-col items-center gap-3">
                                    @if ($post->spotify_external_url)
                                        <button
                                            onclick="event.preventDefault(); event.stopPropagation(); window.open('{{ $post->spotify_external_url }}', '_blank'); return false;"
                                            class="flex items-center gap-1 bg-[#1DB954] hover:bg-[#1ed760] text-black text-xs font-medium px-3 py-1.5 rounded-full transition-all duration-300 hover:shadow-lg cursor-pointer z-10 relative">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 168 168">
                                                <path
                                                    d="M83.996 0C37.588 0 0 37.588 0 83.996s37.588 83.996 83.996 83.996 83.996-37.588 83.996-83.996S130.404 0 83.996 0zm38.404 121.17c-1.506 2.467-4.718 3.24-7.177 1.737-19.640-12.002-44.389-14.729-73.524-8.075-2.818.646-5.674-1.115-6.32-3.934-.646-2.818 1.115-5.674 3.934-6.32 31.9-7.291 59.263-4.15 81.337 9.34 2.46 1.51 3.24 4.72 1.75 7.18zm10.25-22.802c-1.89 3.075-5.91 4.045-8.98 2.155-22.51-13.839-56.823-17.846-83.448-9.764-3.453 1.043-7.1-.903-8.148-4.35-1.04-3.453.907-7.093 4.354-8.143 30.413-9.228 68.222-4.758 94.072 11.127 3.07 1.89 4.04 5.91 2.15 8.976zm.88-23.744c-26.99-16.031-71.52-17.505-97.289-9.684-4.138 1.255-8.514-1.081-9.768-5.219-1.254-4.14 1.08-8.513 5.221-9.771 29.581-8.98 78.756-7.245 109.83 11.202 3.722 2.209 4.943 7.016 2.737 10.733-2.2 3.722-7.02 4.949-10.73 2.739z" />
                                            </svg>
                                            Reproducir
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <br />
                        </div>
                    </a>

                @endif

                <!-- Acciones de la publicación -->
                <div class="w-full px-4 py-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-black text-lg">{{ $post->titulo }}</span>
                        <div class="flex items-center gap-4">
                            <livewire:comment-post :post="$post" color="gray" />
                            <livewire:like-post :post="$post" color="red" />
                        </div>
                    </div>

                    <!-- Descripción de la publicación -->
                    <div class="mb-3">
                        <p class="text-gray-700 text-sm">{{ $post->descripcion }}</p>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Paginación de publicaciones -->
        <div class="w-full flex justify-center">
            <div class="flex items-center justify-center w-full max-w-3xl">
                {{ $posts->links() }}
            </div>
        </div>
    @else
        <p class="text-center text-gray-500">No hay post, sigue a alguien para ver sus posts</p>
    @endif
</div>