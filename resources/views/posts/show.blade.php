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
                                @if(isset($post->user) && $post->user->insignia === 'Colaborador')
                                    <span class="ml-1 flex items-center">
                                        <img src="https://res.cloudinary.com/dtmemrt1j/image/upload/v1754775975/Copia_de_social_20250809_154251_0002_tvbo7l.png"
                                            alt="Colaborador" width="16" height="16">
                                    </span>
                                @elseif(isset($post->user) && $post->user->insignia === 'Docente')
                                    <span class="ml-1 flex items-center">
                                        <img src="https://res.cloudinary.com/dtmemrt1j/image/upload/v1754775975/Copia_de_social_20250809_154250_0000_wtburi.png"
                                            alt="Docente" width="16" height="16">
                                    </span>
                                @elseif(isset($post->user) && $post->user->insignia === 'Comunidad')
                                    <span class="ml-1 flex items-center">
                                        <img src="https://res.cloudinary.com/dtmemrt1j/image/upload/v1754775975/Copia_de_social_20250809_154250_0001_b7euh4.png"
                                            alt="Comunidad" width="16" height="16">
                                    </span>
                                @else

                                @endif
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
                        class="bg-white rounded-2xl shadow-lg w-full lg:max-w-md flex flex-col items-center min-h-[500px]">
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
                                @if(isset($post->user) && $post->user->insignia === 'Colaborador')
                                    <span class="ml-1 flex items-center">
                                        <img src="https://res.cloudinary.com/dtmemrt1j/image/upload/v1754775975/Copia_de_social_20250809_154251_0002_tvbo7l.png"
                                            alt="Colaborador" width="16" height="16">
                                    </span>
                                @elseif(isset($post->user) && $post->user->insignia === 'Docente')
                                    <span class="ml-1 flex items-center">
                                        <img src="https://res.cloudinary.com/dtmemrt1j/image/upload/v1754775975/Copia_de_social_20250809_154250_0000_wtburi.png"
                                            alt="Docente" width="16" height="16">
                                    </span>
                                @elseif(isset($post->user) && $post->user->insignia === 'Comunidad')
                                    <span class="ml-1 flex items-center">
                                        <img src="https://res.cloudinary.com/dtmemrt1j/image/upload/v1754775975/Copia_de_social_20250809_154250_0001_b7euh4.png"
                                            alt="Comunidad" width="16" height="16">
                                    </span>
                                @else

                                @endif
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

                        <!-- Imagen del post - SIEMPRE CUADRADA -->
                        <div class="w-full bg-white rounded-b-none rounded-t-none aspect-square">
                            <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}"
                                class="w-full h-full object-cover rounded-none" width="1080" height="1080" loading="lazy">
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
                    class="bg-white rounded-2xl shadow-lg w-full lg:max-w-md flex flex-col h-auto lg:min-h-[500px] mt-4 lg:mt-0">
                    <!-- Componente Livewire para comentarios -->
                    <livewire:comments-section :post="$post" />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal simple para eliminar post usando JavaScript puro -->
    <div id="deletePostModal" class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center p-4 hidden"
        style="z-index: 1100;">
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

    <!-- Modal de Likes - Estilo Instagram hoja deslizante para móvil -->
    <div id="likesModal" class="fixed inset-0 hidden items-end sm:items-center justify-center"
        style="background-color: rgba(0, 0, 0, 0.6); z-index: 1100;">
        <!-- Backdrop para cerrar modal -->
        <div class="absolute inset-0 cursor-pointer" onclick="closeLikesModal()"></div>

        <!-- Contenedor del modal - Igual al panel de perfiles -->
        <div id="likesModalContent"
            class="fixed bottom-0 left-0 right-0 bg-white text-black rounded-t-2xl shadow-lg z-50 flex flex-col max-h-[80vh] w-full mx-auto sm:relative sm:w-96 sm:h-96 sm:rounded-xl overflow-hidden">
            <!-- Drag handle -->
            <div id="draghadlelike"
                class="p-4 border-b border-gray-200 text-center text-lg font-semibold cursor-grab touch-none sm:hidden">
                <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-2"></div>
                <div class="flex items-center justify-between px-2">
                    <span class="text-base font-bold text-gray-900">Me gusta</span>
                    <button onclick="closeLikesModal()" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Header solo en desktop -->
            <div class="hidden sm:block flex-none border-b border-gray-200 bg-white sm:rounded-t-xl sticky top-0 z-10">
                <div class="flex items-center justify-between px-4 py-3">
                    <h3 class="text-base font-semibold text-gray-900">Me gusta</h3>
                    <button onclick="closeLikesModal()" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Lista scrolleable -->
            <div class="p-4 space-y-3 overflow-y-auto flex-1 pb-0 bg-white" id="likesScrollContainer">
                <div id="likesUsersList"></div>
                {{-- <!-- Estados básicos -->
                <div id="likesLoader" class="hidden p-4 text-center">
                    <div class="inline-block w-6 h-6 border-2 border-gray-300 border-t-blue-500 rounded-full animate-spin">
                    </div>
                </div> --}}
                <div id="likesEmptyState" class="hidden p-8 text-center">
                    <p class="text-gray-500">Sin likes aún</p>
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
                        // Guardado de tiempo silencioso
                    }
                } catch (error) {
                    // Error handling silencioso
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
                showNotification('Error al cargar la vista previa', 'error');
                resetPlayer();
                clearLocalAudioState();
            });

            // Reproducir
            currentPreviewAudio.play().catch(error => {
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
            // Función para igualar alturas sin saltos visuales (solo en desktop)
            window.matchHeights = function () {
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
                    // En mobile, resetear altura para que sea automática
                    const commentsContainer = document.getElementById('comments-container');
                    if (commentsContainer) {
                        commentsContainer.style.height = 'auto';
                    }
                }
            };

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
                // Verificar si el usuario está escribiendo en un input, textarea o elemento editable
                const activeElement = document.activeElement;
                const isTyping = activeElement && (
                    activeElement.tagName === 'INPUT' ||
                    activeElement.tagName === 'TEXTAREA' ||
                    activeElement.contentEditable === 'true' ||
                    activeElement.isContentEditable
                );

                // Solo procesar controles de teclado si NO está escribiendo
                if (currentPreviewAudio && !isTyping) {
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

            // Ejecutar cuando la página carga completamente
            // Esperar a que todas las imágenes y contenido se carguen
            const images = document.querySelectorAll('img');
            let loadedImages = 0;
            const totalImages = images.length;

            function checkAllLoaded() {
                loadedImages++;
                if (loadedImages === totalImages) {
                    // Todas las imágenes han cargado, ahora igualar alturas
                    setTimeout(window.matchHeights, 50);
                }
            }

            if (totalImages === 0) {
                // No hay imágenes, ejecutar inmediatamente
                setTimeout(window.matchHeights, 50);
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
                resizeTimeout = setTimeout(window.matchHeights, 200);
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
            setTimeout(window.matchHeights, 100);
        });

        document.addEventListener('livewire:load', function () {
            setTimeout(window.matchHeights, 100);
        });

        // Función para mostrar notificaciones (si existe)
        function showNotification(message, type = 'info') {
            if (window.showNotification) {
                window.showNotification(message, type);
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

        // Ocultar menú móvil al abrir modal de likes
        if (window.innerWidth <= 640) {
            const headerMenu = document.getElementById('header');
            if (headerMenu) headerMenu.style.display = 'none';
        }

        // Mostrar menú móvil al cerrar modal de likes
        if (window.innerWidth <= 640) {
            const headerMenu = document.getElementById('header');
            if (headerMenu) headerMenu.style.display = '';
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
            document.body.classList.add('modal-open');
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
            document.body.classList.remove('modal-open');

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
                closeLikesModal(); // También cerrar modal de likes con ESC
            }
        });

        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("likesModal");
            const modalContent = document.getElementById("likesModalContent");
            const dragHandle = document.getElementById("draghadlelike");

            let startY = 0;
            let currentY = 0;
            let isDragging = false;

            dragHandle.addEventListener("touchstart", (e) => {
                startY = e.touches[0].clientY;
                isDragging = true;
                modalContent.style.transition = "none"; // sin animación mientras se arrastra
            });

            dragHandle.addEventListener("touchmove", (e) => {
                if (!isDragging) return;
                currentY = e.touches[0].clientY;
                let diff = currentY - startY;

                if (diff > 0) { // solo arrastrar hacia abajo
                    modalContent.style.transform = `translateY(${diff}px)`;
                }

            });

            dragHandle.addEventListener("touchend", () => {
                isDragging = false;
                modalContent.style.transition = "transform 0.3s ease";

                // Si se arrastró más de 100px, cerramos modal
                if (currentY - startY > 100) {
                    modalContent.style.transform = `translateY(100%)`;
                    setTimeout(() => {
                        modal.classList.add("hidden");
                        modalContent.style.transform = "";
                        document.documentElement.style.overflow = "";
                    }, 300);
                } else {
                    // Vuelve a posición original
                    modalContent.style.transform = "translateY(0)";
                }
            });
        });

        // ===== FUNCIONES PARA EL MODAL DE LIKES =====
        // Variables globales para el modal de likes mejorado
        let currentPostId = {{ $post->id }}; // ID del post actual
        let likesData = [];
        let filteredLikesData = [];
        let currentLikesPage = 1;
        let likesPerPage = 20;
        let isLoadingLikes = false;
        let hasMoreLikes = true;
        let searchTimeout = null;
        let touchStartY = 0;
        let isPullingToRefresh = false;

        // Función mejorada para abrir el modal de likes
        function openLikesModal(postId = null) {
            const modal = document.getElementById('likesModal');
            const content = document.getElementById('likesModalContent');
            const targetPostId = postId || currentPostId;

            if (!modal || !content) return;

            // Reset animaciones
            content.classList.remove('like-mobile-close', 'like-desktop-close');
            void content.offsetWidth; // Reinicia animación

            // Detectar mobile
            if (window.innerWidth < 648) {
                content.classList.add('like-mobile-open');
            } else {
                content.classList.add('like-desktop-open');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = "hidden";

            setupLikesModal();
            loadLikesData(targetPostId, true);
        }
        // Configurar funcionalidades del modal
        function setupLikesModal() {
            const scrollContainer = document.getElementById('likesScrollContainer');

            // Solo scroll básico para paginación - estilo Instagram
            if (scrollContainer) {
                scrollContainer.addEventListener('scroll', handleLikesScroll);
            }

            // Reset variables
            currentLikesPage = 1;
            hasMoreLikes = true;
            filteredLikesData = [];
        }

        // Función mejorada para cerrar el modal de likes
        function closeLikesModal() {
            const modal = document.getElementById('likesModal');
            const content = document.getElementById('likesModalContent');
            const searchInput = document.getElementById('likesSearchInput');

            if (!modal || !content) return;

            // Reset animaciones
            content.classList.remove('like-mobile-open', 'like-desktop-open');
            void content.offsetWidth;

            // Detectar mobile
            if (window.innerWidth < 648) {
                content.classList.add('like-mobile-close');
                content.style.transform = "";
            } else {
                content.classList.add('like-desktop-close');
            }

            content.addEventListener('animationend', function handler() {
                content.removeEventListener('animationend', handler);
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
                document.documentElement.style.overflow = "";

                likesData = [];
                filteredLikesData = [];
                currentLikesPage = 1;
                if (searchInput) searchInput.value = '';
            });
        }

        // Instagram no usa búsqueda - función simplificada para compatibilidad
        function filterLikes(query = '') {
            // Instagram no filtra, solo muestra todos los usuarios
            filteredLikesData = [...likesData];
            renderLikesList();
        }

        // Manejar scroll infinito básico - estilo Instagram
        function handleLikesScroll(event) {
            const container = event.target;
            const scrollTop = container.scrollTop;
            const scrollHeight = container.scrollHeight;
            const clientHeight = container.clientHeight;

            // Cargar más cuando llegue cerca del final
            if (scrollTop + clientHeight >= scrollHeight - 100 && hasMoreLikes && !isLoadingLikes) {
                loadMoreLikes();
            }
        }

        // Cargar más likes - estilo Instagram
        function loadMoreLikes() {
            if (!hasMoreLikes || isLoadingLikes) return;
            currentLikesPage++;
            loadLikesData(currentPostId, false);
        }

        // Función mejorada para cargar los datos de likes - estilo Instagram simple
        function loadLikesData(postId, reset = false) {
            if (isLoadingLikes) return;

            isLoadingLikes = true;

            if (reset) {
                showLikesLoader();
                currentLikesPage = 1;
                likesData = [];
                filteredLikesData = [];
            } else {
                showLoadMoreIndicator();
            }

            const url = `/posts/${postId}/likes?page=${currentLikesPage}&per_page=${likesPerPage}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const newLikes = data.likes || [];
                    const pagination = data.pagination || {};

                    if (reset) {
                        likesData = newLikes;
                    } else {
                        likesData = [...likesData, ...newLikes];
                    }

                    filteredLikesData = [...likesData];
                    hasMoreLikes = pagination.has_more || false;

                    updateLikesCount(data.total || likesData.length);
                    renderLikesList();

                    hideLikesLoader();
                    hideLoadMoreIndicator();
                })
                .catch(error => {
                    showLikesError();
                    hideLikesLoader();
                    hideLoadMoreIndicator();
                })
                .finally(() => {
                    isLoadingLikes = false;
                });
        }

        // Cargar más likes
        function loadMoreLikes() {
            currentLikesPage++;
            loadLikesData(currentPostId, false);
        }

        // Reintentar carga
        function retryLoadLikes() {
            const targetPostId = currentPostId;
            loadLikesData(targetPostId, true);
        }

        // Actualizar contador de likes
        function updateLikesCount(total) {
            const countElement = document.getElementById('likesCount');
            if (countElement && total > 0) {
                countElement.textContent = `(${total})`;
                countElement.classList.remove('hidden');
            }
        }

        // Mostrar/ocultar estados
        function showLikesLoader() {
            hideAllLikesStates();
            const loader = document.getElementById('likesLoader');
            if (loader) {
                loader.classList.remove('hidden');
                loader.style.display = 'flex';
            }
        }

        function hideLikesLoader() {
            const loader = document.getElementById('likesLoader');
            if (loader) {
                loader.classList.add('hidden');
                loader.style.display = 'none';
            }
        }

        function showLikesEmpty() {
            hideAllLikesStates();
            const emptyState = document.getElementById('likesEmptyState');
            if (emptyState) {
                emptyState.classList.remove('hidden');
                emptyState.style.display = 'flex';
            }
        }

        function showLikesError() {
            hideAllLikesStates();
            const errorState = document.getElementById('likesErrorState');
            if (errorState) {
                errorState.classList.remove('hidden');
                errorState.style.display = 'flex';
            }
        }

        function showLikesNoResults() {
            hideAllLikesStates();
            const noResultsState = document.getElementById('likesNoResultsState');
            if (noResultsState) {
                noResultsState.classList.remove('hidden');
                noResultsState.style.display = 'flex';
            }
        }

        function showLoadMoreIndicator() {
            const indicator = document.getElementById('loadMoreIndicator');
            if (indicator) {
                indicator.classList.remove('hidden');
                indicator.style.display = 'flex';
            }
        }

        function hideLoadMoreIndicator() {
            const indicator = document.getElementById('loadMoreIndicator');
            if (indicator) {
                indicator.classList.add('hidden');
                indicator.style.display = 'none';
            }
        }

        function hideAllLikesStates() {
            const states = ['likesLoader', 'likesEmptyState', 'likesErrorState', 'likesNoResultsState', 'loadMoreIndicator'];
            states.forEach(stateId => {
                const element = document.getElementById(stateId);
                if (element) {
                    element.classList.add('hidden');
                    element.style.display = 'none';
                }
            });
        }

        // Función mejorada para renderizar la lista de likes
        function renderLikesList() {
            const usersList = document.getElementById('likesUsersList');
            const modalContainer = document.getElementById('likesModalContent');
            if (!usersList || !modalContainer) return;

            hideAllLikesStates();

            // Usar datos filtrados
            const dataToRender = filteredLikesData;

            // Manejar diferentes estados
            if (likesData.length === 0) {
                showLikesEmpty();
                return;
            }

            if (dataToRender.length === 0 && filteredLikesData !== likesData) {
                showLikesNoResults();
                return;
            }

            // Aplicar clase para pocos likes en móvil según cantidad
            modalContainer.classList.remove('few-likes', 'medium-likes');

            if (dataToRender.length <= 3) {
                modalContainer.classList.add('few-likes');
            } else if (dataToRender.length <= 8) {
                modalContainer.classList.add('medium-likes');
            }

            // Render users with animations
            renderUsersWithAnimation(dataToRender, usersList);
        }

        // Renderizar usuarios estilo Instagram - SIN efectos ni animaciones
        function renderUsersWithAnimation(data, container) {
            let html = '';
            const currentUserId = {{ Auth::check() ? Auth::id() : 'null' }};

            data.forEach(like => {
                const user = like.user;
                const avatarUrl = user.imagen ? `/perfiles/${user.imagen}` : '/img/img.jpg';
                const isFollowing = like.isFollowing || false;

                html += `
                                                                <div class="py-3 flex items-center justify-between">
                                                                    <div class="flex items-center gap-3">
                                                                        <a href="/${user.username}">
                                                                            <img src="${avatarUrl}" 
                                                                                 alt="${user.username}"
                                                                                 class="w-12 h-12 rounded-full object-cover"
                                                                                 onerror="this.src='/img/img.jpg'">
                                                                        </a>
                                                                        <div>
                                                                            <a href="/${user.username}" class="block">
                                                                                <p class="font-semibold text-sm text-gray-900">${user.name || user.username}</p>
                                                                                <p class="text-sm text-gray-500">${user.username}</p>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    ${currentUserId && currentUserId !== user.id ? `
                                                                        <button onclick="toggleFollow(${user.id}, this)" 
                                                                                data-user-id="${user.id}"
                                                                                class="px-4 py-1.5 text-sm font-medium rounded-lg ${isFollowing
                            ? 'bg-gray-200 text-gray-700'
                            : 'bg-blue-500 text-white'
                        }">
                                                                            <span class="follow-text">${isFollowing ? 'Siguiendo' : 'Seguir'}</span>
                                                                        </button>
                                                                    ` : ''}
                                                                </div>`;
            });

            container.innerHTML = html;
        }        // Función para toggle seguir/no seguir usuario
        // Función mejorada para toggle seguir/no seguir usuario
        function toggleFollow(userId, button) {
            if (!button || button.disabled) return;

            const textElement = button.querySelector('.follow-text');
            const iconElement = button.querySelector('.follow-icon');

            if (!textElement) {
                // Fallback para botones sin estructura nueva
                const isCurrentlyFollowing = button.textContent.trim() === 'Siguiendo';
                const action = isCurrentlyFollowing ? 'unfollow' : 'follow';

                button.disabled = true;
                button.textContent = 'Procesando...';

                performFollowAction(userId, action, button, null, null, button.textContent);
                return;
            }

            const isCurrentlyFollowing = textElement.textContent.trim() === 'Siguiendo';
            const action = isCurrentlyFollowing ? 'unfollow' : 'follow';
            const originalText = textElement.textContent;

            // Deshabilitar botón y mostrar estado de carga
            button.disabled = true;

            // Animación de carga
            button.innerHTML = `
                                                                        <div class="flex items-center gap-2">
                                                                            <div class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                                                                            <span class="text-sm">Procesando...</span>
                                                                        </div>
                                                                    `;

            performFollowAction(userId, action, button, textElement, iconElement, originalText);
        }

        // Función auxiliar para realizar la acción de seguir
        function performFollowAction(userId, action, button, textElement, iconElement, originalText) {
            fetch(`/users/${userId}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Error en la respuesta del servidor');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const isNowFollowing = action === 'follow';

                        if (textElement) {
                            // Estructura nueva del botón
                            button.innerHTML = `
                                                                                        <span class="follow-text">${isNowFollowing ? 'Siguiendo' : 'Seguir'}</span>
                                                                                        <svg class="follow-icon w-4 h-4 ml-1 ${isNowFollowing ? '' : 'hidden'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                                        </svg>
                                                                                    `;

                            // Actualizar clases del botón
                            button.className = `follow-btn flex items-center justify-center px-4 py-1.5 text-sm font-medium rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 transform hover:scale-105 ${isNowFollowing
                                ? 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-400 border border-gray-300'
                                : 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500 shadow-sm hover:shadow-md'
                                }`;
                        } else {
                            // Estructura antigua del botón
                            button.textContent = isNowFollowing ? 'Siguiendo' : 'Seguir';
                            button.className = `follow-btn px-4 py-1.5 text-sm font-medium rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 ${isNowFollowing ? 'bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-gray-500' : 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500'}`;
                        }

                        // Actualizar datos en los arrays locales
                        const likeIndex = filteredLikesData.findIndex(like => like.user.id === userId);
                        if (likeIndex !== -1) {
                            filteredLikesData[likeIndex].isFollowing = isNowFollowing;
                        }

                        const originalLikeIndex = likesData.findIndex(like => like.user.id === userId);
                        if (originalLikeIndex !== -1) {
                            likesData[originalLikeIndex].isFollowing = isNowFollowing;
                        }

                        // Animación de éxito
                        button.style.transform = 'scale(1.1)';
                        setTimeout(() => {
                            button.style.transform = 'scale(1)';
                        }, 150);
                    } else {
                        throw new Error(data.message || 'Error desconocido');
                    }
                })
                .catch(error => {
                    if (textElement) {
                        // Restaurar botón con estructura nueva
                        const isFollowing = originalText === 'Siguiendo';
                        button.innerHTML = `
                                                                                    <span class="follow-text">${originalText}</span>
                                                                                    <svg class="follow-icon w-4 h-4 ml-1 ${isFollowing ? '' : 'hidden'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                                    </svg>
                                                                                `;

                        // Mostrar error temporal
                        const errorTextElement = button.querySelector('.follow-text');
                        if (errorTextElement) {
                            const originalButtonText = errorTextElement.textContent;
                            errorTextElement.textContent = 'Error';
                            button.style.backgroundColor = '#ef4444';

                            setTimeout(() => {
                                errorTextElement.textContent = originalButtonText;
                                button.style.backgroundColor = '';
                            }, 2000);
                        }
                    } else {
                        // Restaurar botón con estructura antigua
                        button.textContent = originalText;

                        // Mostrar error temporal
                        const originalBg = button.style.backgroundColor;
                        button.style.backgroundColor = '#ef4444';
                        button.textContent = 'Error';

                        setTimeout(() => {
                            button.style.backgroundColor = originalBg;
                            button.textContent = originalText;
                        }, 2000);
                    }
                })
                .finally(() => {
                    button.disabled = false;
                });
        }

        // Event listeners para el modal de likes
        document.addEventListener('DOMContentLoaded', function () {
            // Cerrar modal al hacer clic fuera
            const likesModal = document.getElementById('likesModal');
            if (likesModal) {
                likesModal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeLikesModal();
                    }
                });
            }
        });

        // Exponer función globalmente
        window.openLikesModal = openLikesModal;

        // Ajustes adicionales para mejorar la experiencia
        document.addEventListener('DOMContentLoaded', function () {
            // Mejorar experiencia de navegación por teclado
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('likesModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeLikesModal();
                    }
                }
            });

            // Optimización para dispositivos táctiles
            if ('ontouchstart' in window) {
                document.body.classList.add('touch-device');
            }

            // Observer para lazy loading de avatares si hay muchas imágenes
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.removeAttribute('data-src');
                                observer.unobserve(img);
                            }
                        }
                    });
                }, { rootMargin: '50px' });

                // La función se activará cuando se rendericen nuevas imágenes
                window.observeNewImages = function () {
                    const lazyImages = document.querySelectorAll('img[data-src]');
                    lazyImages.forEach(img => imageObserver.observe(img));
                };
            }

            // Drag to close para el modal de likes en móvil
            if (window.innerWidth <= 640) {
                let startY = null;
                let dragging = false;
                let modal = document.getElementById('likesModalContent');
                let modalWrapper = document.getElementById('likesModal');
                let dragHandle = document.querySelector('.drag-handle');
                let initialTop = 0;

                if (dragHandle && modal) {
                    dragHandle.addEventListener('touchstart', function (e) {
                        dragging = true;
                        startY = e.touches[0].clientY;
                        modal.style.transition = 'none';
                    });
                    document.addEventListener('touchmove', function (e) {
                        if (!dragging) return;
                        let deltaY = e.touches[0].clientY - startY;
                        if (deltaY > 0) {
                            modal.style.transform = `translateY(${deltaY}px)`;
                        }
                    });
                    document.addEventListener('touchend', function (e) {
                        if (!dragging) return;
                        let deltaY = e.changedTouches[0].clientY - startY;
                        modal.style.transition = 'transform 0.2s';
                        if (deltaY > 80) {
                            closeLikesModal();
                            setTimeout(() => { modal.style.transform = ''; }, 200);
                        } else {
                            modal.style.transform = '';
                        }
                        dragging = false;
                    });
                }
            }
        });
    </script>

    <!-- CSS adicional para mejoras específicas del modal -->
    <style>
        /* Asegurar que no haya conflictos con Livewire */
        [wire\:loading],
        [wire\:loading\.delay],
        [wire\:loading\.inline-block],
        [wire\:loading\.inline],
        [wire\:loading\.block],
        [wire\:loading\.flex],
        [wire\:loading\.table],
        [wire\:loading\.grid] {
            display: none;
        }

        [wire\:loading\.delay\.shortest],
        [wire\:loading\.delay\.shorter],
        [wire\:loading\.delay\.short],
        [wire\:loading\.delay\.long],
        [wire\:loading\.delay\.longer],
        [wire\:loading\.delay\.longest] {
            display: none;
        }

        /* ESTILOS MÍNIMOS - TODO LO DEMÁS CON TAILWIND CSS */

        /* Solo las animaciones esenciales */
        .animate-modal-enter {
            animation: modal-enter 0.3s ease-out;
        }

        @keyframes modal-enter {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Solo scroll suave para iOS - estilo Instagram */
        #likesScrollContainer {
            -webkit-overflow-scrolling: touch;
        }

        /* Estilos específicos para el modal de likes en móvil tipo hoja deslizante */
        @media (max-width: 640px) {
            #likesModalContent {
                width: 100% !important;
                max-width: 480px !important;
                min-width: 0 !important;
                border-radius: 22px 22px 0 0 !important;
                margin: 0 auto 0 auto !important;
                position: fixed !important;
                bottom: 0 !important;
                top: auto !important;
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.18) !important;
                height: auto !important;
                max-height: 90vh !important;
                transition: max-height 0.3s cubic-bezier(.4, 0, .2, 1), border-radius 0.2s;
                overflow: hidden !important;
                display: flex;
                flex-direction: column;
                padding: 0 !important;
            }
        }

        /* Asegurar que los modales estén por encima del menú móvil */
        #likesModal,
        #deletePostModal {
            z-index: 1100 !important;
        }

        /* Prevenir scroll del body cuando los modales están abiertos */
        *.modal-open {
            overflow: hidden !important;
        }
    </style>

    <!-- Modal de Likes Livewire -->
    <livewire:likes-modal />
@endsection