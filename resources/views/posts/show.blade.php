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
    <div class="container mx-auto flex justify-center items-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl w-full">
            <!-- Contenido principal -->
            <div class="flex flex-col lg:flex-row gap-4 lg:gap-8 w-full justify-center items-start">

                <!-- Post - Lado izquierdo -->
                @if($post->isMusicPost())
                    <!-- Post musical -->
                    <div id="post-container"
                        class="bg-white rounded-2xl shadow-lg w-full lg:max-w-md flex flex-col items-center">
                        <!-- Header: perfil y username -->
                        <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
                            <a href="{{ route('posts.index', $post->user->username) }}" class="flex items-center group">
                                <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/usuario.svg') }}"
                                    alt="Avatar de {{ $post->user->username }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-[#3B25DD] group-hover:border-[#120073] transition"
                                    onerror="this.src='{{ asset('img/usuario.svg') }}'">
                                <span class="ml-3 font-bold text-black group-hover:underline text-sm sm:text-base">
                                    {{ $post->user->name ?? $post->user->username }}
                                </span>
                            </a>
                            <span class="text-xs text-gray-500 ml-auto">{{ ucfirst($post->created_at->diffForHumans()) }}</span>
                        </div>

                        <!-- Contenido musical principal -->
                        <div class="w-full p-4 sm:p-6 bg-gradient-to-br from-[#121212] to-[#1a1a1a] relative overflow-hidden">
                            <!-- Fondo decorativo con el color dominante -->
                            <div class="absolute inset-0 opacity-10"
                                style="background: linear-gradient(135deg, {{ $post->dominant_color ?? '#1DB954' }} 0%, transparent 50%);">
                            </div>

                            <!-- Spotify Brand Attribution -->
                            <div
                                class="flex flex-col sm:flex-row items-center justify-between mb-4 sm:mb-6 relative z-10 gap-3">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-[#1DB954]" fill="currentColor" viewBox="0 0 168 168">
                                        <path
                                            d="M83.996 0C37.588 0 0 37.588 0 83.996s37.588 83.996 83.996 83.996 83.996-37.588 83.996-83.996S130.404 0 83.996 0zm38.404 121.17c-1.506 2.467-4.718 3.24-7.177 1.737-19.640-12.002-44.389-14.729-73.524-8.075-2.818.646-5.674-1.115-6.32-3.934-.646-2.818 1.115-5.674 3.934-6.32 31.9-7.291 59.263-4.15 81.337 9.34 2.46 1.51 3.24 4.72 1.75 7.18zm10.25-22.802c-1.89 3.075-5.91 4.045-8.98 2.155-22.51-13.839-56.823-17.846-83.448-9.764-3.453 1.043-7.1-.903-8.148-4.35-1.04-3.453.907-7.093 4.354-8.143 30.413-9.228 68.222-4.758 94.072 11.127 3.07 1.89 4.04 5.91 2.15 8.976zm.88-23.744c-26.99-16.031-71.52-17.505-97.289-9.684-4.138 1.255-8.514-1.081-9.768-5.219-1.254-4.14 1.08-8.513 5.221-9.771 29.581-8.98 78.756-7.245 109.83 11.202 3.722 2.209 4.943 7.016 2.737 10.733-2.2 3.722-7.02 4.949-10.73 2.739z" />
                                    </svg>
                                </div>

                                @if($post->spotify_external_url)
                                    <a href="{{ $post->spotify_external_url }}" target="_blank"
                                        class="bg-[#1DB954] hover:bg-[#1ed760] text-black px-3 py-2 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-bold transition-all duration-300 hover:scale-105 shadow-lg">
                                        Abrir en Spotify
                                    </a>
                                @endif
                            </div>

                            <!-- Contenido principal de la canción -->
                            <div class="relative z-10 space-y-4 sm:space-y-6">


                                <!-- Iframe de Spotify si está disponible -->
                                @if($post->spotify_track_id)
                                    <div class="bg-black/20 rounded-xl p-3 sm:p-4 backdrop-blur-sm">
                                        <iframe
                                            src="https://open.spotify.com/embed/track/{{ $post->spotify_track_id }}?utm_source=generator&theme=0"
                                            width="100%" height="152" frameBorder="0" allowfullscreen=""
                                            allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                                            loading="lazy" class="rounded-lg">
                                        </iframe>
                                    </div>
                                @endif

                                <!-- Atribución requerida por Spotify -->
                                <div class="flex flex-col items-center mt-4">
                                    <span class="text-white/50 text-xs mb-1">Powered by <a href="https://spotify.com"
                                            target="_blank" class="hover:text-white/60 transition-colors">Spotify</a></span>

                                </div>
                            </div>
                        </div>

                        <!-- Detalles debajo del contenido musical -->
                        <div class="w-full px-4 py-3">
                            <!-- Título del post y botones de interacción en la misma línea -->
                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-2 gap-2 sm:gap-0">
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
                    <div id="post-container"
                        class="bg-white rounded-2xl shadow-lg w-full lg:max-w-md flex flex-col items-center">
                        <!-- Header: perfil y username -->
                        <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
                            <a href="{{ route('posts.index', $post->user->username) }}" class="flex items-center group">
                                <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/usuario.svg') }}"
                                    alt="Avatar de {{ $post->user->username }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-[#3B25DD] group-hover:border-[#120073] transition"
                                    onerror="this.src='{{ asset('img/usuario.svg') }}'">
                                <span class="ml-3 font-bold text-black group-hover:underline text-sm sm:text-base">
                                    {{ $post->user->name ?? $post->user->username }}
                                </span>
                            </a>
                            <span class="text-xs text-gray-500 ml-auto">{{ ucfirst($post->created_at->diffForHumans()) }}</span>
                        </div>

                        <!-- Imagen del post -->
                        <div class="w-full flex justify-center bg-black rounded-b-none rounded-t-none">
                            <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}"
                                class="object-cover w-full max-h-96 sm:max-h-[500px] lg:max-h-96 aspect-square rounded-none">
                        </div>

                        <!-- Detalles debajo de la imagen -->
                        <div class="w-full px-4 py-3">
                            <!-- Título del post y botones de interacción en la misma línea -->
                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-2 gap-2 sm:gap-0">
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
                    class="bg-white rounded-2xl shadow-lg w-full lg:max-w-md flex flex-col min-h-[500px] mt-4 lg:mt-0">
                    <!-- Header de comentarios -->
                    <div class="px-4 py-3 border-b border-gray-200 flex-shrink-0">
                        <h2 class="text-lg font-bold text-center text-black">Comentarios</h2>
                    </div>

                    <!-- Lista de comentarios con scroll independiente -->
                    <div class="px-4 py-3 overflow-y-auto flex-1 min-h-0">
                        @if ($post->comentarios->count())
                            @foreach ($post->comentarios as $comentario)
                                <div class="mb-4 last:mb-0">
                                    <div class="bg-gray-100 rounded-2xl p-3 sm:p-4 shadow-sm">
                                        <div class="flex items-start gap-3">
                                            <img src="{{ $comentario->user && $comentario->user->imagen ? asset('perfiles/' . $comentario->user->imagen) : asset('img/usuario.svg') }}"
                                                alt="Avatar de {{ $comentario->user->username }}"
                                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-[#3B25DD] flex-shrink-0"
                                                onerror="this.src='{{ asset('img/usuario.svg') }}'">
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
                                <div class="bg-gray-100 rounded-full p-2 flex items-center gap-2 sm:gap-3">
                                    @if(auth()->user()->imagen)
                                        <img src="{{ asset('perfiles/' . auth()->user()->imagen) }}" alt="Tu avatar"
                                            class="w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover border-2 border-[#3B25DD] flex-shrink-0"
                                            onerror="this.src='{{ asset('img/usuario.svg') }}'">
                                    @else
                                        <img src="{{ asset('img/usuario.svg') }}" alt="Tu avatar por defecto"
                                            class="w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover border-2 border-[#3B25DD] flex-shrink-0">
                                    @endif
                                    <input type="text" id="comentario" name="comentario"
                                        class="flex-1 bg-transparent border-none outline-none text-xs sm:text-sm placeholder-gray-500 text-gray-800 {{ $errors->has('comentario') ? 'text-red-500' : '' }}"
                                        placeholder="Agrega un comentario..." value="{{ old('comentario') }}" maxlength="255"
                                        required>
                                    <button type="submit"
                                        class="text-gray-800 hover:text-black hover:bg-gray-100 rounded-full p-1 sm:p-2 transition-all duration-200 transform hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-300"
                                        title="Enviar comentario">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-200 hover:rotate-12"
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
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400 mx-auto mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
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

            </div>
        </div>
    </div>

    <script>
        // Audio global para preview
        let currentPreviewAudio = null;

        // Función para alternar preview de música
        function toggleTrackPreview(previewUrl, button) {
            const playIcon = button.querySelector('.play-icon');
            const pauseIcon = button.querySelector('.pause-icon');
            const previewText = button.querySelector('.preview-text');

            if (!previewUrl) {
                showNotification('Vista previa no disponible', 'info');
                return;
            }

            // Si hay audio reproduciéndose y es el mismo
            if (currentPreviewAudio && !currentPreviewAudio.paused && currentPreviewAudio.src === previewUrl) {
                currentPreviewAudio.pause();
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                previewText.textContent = 'Vista previa • 30s';
                return;
            }

            // Pausar cualquier audio anterior
            if (currentPreviewAudio) {
                currentPreviewAudio.pause();
                // Resetear otros botones si existen
                document.querySelectorAll('.play-icon').forEach(icon => icon.classList.remove('hidden'));
                document.querySelectorAll('.pause-icon').forEach(icon => icon.classList.add('hidden'));
                document.querySelectorAll('.preview-text').forEach(text => text.textContent = 'Vista previa • 30s');
            }

            // Crear nuevo audio
            currentPreviewAudio = new Audio(previewUrl);
            currentPreviewAudio.volume = 0.7;

            // Actualizar UI
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
            previewText.textContent = 'Reproduciendo...';

            // Reproducir
            currentPreviewAudio.play().catch(error => {
                console.error('Error al reproducir:', error);
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                previewText.textContent = 'Vista previa • 30s';
                showNotification('Error al reproducir vista previa', 'error');
            });

            // Evento cuando termina
            currentPreviewAudio.addEventListener('ended', () => {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                previewText.textContent = 'Vista previa • 30s';
            });
        }

        // Función para mostrar notificaciones (si existe)
        function showNotification(message, type = 'info') {
            if (window.showNotification) {
                window.showNotification(message, type);
            } else {
                console.log(`${type.toUpperCase()}: ${message}`);
            }
        }

        // Función para igualar alturas sin saltos visuales (solo en desktop)
        function matchHeights() {
            // Solo ejecutar en pantallas grandes (desktop)
            if (window.innerWidth >= 1024) {
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
            } else {
                // En móviles, remover cualquier altura fija
                const commentsContainer = document.getElementById('comments-container');
                if (commentsContainer) {
                    commentsContainer.style.height = 'auto';
                    commentsContainer.style.minHeight = '300px';
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
            let resizeTimeout;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(matchHeights, 200);
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