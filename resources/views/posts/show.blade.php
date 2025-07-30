@extends('layouts.app')

@section('titulo')
    <div class="flex items-center justify-center relative w-full">
        <a href="{{ url()->previous() }}"
            class="absolute left-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-white text-2xl font-bold mx-auto">Publicación</h1>
    </div>
@endsection

@section('contenido')
    <div class="container mx-auto flex justify-center items-center px-4">
        <div class="max-w-6xl w-full">
            <!-- Contenido principal -->
            <div class="flex gap-8 w-full justify-center items-start">

                <!-- Post - Lado izquierdo -->
                @if($post->isMusicPost())
                    <!-- Post musical -->
                    <div id="post-container" class="bg-white rounded-2xl shadow-lg w-full max-w-md flex flex-col items-center">
                        <!-- Header: perfil y username -->
                        <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
                            <a href="{{ route('posts.index', $post->user->username) }}" class="flex items-center group">
                                <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/usuario.svg') }}"
                                    alt="Avatar de {{ $post->user->username }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-[#3B25DD] group-hover:border-[#120073] transition"
                                    onerror="this.src='{{ asset('img/usuario.svg') }}'">
                                <span class="ml-3 font-bold text-black group-hover:underline">
                                    {{ $post->user->name ?? $post->user->username }}
                                </span>
                            </a>
                            <span class="text-xs text-gray-500 ml-auto">{{ ucfirst($post->created_at->diffForHumans()) }}</span>
                        </div>

                        <!-- Contenido musical -->
                        <div class="w-full p-6 bg-black border border-gray-600 rounded-lg">
                            <div class="text-center mb-6">
                                <!-- Imagen de álbum más grande -->
                                <div class="relative inline-block">
                                    <img src="{{ $post->spotify_album_image ?? asset('img/usuario.svg') }}" 
                                         alt="{{ $post->spotify_album_name }}"
                                         class="w-32 h-32 rounded-2xl object-cover shadow-2xl mx-auto">
                                    <!-- Indicador de música -->
                                    <div class="absolute -top-3 -right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM15.657 6.343a1 1 0 011.414 0A9.972 9.972 0 0119 12a9.972 9.972 0 01-1.929 5.657 1 1 0 11-1.414-1.414A7.971 7.971 0 0017 12a7.971 7.971 0 00-1.343-4.243 1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            <path fill-rule="evenodd" d="M13.828 8.172a1 1 0 011.414 0A5.983 5.983 0 0117 12a5.983 5.983 0 01-1.758 3.828 1 1 0 11-1.414-1.414A3.987 3.987 0 0015 12a3.987 3.987 0 00-1.172-2.828 1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Información de la canción -->
                                <div class="mt-4 text-white">
                                    <h2 class="text-2xl font-bold mb-2 text-white">{{ $post->spotify_track_name }}</h2>
                                    <p class="text-gray-300 text-lg mb-1">{{ $post->spotify_artist_name }}</p>
                                    <p class="text-gray-400 text-sm">{{ $post->spotify_album_name }}</p>
                                </div>
                                
                                <!-- Controles de reproducción -->
                                <div class="flex items-center justify-center gap-4 mt-6">
                                    @if ($post->spotify_preview_url)
                                        <button onclick="togglePostPreview('{{ $post->spotify_preview_url }}', this, {{ $post->id }})" 
                                                class="w-16 h-16 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-all duration-300 group hover:scale-110">
                                            <svg class="w-8 h-8 text-white play-icon group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                            </svg>
                                            <svg class="w-8 h-8 text-white pause-icon hidden group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    @if ($post->spotify_external_url)
                                        <a href="{{ $post->spotify_external_url }}" target="_blank" 
                                           class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-full text-white text-sm transition-all hover:scale-105 border border-gray-600 hover:border-gray-400">
                                            Ver en Spotify ↗
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Detalles debajo del contenido musical -->
                        <div class="w-full px-4 py-3">
                            <!-- Título del post y botones de interacción en la misma línea -->
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-black text-lg">{{ $post->titulo }}</span>
                                <div class="flex items-center gap-4">
                                    <!-- Componente de comentarios -->
                                    <livewire:comment-post :post="$post" color="gray" />
                                    <!-- Componente de likes -->
                                    <livewire:like-post :post="$post" color="red" />
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="mb-3">
                                <p class="text-gray-700 text-sm">{{ $post->descripcion }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Post de imagen -->
                    <div id="post-container" class="bg-white rounded-2xl shadow-lg w-full max-w-md flex flex-col items-center">
                        <!-- Header: perfil y username -->
                        <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
                            <a href="{{ route('posts.index', $post->user->username) }}" class="flex items-center group">
                                <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/usuario.svg') }}"
                                    alt="Avatar de {{ $post->user->username }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-[#3B25DD] group-hover:border-[#120073] transition"
                                    onerror="this.src='{{ asset('img/usuario.svg') }}'">
                                <span class="ml-3 font-bold text-black group-hover:underline">
                                    {{ $post->user->name ?? $post->user->username }}
                                </span>
                            </a>
                            <span class="text-xs text-gray-500 ml-auto">{{ ucfirst($post->created_at->diffForHumans()) }}</span>
                        </div>

                        <!-- Imagen del post -->
                        <div class="w-full flex justify-center bg-black rounded-b-none rounded-t-none">
                            <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}"
                                class="object-cover w-full max-h-96 aspect-square rounded-none">
                        </div>

                        <!-- Detalles debajo de la imagen -->
                        <div class="w-full px-4 py-3">
                            <!-- Título del post y botones de interacción en la misma línea -->
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-black text-lg">{{ $post->titulo }}</span>
                                <div class="flex items-center gap-4">
                                    <!-- Componente de comentarios -->
                                    <livewire:comment-post :post="$post" color="gray" />
                                    <!-- Componente de likes -->
                                    <livewire:like-post :post="$post" color="red" />
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="mb-3">
                                <p class="text-gray-700 text-sm">{{ $post->descripcion }}</p>
                            </div>

                            @auth
                                @if ($post->user_id === Auth::user()->id)
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" value="Eliminar Post"
                                            class="bg-red-500 hover:bg-red-600 transition-colors cursor-pointer font-bold p-2 text-white rounded-lg w-full text-sm">
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endif

                <!-- Comentarios - Lado derecho con altura igual al post -->
                <div id="comments-container"
                    class="bg-white rounded-2xl shadow-lg w-full max-w-md flex flex-col min-h-[500px]">
                    <!-- Header de comentarios -->
                    <div class="px-4 py-3 border-b border-gray-200 flex-shrink-0">
                        <h2 class="text-lg font-bold text-center text-black">Comentarios</h2>
                    </div>

                    <!-- Lista de comentarios con scroll independiente -->
                    <div class="px-4 py-3 overflow-y-auto flex-1 min-h-0">
                        @if ($post->comentarios->count())
                            @foreach ($post->comentarios as $comentario)
                                <div class="mb-4 last:mb-0">
                                    <div class="bg-gray-100 rounded-2xl p-4 shadow-sm">
                                        <div class="flex items-start gap-3">
                                            <img src="{{ $comentario->user && $comentario->user->imagen ? asset('perfiles/' . $comentario->user->imagen) : asset('img/usuario.svg') }}"
                                                alt="Avatar de {{ $comentario->user->username }}"
                                                class="w-10 h-10 rounded-full object-cover border-2 border-[#3B25DD] flex-shrink-0"
                                                onerror="this.src='{{ asset('img/usuario.svg') }}'">
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <a href="{{ route('posts.index', $comentario->user->username) }}"
                                                        class="font-semibold text-black text-sm">
                                                        {{ $comentario->user->username }}
                                                    </a>
                                                    <span
                                                        class="text-xs text-gray-500">{{ ucfirst($comentario->created_at->diffForHumans()) }}</span>
                                                </div>
                                                <p class="text-gray-700 text-sm">{{ $comentario->comentario }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-8">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <p class="text-gray-500 text-sm text-center">No hay comentarios aún</p>
                            </div>
                        @endif
                    </div>

                    <!-- Formulario de comentario FIJO al final -->
                    <div class="px-4 py-3 border-t border-gray-200 flex-shrink-0 bg-white rounded-b-2xl">
                        @auth
                            @if (session('success'))
                                <div id="success-message"
                                    class="bg-green-500 text-white p-2 rounded-lg mb-4 text-center text-sm transition-opacity duration-500">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('mensaje'))
                                <div id="mensaje-success"
                                    class="bg-green-500 text-white p-2 rounded-lg mb-4 text-center text-sm transition-opacity duration-500">
                                    {{ session('mensaje') }}
                                </div>
                            @endif

                            <form
                                action="{{ route('comentarios.store', ['user' => $post->user->username, 'post' => $post->id]) }}"
                                method="POST" autocomplete="off">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <div class="bg-gray-100 rounded-full p-2 flex items-center gap-3">
                                    @if(auth()->user()->imagen)
                                        <img src="{{ asset('perfiles/' . auth()->user()->imagen) }}" alt="Tu avatar"
                                            class="w-8 h-8 rounded-full object-cover border-2 border-[#3B25DD] flex-shrink-0"
                                            onerror="this.src='{{ asset('img/usuario.svg') }}'">
                                    @else
                                        <img src="{{ asset('img/usuario.svg') }}" alt="Tu avatar por defecto"
                                            class="w-8 h-8 rounded-full object-cover border-2 border-[#3B25DD] flex-shrink-0">
                                    @endif
                                    <input type="text" id="comentario" name="comentario"
                                        class="flex-1 bg-transparent border-none outline-none text-sm placeholder-gray-500 text-gray-800 {{ $errors->has('comentario') ? 'text-red-500' : '' }}"
                                        placeholder="Agrega un comentario..." value="{{ old('comentario') }}" maxlength="255"
                                        required>
                                    <button type="submit"
                                        class="text-gray-800 hover:text-black hover:bg-gray-100 rounded-full p-2 transition-all duration-200 transform hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-300"
                                        title="Enviar comentario">
                                        <svg class="w-5 h-5 transition-transform duration-200 hover:rotate-12"
                                            fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </button>
                                </div>
                                @error('comentario')
                                    <p class="text-red-500 text-xs mt-2 ml-11">{{ $message }}</p>
                                @enderror
                            </form>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-center">
                                <div class="mb-3">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <p class="text-gray-600 text-sm font-medium mb-1">¿Quieres comentar?</p>
                                    <p class="text-gray-500 text-xs">Inicia sesión para poder comentar esta publicación</p>
                                </div>
                                <a href="{{ route('login') }}"
                                    class="inline-block bg-[#3B25DD] hover:bg-[#120073] text-white px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                                    Iniciar Sesión
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Función para igualar alturas sin saltos visuales
        function matchHeights() {
            const postContainer = document.getElementById('post-container');
            const commentsContainer = document.getElementById('comments-container');

            if (postContainer && commentsContainer) {
                // Obtener altura del post
                const postHeight = postContainer.offsetHeight;

                // Solo aplicar si hay una diferencia significativa (evitar micro-ajustes)
                const currentHeight = commentsContainer.offsetHeight;
                if (Math.abs(postHeight - currentHeight) > 10) {
                    commentsContainer.style.height = postHeight + 'px';
                }
            }
        }

        // Ejecutar cuando la página carga completamente
        document.addEventListener('DOMContentLoaded', function () {
            // Esperar a que todas las imágenes y contenido se carguen
            const images = document.querySelectorAll('img');
            let loadedImages = 0;
            const totalImages = images.length;

            function checkAllLoaded() {
                loadedImages++;
                if (loadedImages === totalImages) {
                    // Todas las imágenes han cargado, ahora igualar alturas
                    setTimeout(matchHeights, 50);
                }
            }

            if (totalImages === 0) {
                // No hay imágenes, ejecutar inmediatamente
                setTimeout(matchHeights, 50);
            } else {
                // Esperar a que todas las imágenes carguen
                images.forEach(img => {
                    if (img.complete) {
                        checkAllLoaded();
                    } else {
                        img.addEventListener('load', checkAllLoaded);
                        img.addEventListener('error', checkAllLoaded); // También contar errores
                    }
                });
            }

            // También ejecutar cuando se redimensiona la ventana
            window.addEventListener('resize', function () {
                setTimeout(matchHeights, 100);
            });
        });

        // Para componentes Livewire (cuando se actualice el contenido)
        document.addEventListener('livewire:navigated', function () {
            setTimeout(matchHeights, 100);
        });

        document.addEventListener('livewire:load', function () {
            setTimeout(matchHeights, 100);
        });

        // Auto-ocultar mensajes de éxito después de 4 segundos
        document.addEventListener('DOMContentLoaded', function () {
            // Función para ocultar mensajes
            function hideMessage(elementId) {
                const element = document.getElementById(elementId);
                if (element) {
                    setTimeout(function () {
                        element.style.opacity = '0';
                        setTimeout(function () {
                            element.style.display = 'none';
                        }, 500); // Esperar a que termine la transición
                    }, 4000); // 4 segundos
                }
            }

            // Ocultar ambos tipos de mensajes
            hideMessage('success-message');
            hideMessage('mensaje-success');
        });
    </script>
@endsection