@extends('layouts.app')

@push('scripts')
    <style src="//unpkg.com/alpinejs" defer>
        </script><style>[x-cloak] {
            display: none !important;
        }

        /* Asegurar que el modal esté completamente oculto inicialmente */
        .modal-backdrop[style*="display: none"] {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }

        /* Prevenir flash de contenido Alpine.js */
        [x-cloak] {
            display: none !important;
        }

        /* Asegurar visibilidad correcta de elementos con x-show */
        [x-show]:not([style*="display: none"]) {
            visibility: visible !important;
        }

        /* Ocultar elementos con x-show false por defecto */
        [x-show][style*="display: none"] {
            display: none !important;
            visibility: hidden !important;
        }
    </style>
@endpush

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

                <!-- Post -->
                @if($post->isMusicPost())
                    <!-- Post musical -->
                    <div id="post-container"
                        class="bg-white rounded-2xl shadow-lg w-full lg:max-w-md flex flex-col min-h-[500px] mt-4 lg:mt-0">
                        <!-- Header: perfil y username -->
                        <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
                            <a href="{{ route('posts.index', $post->user->username) }}" class="flex items-center group">
                                <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/img.jpg') }}"
                                    alt="Avatar de {{ $post->user->username }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-[#3B25DD] group-hover:border-[#120073] transition"
                                    onerror="this.src='{{ asset('img/img.jpg') }}'">
                                <span class="ml-3 font-bold text-black group-hover:underline text-sm sm:text-base">
                                    {{ $post->user->name ?? $post->user->username }}
                                </span>
                            </a>
                            <div class="flex items-center gap-2 ml-auto">
                                <span class="text-xs text-gray-500">{{ ucfirst($post->created_at->diffForHumans()) }}</span>

                                <!-- Menú de opciones (solo para el propietario) -->
                                @auth
                                    @if ($post->user_id === Auth::user()->id)
                                        <div class="relative" x-data="{ showMusicMenu: false }"
                                            @close-menus.window="showMusicMenu = false">
                                            <button @click="showMusicMenu = !showMusicMenu"
                                                class="p-1.5 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <div x-show="showMusicMenu" x-cloak @click.away="showMusicMenu = false" x-transition
                                                class="absolute right-0 mt-1 w-36 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-1">

                                                <!-- Opción Editar (preparada para futura implementación) -->
                                                <button onclick="alert('Función de editar en desarrollo')"
                                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Separador -->
                                                <hr class="my-1">

                                                <!-- Opción Eliminar -->
                                                <button onclick="openDeleteModal()"
                                                    class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        <!-- Contenido musical principal -->
                        <div class="w-full p-4 sm:p-6 bg-gradient-to-br from-[#121212] to-[#1a1a1a] relative overflow-hidden">
                            <!-- Fondo decorativo con el color dominante -->
                            <div class="absolute inset-0 opacity-10"
                                style="background: linear-gradient(135deg, #1DB954 0%, transparent 50%);">
                            </div>

                            <!-- Brand Attribution -->
                            <div
                                class="flex flex-col sm:flex-row items-center justify-between mb-4 sm:mb-6 relative z-10 gap-3">
                                @php
                                    $btnClasses = 'min-w-[100px] px-4 py-2 rounded-full text-xs font-medium flex items-center justify-center gap-1 transition-colors border';
                                @endphp

                                <!-- Icono de música minimalista y responsive -->
                                <div class="flex items-center justify-center">
                                    <i class="fa-solid fa-music text-white text-sm"></i>
                                </div>

                                <!-- Botones de reproducción -->
                                <div class="flex flex-wrap gap-2 justify-center">
                                    @if($post->hasAppleMusicLink())
                                        <a href="{{ $post->getAppleMusicUrl() }}" target="_blank"
                                            onclick="if(window.pauseAllAudio) window.pauseAllAudio();"
                                            class="{{ $btnClasses }} bg-white text-black hover:bg-gray-100 border-gray-300">
                                            <i class="fa-brands fa-apple text-sm"></i>
                                            <span>Apple</span>
                                        </a>
                                    @endif
                                    @if($post->hasSpotifyLink())
                                        <a href="{{ $post->getSpotifyUrl() }}" target="_blank"
                                            onclick="if(window.pauseAllAudio) window.pauseAllAudio();"
                                            class="{{ $btnClasses }} bg-[#1DB954] text-black hover:bg-[#1ed760] border-[#222326]">
                                            <i class="fa-brands fa-spotify text-sm text-black"></i>
                                            <span>Spotify</span>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Contenido principal de la canción -->
                            <div class="relative z-10 space-y-4 sm:space-y-6">
                                @php
                                    // Ahora solo usamos iTunes para las búsquedas principales
                                    $albumImage = $post->itunes_artwork_url;
                                    $trackName = $post->itunes_track_name;
                                    $artistName = $post->itunes_artist_name;
                                    $albumName = $post->itunes_collection_name;
                                    $previewUrl = $post->itunes_preview_url;
                                    $externalUrl = $post->itunes_track_view_url;
                                    $trackDuration = $post->itunes_track_time_millis ? round($post->itunes_track_time_millis / 1000) : 30;
                                @endphp

                                <!-- Reproductor de música personalizado -->
                                <div class="bg-black/30 backdrop-blur-sm rounded-xl p-4 sm:p-6 border border-white/10">
                                    <!-- Información de la canción -->
                                    <div class="flex items-center gap-4 mb-6">
                                        <!-- Imagen del álbum -->
                                        <div class="flex-shrink-0">
                                            <img src="{{ $albumImage ?: asset('img/img.jpg') }}"
                                                alt="{{ $albumName ?: 'Portada del álbum' }}"
                                                class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg object-cover shadow-xl">
                                        </div>

                                        <!-- Detalles de la canción -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-white font-bold text-lg sm:text-xl truncate mb-1">
                                                {{ $trackName ?: 'Canción desconocida' }}
                                            </h3>
                                            <p class="text-gray-300 text-sm sm:text-base truncate mb-2">
                                                {{ $artistName ?: 'Artista desconocido' }}
                                            </p>
                                            @if($albumName)
                                                <p class="text-gray-400 text-xs sm:text-sm truncate">
                                                    {{ $albumName }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    @if($previewUrl)
                                        <!-- Controles del reproductor -->
                                        <div class="space-y-4">
                                            <!-- Barra de progreso responsive -->
                                            <div class="space-y-2 sm:space-y-3">
                                                <div class="progress-container relative bg-white/20 hover:bg-white/30 rounded-full 
                                                                                                                                                            h-1.5 sm:h-2 cursor-pointer transition-all duration-200"
                                                    id="progress-container">
                                                    <div id="progress-bar"
                                                        class="absolute left-0 top-0 h-full bg-white rounded-full 
                                                                                                                                                                transition-all duration-100 ease-out"
                                                        style="width: 0%">
                                                    </div>
                                                    <!-- Punto de progreso -->
                                                    <div id="progress-thumb"
                                                        class="absolute w-3 h-3 sm:w-4 sm:h-4 bg-white rounded-full 
                                                                                                                                                                shadow-lg transform -translate-y-1/2 translate-x-1/2 
                                                                                                                                                                opacity-0 transition-all duration-200 ease-out
                                                                                                                                                                hover:scale-110 active:scale-95"
                                                        style="left: 0%; top: 50%"></div>
                                                </div>

                                                <!-- Tiempo y controles -->
                                                <div class="flex justify-between items-center text-xs sm:text-sm text-gray-300">
                                                    <span id="current-time" class="font-mono">0:00</span>
                                                    <span id="total-time" class="font-mono">0:30</span>
                                                </div>
                                            </div>

                                            <!-- Botón de reproducción principal usando componente -->
                                            <div class="flex items-center justify-center">
                                                <button id="main-play-btn" type="button"
                                                    class="play-button-main-track bg-white/20 hover:bg-white/30 text-white rounded-full p-3 sm:p-4 shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                                                    onclick="toggleTrackPreview('{{ $previewUrl }}', this)">
                                                    <!-- Icono play -->
                                                    <svg class="play-icon play-icon-main-track w-6 h-6 sm:w-8 sm:h-8 transition-all duration-200"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 5v14l11-7z" />
                                                    </svg>
                                                    <!-- Icono pause (oculto por defecto) -->
                                                    <svg class="pause-icon pause-icon-main-track w-6 h-6 sm:w-8 sm:h-8 hidden transition-all duration-200"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M10 9v6m4-6v6" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <!-- No hay preview disponible -->
                                        <div class="text-center py-6">
                                            <svg class="w-12 h-12 mx-auto text-gray-500 mb-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <p class="text-gray-400 text-sm">Vista previa no disponible</p>
                                            @if($externalUrl)
                                                <a href="{{ $externalUrl }}" target="_blank"
                                                    onclick="if(window.pauseAllAudio) window.pauseAllAudio();"
                                                    class="inline-block mt-3 text-white bg-white/20 hover:bg-white/30 px-4 py-2 rounded-full text-sm transition-colors">
                                                    Escuchar en {{ $isItunes ? 'iTunes' : 'Spotify' }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <!-- Detalles debajo del contenido musical -->
                        <div class="w-full px-4 py-3">
                            <!-- Layout unificado para móviles y PC: título y acciones en la misma línea -->
                            <div class="flex items-center justify-between mb-2">
                                @if($post->titulo)
                                    <!-- Si tiene título, mostrar solo el título en esta línea -->
                                    <span class="font-semibold text-black text-base sm:text-lg">{{ $post->titulo }}</span>
                                @elseif($post->descripcion)
                                    <!-- Si NO tiene título pero SÍ descripción, descripción va en línea del título -->
                                    <span class="text-gray-700 text-base sm:text-lg">{{ $post->descripcion }}</span>
                                @else
                                    <span></span> <!-- Espacio para alinear las acciones a la derecha -->
                                @endif
                                <div class="flex items-center gap-4">
                                    <livewire:comment-post :post="$post" color="gray" />
                                    <livewire:like-post :post="$post" color="red" />
                                </div>
                            </div>

                            <!-- Descripción abajo solo si tiene título Y descripción -->
                            @if($post->titulo && $post->descripcion)
                                <div class="mb-3">
                                    <p class="text-gray-700 text-xs sm:text-sm">{{ $post->descripcion }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Formulario oculto para eliminar posts de música -->
                        @auth
                            @if ($post->user_id === Auth::user()->id)
                                <form id="deleteMusicForm" action="{{ route('posts.destroy', $post) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        @endauth
                    </div>

                @else
                    <!-- Post de imagen -->
                    <div id="post-container"
                        class="bg-white rounded-2xl shadow-lg w-full lg:max-w-md flex flex-col items-center">
                        <!-- Header: perfil y username -->
                        <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
                            <a href="{{ route('posts.index', $post->user->username) }}" class="flex items-center group">
                                <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/img.jpg') }}"
                                    alt="Avatar de {{ $post->user->username }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-[#3B25DD] group-hover:border-[#120073] transition"
                                    onerror="this.src='{{ asset('img/img.jpg') }}'">
                                <span class="ml-3 font-bold text-black group-hover:underline text-sm sm:text-base">
                                    {{ $post->user->name ?? $post->user->username }}
                                </span>
                            </a>
                            <div class="flex items-center gap-2 ml-auto">
                                <span class="text-xs text-gray-500">{{ ucfirst($post->created_at->diffForHumans()) }}</span>

                                <!-- Menú de opciones (solo para el propietario) -->
                                @auth
                                    @if ($post->user_id === Auth::user()->id)
                                        <div class="relative" x-data="{ showImageMenu: false }"
                                            @close-menus.window="showImageMenu = false">
                                            <button @click="showImageMenu = !showImageMenu"
                                                class="p-1.5 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <div x-show="showImageMenu" x-cloak @click.away="showImageMenu = false" x-transition
                                                class="absolute right-0 mt-1 w-36 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-1">

                                                <!-- Opción Editar (preparada para futura implementación) -->
                                                <button onclick="alert('Función de editar en desarrollo')"
                                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                    Editar
                                                </button>

                                                <!-- Separador -->
                                                <hr class="my-1">

                                                <!-- Opción Eliminar -->
                                                <button onclick="openDeleteModal()"
                                                    class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        <!-- Imagen del post -->
                        <div class="w-full flex justify-center bg-black rounded-b-none rounded-t-none">
                            <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}"
                                class="object-cover w-full max-h-96 sm:max-h-[500px] lg:max-h-96 aspect-square rounded-none">
                        </div>

                        <!-- Detalles debajo de la imagen -->
                        <div class="w-full px-4 py-3">
                            <!-- Layout unificado para móviles y PC: título y acciones en la misma línea -->
                            <div class="flex items-center justify-between mb-2">
                                @if($post->titulo)
                                    <span class="font-semibold text-black text-base sm:text-lg">{{ $post->titulo }}</span>
                                @else
                                    <span></span> <!-- Espacio para alinear las acciones a la derecha -->
                                @endif
                                <div class="flex items-center gap-4">
                                    <livewire:comment-post :post="$post" color="gray" />
                                    <livewire:like-post :post="$post" color="red" />
                                </div>
                            </div>

                            <!-- Descripción (solo si existe) -->
                            @if($post->descripcion)
                                <div class="mb-3">
                                    <p class="text-gray-700 text-xs sm:text-sm">{{ $post->descripcion }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Formulario oculto para eliminar posts de imagen -->
                        @auth
                            @if ($post->user_id === Auth::user()->id)
                                <form id="deleteImageForm" action="{{ route('posts.destroy', $post) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        @endauth
                    </div>
                @endif

                <!-- Comentarios -->
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
                                            onerror="this.src='{{ asset('img/img.jpg') }}'">
                                    @else
                                        <img src="{{ asset('img/img.jpg') }}" alt="Tu avatar por defecto"
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

    <!-- Modal simple para eliminar post usando JavaScript puro -->
    <div id="deletePostModal" class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center z-50 p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full mx-4 transform transition-all scale-95 opacity-0"
            id="deleteModalContent">
            <div class="p-6">
                <!-- Icono de advertencia -->
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div>

                <!-- Título y mensaje -->
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar publicación?</h3>
                    <p class="text-gray-600 text-sm">
                        Esta acción eliminará permanentemente la publicación
                        @if($post->tipo === 'imagen')
                            y la imagen asociada.
                        @endif
                        <span class="font-semibold text-red-600">No se puede deshacer.</span>
                    </p>
                </div>

                <!-- Botones -->
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition duration-200">
                        Cancelar
                    </button>
                    <form method="POST" action="{{ route('posts.destroy', $post) }}" class="flex-1" id="deletePostForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition duration-200">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Audio global para preview
        let currentPreviewAudio = null;
        let progressInterval = null;
        let isDragging = false;

        // Función para alternar preview de música
        function toggleTrackPreview(previewUrl, button) {
            const playIcon = button.querySelector('.play-icon');
            const pauseIcon = button.querySelector('.pause-icon');
            const progressBar = document.getElementById('progress-bar');
            const progressThumb = document.getElementById('progress-thumb');
            const currentTimeDisplay = document.getElementById('current-time');

            if (!previewUrl) {
                showNotification('Vista previa no disponible', 'info');
                return;
            }

            // Si hay audio reproduciéndose y es el mismo
            if (currentPreviewAudio && !currentPreviewAudio.paused && currentPreviewAudio.src === previewUrl) {
                pauseAudio();
                return;
            }

            // Pausar cualquier audio anterior
            if (currentPreviewAudio) {
                pauseAudio();
            }

            // Verificar si hay un estado guardado para esta canción
            const savedState = sessionStorage.getItem('sivarsocial_show_audio_state');
            let savedTime = 0;

            if (savedState) {
                try {
                    const audioState = JSON.parse(savedState);
                    const currentPostId = window.location.pathname.split('/').pop();

                    // Si es la misma canción y el mismo post, usar el tiempo guardado
                    if (audioState.previewUrl === previewUrl && audioState.postId === currentPostId) {
                        savedTime = audioState.currentTime || 0;
                        console.log('Restaurando desde el tiempo:', savedTime);
                    }
                } catch (error) {
                    console.error('Error al leer estado guardado:', error);
                }
            }

            // Crear nuevo audio
            currentPreviewAudio = new Audio(previewUrl);
            currentPreviewAudio.volume = 0.7;
            currentPreviewAudio.crossOrigin = "anonymous";

            // Actualizar UI inmediatamente
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');

            if (progressThumb) {
                progressThumb.style.opacity = '1';
            }

            // Eventos del audio
            currentPreviewAudio.addEventListener('loadedmetadata', () => {
                // Si hay tiempo guardado, establecerlo
                if (savedTime > 0 && savedTime < currentPreviewAudio.duration) {
                    currentPreviewAudio.currentTime = savedTime;
                }
            });

            currentPreviewAudio.addEventListener('timeupdate', () => {
                if (!isDragging && currentPreviewAudio && progressBar) {
                    const progress = (currentPreviewAudio.currentTime / currentPreviewAudio.duration) * 100;
                    progressBar.style.width = progress + '%';

                    if (progressThumb) {
                        progressThumb.style.left = progress + '%';
                    }

                    if (currentTimeDisplay) {
                        const currentTime = formatTime(currentPreviewAudio.currentTime);
                        currentTimeDisplay.textContent = currentTime;
                    }

                    // Guardar estado mientras se reproduce
                    saveLocalAudioState();
                }
            });

            currentPreviewAudio.addEventListener('ended', () => {
                resetPlayer();
                clearLocalAudioState();
            });

            currentPreviewAudio.addEventListener('error', (e) => {
                console.error('Error al cargar audio:', e);
                showNotification('Error al cargar la vista previa', 'error');
                resetPlayer();
                clearLocalAudioState();
            });

            // Reproducir
            currentPreviewAudio.play().catch(error => {
                console.error('Error al reproducir:', error);
                resetPlayer();
                showNotification('Error al reproducir vista previa', 'error');
                clearLocalAudioState();
            }).then(() => {
                // Guardar estado inicial cuando empiece a reproducir
                if (currentPreviewAudio && !currentPreviewAudio.paused) {
                    const audioState = {
                        previewUrl: currentPreviewAudio.src,
                        currentTime: currentPreviewAudio.currentTime,
                        isPlaying: true,
                        timestamp: Date.now(),
                        page: 'show',
                        postId: window.location.pathname.split('/').pop(),
                        userInitiated: true
                    };
                    sessionStorage.setItem('sivarsocial_show_audio_state', JSON.stringify(audioState));
                }
            });
        }

        // Función para pausar el audio local
        function pauseAudio() {
            if (currentPreviewAudio) {
                saveLocalAudioState(); // Guardar estado antes de pausar
                currentPreviewAudio.pause();

                const playIcon = document.querySelector('.play-button-main-track .play-icon-main-track');
                const pauseIcon = document.querySelector('.play-button-main-track .pause-icon-main-track');

                if (playIcon && pauseIcon) {
                    playIcon.classList.remove('hidden');
                    pauseIcon.classList.add('hidden');
                }
            }
        }

        // Función para guardar el estado del audio local
        function saveLocalAudioState() {
            if (currentPreviewAudio && !currentPreviewAudio.paused && currentPreviewAudio.src) {
                const audioState = {
                    previewUrl: currentPreviewAudio.src,
                    currentTime: currentPreviewAudio.currentTime,
                    isPlaying: !currentPreviewAudio.paused,
                    timestamp: Date.now(),
                    page: 'show', // Identificar que es de la página show
                    postId: window.location.pathname.split('/').pop(), // ID del post actual
                    userInitiated: true // Marcar que fue iniciado por el usuario
                };
                sessionStorage.setItem('sivarsocial_show_audio_state', JSON.stringify(audioState));
            }
        }

        // Función para verificar si es una navegación desde listar-post
        function isNavigatingFromList() {
            const referrer = document.referrer;
            return referrer && !referrer.includes('/posts/') && referrer.includes(window.location.origin);
        }

        // Función mejorada para restaurar solo cuando sea apropiado
        function restoreLocalAudioStateIfAppropriate() {
            // No restaurar si viene directamente desde la lista de posts
            if (isNavigatingFromList()) {
                sessionStorage.removeItem('sivarsocial_show_audio_state');
                return;
            }

            // Solo restaurar en casos específicos
            restoreLocalAudioState();
        }

        // Función para restaurar el estado del audio local
        function restoreLocalAudioState() {
            const savedState = sessionStorage.getItem('sivarsocial_show_audio_state');
            if (savedState) {
                try {
                    const audioState = JSON.parse(savedState);
                    const currentPostId = window.location.pathname.split('/').pop();

                    // Solo restaurar si es el mismo post, la sesión es reciente (menos de 5 minutos)
                    // Y el usuario venía de la misma página (no de navegación externa)
                    const timeDiff = Date.now() - audioState.timestamp;
                    const wasRecentlyPlaying = timeDiff < 300000; // 5 minutos
                    const isSamePost = audioState.postId === currentPostId;
                    const wasPlayingOnThisPage = audioState.isPlaying && audioState.page === 'show';

                    // Verificar si viene de navegación interna (no es la primera visita a la página)
                    const hasNavigationHistory = window.performance.navigation.type === 1 || // reload
                        document.referrer.includes(window.location.origin); // viene del mismo sitio

                    if (wasRecentlyPlaying && isSamePost && wasPlayingOnThisPage && hasNavigationHistory) {
                        // Buscar el botón de reproducir principal
                        const mainPlayButton = document.getElementById('main-play-btn');
                        if (mainPlayButton && audioState.previewUrl) {
                            // Simular click en el botón después de un breve delay
                            setTimeout(() => {
                                mainPlayButton.click();
                                // Saltar al tiempo guardado después de que el audio se cargue
                                if (currentPreviewAudio) {
                                    const checkAudioReady = () => {
                                        if (currentPreviewAudio.readyState >= 2) {
                                            currentPreviewAudio.currentTime = audioState.currentTime;
                                        } else {
                                            setTimeout(checkAudioReady, 100);
                                        }
                                    };
                                    checkAudioReady();
                                }
                            }, 300);
                        }
                    }
                    // Limpiar el estado después de intentar restaurar
                    sessionStorage.removeItem('sivarsocial_show_audio_state');
                } catch (error) {
                    console.error('Error al restaurar estado del audio local:', error);
                    sessionStorage.removeItem('sivarsocial_show_audio_state');
                }
            }
        }

        // Función para limpiar el estado del audio local
        function clearLocalAudioState() {
            sessionStorage.removeItem('sivarsocial_show_audio_state');
        }

        // Exponer las funciones pauseAudio y restauración globalmente para que app.js pueda accederlas
        window.pauseAudio = pauseAudio;
        window.restoreLocalAudioState = restoreLocalAudioStateIfAppropriate;
        window.saveLocalAudioState = saveLocalAudioState;

        // Función para resetear el reproductor
        function resetPlayer() {
            const playIcon = document.querySelector('.play-button-main-track .play-icon-main-track');
            const pauseIcon = document.querySelector('.play-button-main-track .pause-icon-main-track');
            const progressBar = document.getElementById('progress-bar');
            const progressThumb = document.getElementById('progress-thumb');
            const currentTimeDisplay = document.getElementById('current-time');

            if (playIcon && pauseIcon) {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            }

            if (progressBar) {
                progressBar.style.width = '0%';
            }

            if (progressThumb) {
                progressThumb.style.left = '0%';
                progressThumb.style.opacity = '0';
            }

            if (currentTimeDisplay) {
                currentTimeDisplay.textContent = '0:00';
            }
        }

        // Función para formatear tiempo
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }

        // Inicialización cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function () {
            // Control de progreso
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');
            const progressThumb = document.getElementById('progress-thumb');

            if (progressContainer && progressBar && progressThumb) {
                // Click en la barra de progreso
                progressContainer.addEventListener('click', function (e) {
                    if (currentPreviewAudio && currentPreviewAudio.duration) {
                        const rect = progressContainer.getBoundingClientRect();
                        const clickX = e.clientX - rect.left;
                        const percentage = (clickX / rect.width) * 100;
                        const newTime = (percentage / 100) * currentPreviewAudio.duration;

                        currentPreviewAudio.currentTime = newTime;
                        progressBar.style.width = percentage + '%';
                        progressThumb.style.left = percentage + '%';
                    }
                });

                // Arrastrar en la barra de progreso
                progressThumb.addEventListener('mousedown', function (e) {
                    isDragging = true;
                    e.preventDefault();
                });

                document.addEventListener('mousemove', function (e) {
                    if (isDragging && currentPreviewAudio && currentPreviewAudio.duration) {
                        const rect = progressContainer.getBoundingClientRect();
                        const clickX = Math.max(0, Math.min(e.clientX - rect.left, rect.width));
                        const percentage = (clickX / rect.width) * 100;

                        progressBar.style.width = percentage + '%';
                        progressThumb.style.left = percentage + '%';
                    }
                });

                document.addEventListener('mouseup', function (e) {
                    if (isDragging && currentPreviewAudio && currentPreviewAudio.duration) {
                        const rect = progressContainer.getBoundingClientRect();
                        const clickX = Math.max(0, Math.min(e.clientX - rect.left, rect.width));
                        const percentage = (clickX / rect.width) * 100;
                        const newTime = (percentage / 100) * currentPreviewAudio.duration;

                        currentPreviewAudio.currentTime = newTime;
                    }
                    isDragging = false;
                });

                // Mostrar el thumb al hacer hover en el contenedor
                progressContainer.addEventListener('mouseenter', function () {
                    if (currentPreviewAudio && progressThumb) {
                        progressThumb.style.opacity = '1';
                    }
                });

                progressContainer.addEventListener('mouseleave', function () {
                    if (!isDragging && currentPreviewAudio && !currentPreviewAudio.paused && progressThumb) {
                        // Mantener visible si está reproduciéndose
                    } else if (progressThumb) {
                        progressThumb.style.opacity = '0';
                    }
                });
            }

            // Controles de teclado
            document.addEventListener('keydown', function (e) {
                if (currentPreviewAudio) {
                    switch (e.code) {
                        case 'Space':
                            e.preventDefault();
                            if (currentPreviewAudio.paused) {
                                document.getElementById('main-play-btn').click();
                            } else {
                                pauseAudio();
                            }
                            break;
                        case 'ArrowLeft':
                            e.preventDefault();
                            currentPreviewAudio.currentTime = Math.max(0, currentPreviewAudio.currentTime - 5);
                            break;
                        case 'ArrowRight':
                            e.preventDefault();
                            currentPreviewAudio.currentTime = Math.min(currentPreviewAudio.duration, currentPreviewAudio.currentTime + 5);
                            break;
                    }
                }
            });

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

            // Auto-ocultar mensajes de éxito después de 4 segundos
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

            // Guardar estado del audio local periódicamente mientras se reproduce
            setInterval(() => {
                if (currentPreviewAudio && !currentPreviewAudio.paused) {
                    saveLocalAudioState();
                }
            }, 2000); // Cada 2 segundos
        });

        // Para componentes Livewire (cuando se actualice el contenido)
        document.addEventListener('livewire:navigated', function () {
            setTimeout(matchHeights, 100);
        });

        document.addEventListener('livewire:load', function () {
            setTimeout(matchHeights, 100);
        });

        // Función para mostrar notificaciones (si existe)
        function showNotification(message, type = 'info') {
            if (window.showNotification) {
                window.showNotification(message, type);
            } else {
                console.log(`${type.toUpperCase()}: ${message}`);
            }
        }

        // Función para confirmar eliminación de post de música
        function confirmDeleteMusic() {
            if (confirm('¿Estás seguro de que quieres eliminar esta publicación musical? Esta acción no se puede deshacer.')) {
                const deleteForm = document.getElementById('deleteMusicForm') || document.getElementById('deleteImageForm');
                if (deleteForm) {
                    deleteForm.submit();
                }
            }
        }

        // Función para confirmar eliminación de post de imagen
        function confirmDeleteImage() {
            if (confirm('¿Estás seguro de que quieres eliminar esta publicación? Esta acción no se puede deshacer.')) {
                document.getElementById('deleteImageForm').submit();
            }
        }

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
            if (document.hidden) {
                // Guardar estado antes de pausar
                if (currentPreviewAudio && !currentPreviewAudio.paused) {
                    saveLocalAudioState();
                }
                // Pausar audio local
                pauseAudio();
                // También pausar audio global si existe
                if (window.pauseAllAudio) {
                    window.pauseAllAudio();
                }
            }
        });

        // Restaurar audio al cargar la página
        document.addEventListener('DOMContentLoaded', function () {
            // No restaurar automáticamente - el usuario debe presionar play
            setTimeout(() => {
                if (window.restoreAudioState) {
                    window.restoreAudioState();
                }
            }, 500);
        });

        // También restaurar cuando Livewire termine de navegar
        document.addEventListener('livewire:navigated', function () {
            // No restaurar automáticamente el audio local - el usuario debe presionar play
            setTimeout(() => {
                if (window.restoreAudioState) {
                    window.restoreAudioState();
                }
            }, 500);
        });

        // JavaScript puro para el modal de eliminar post
        function openDeleteModal() {
            // Cerrar los menús de Alpine.js
            document.dispatchEvent(new CustomEvent('close-menus'));

            const modal = document.getElementById('deletePostModal');
            const content = document.getElementById('deleteModalContent');

            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.remove('hidden');
                content.style.transform = 'scale(1)';
                content.style.opacity = '1';
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deletePostModal');
            const content = document.getElementById('deleteModalContent');

            content.style.transform = 'scale(0.95)';
            content.style.opacity = '0';

            setTimeout(() => {
                modal.style.display = 'none';
                modal.classList.add('hidden');
            }, 200);
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('deletePostModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@endsection