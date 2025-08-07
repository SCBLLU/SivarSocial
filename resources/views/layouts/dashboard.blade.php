@extends('layouts.app')

@push('scripts')
    <style>
        [x-cloak] {
            display: none !important;
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
        <h1 class="text-white text-2xl font-bold mx-auto">Perfil</h1>
    </div>
@endsection

@section('contenido')
    @if (session('success'))
        <div class="flex justify-center px-4">
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 max-w-md w-full">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Vista de perfil estilo tarjeta replicada --}}
    <div
        class="bg-white rounded-[2rem] shadow-md border border-gray-200 w-full max-w-2xl mx-auto p-4 sm:p-6 lg:p-12 flex flex-col lg:flex-row items-center justify-between gap-4 lg:gap-6 relative">

        {{-- Info de usuario --}}
        <div class="flex flex-col gap-1 text-center lg:text-left w-full lg:w-auto">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
            <p class="text-gray-600 font-semibold text-sm sm:text-base">{{ '@' . $user->username }}</p>

            {{-- Estadísticas --}}
            <div class="mt-2 space-y-1 text-xs sm:text-sm text-gray-800">
                <p><span class="font-semibold">{{ $user->followers->count() }}</span> Seguidores</p>
                <p><span class="font-semibold">{{ $user->following->count() }}</span> Siguiendo</p>
                <p><span class="font-semibold">{{ $posts->count() }}</span> Publicaciones</p>
            </div>

            {{-- Acciones dinámicas --}}
            <div class="mt-4">
                @auth
                    @if ($user->id === auth()->id())
                        <a href="{{ route('perfil.index') }}"
                            class="inline-block border-2 border-black text-black font-medium text-xs sm:text-sm px-6 sm:px-12 py-2 rounded-full hover:bg-gray-100 transition">
                            Editar perfil
                        </a>
                    @else
                        @if (!auth()->user()->isFollowing($user))
                            <form action="{{ route('users.follow', $user) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="bg-[#3B25DD] text-white px-4 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-medium hover:bg-[#120073] transition">
                                    SEGUIR
                                </button>
                            </form>
                        @else
                            <form action="{{ route('users.unfollow', $user) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-white border-2 border-black text-black px-4 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-medium hover:bg-gray-100 transition">
                                    NO SEGUIR
                                </button>
                            </form>
                        @endif
                    @endif
                @endauth
            </div>
        </div>

        {{-- Foto de perfil --}}
        <div class="relative flex-shrink-0 order-first lg:order-last">
            <div class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 rounded-full border-2 border-indigo-600 overflow-hidden">
                @if($user->imagen_url)
                    <img src="{{ $user->imagen_url }}" alt="Foto de perfil" class="w-full h-full object-cover">
                @else
                    <svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 
                                                                                                                                    1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                @endif
            </div>
        </div>

        {{-- Botón menú (3 puntos) --}}
        @auth
            @if ($user->id === auth()->id())
                <div class="absolute top-4 right-4 sm:top-6 sm:right-6 z-10" x-data="{ open: false }" x-cloak>
                    <button @click="open = !open"
                        class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center border border-gray-400 rounded-full hover:bg-gray-100 transition">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                    {{-- Menú desplegable --}}
                    <div x-show="open" x-cloak @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-44 sm:w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 py-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-3 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endauth
    </div>


    {{-- Sección de publicaciones (solo imagen redondeada) --}}
    <section class="container mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center mb-6">
            <h1 class="text-white text-xl sm:text-2xl font-bold mx-auto text-center">Publicaciones</h1>
        </div>
        @if ($posts->count())
            <div
                class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2 sm:gap-4 lg:gap-8 mb-8">
                @foreach ($posts as $post)
                    <div class="group">
                        <a href="{{ route('posts.show', ['post' => $post, 'user' => $user]) }}" class="block">
                            @if($post->tipo === 'musica')
                                {{-- Post de música: mostrar artwork del álbum con overlay musical --}}
                                <div class="relative w-full rounded-xl sm:rounded-2xl overflow-hidden shadow-md hover:shadow-xl"
                                    style="aspect-ratio:1/1;">
                                    @php
                                        $albumImage = $post->itunes_artwork_url;
                                        $trackName = $post->itunes_track_name;
                                        $artistName = $post->itunes_artist_name;
                                        // Mejorar la calidad de la imagen del álbum
                                        $highResAlbumImage = $albumImage ? str_replace('100x100', '600x600', $albumImage) : asset('img/img.jpg');
                                    @endphp

                                    <img src="{{ $highResAlbumImage }}"
                                        alt="{{ $trackName ?: 'Canción' }} - {{ $artistName ?: 'Artista' }}"
                                        class="object-cover w-full h-full">

                                    {{-- Overlay con indicador de música mejorado --}}
                                    <div
                                        class="music-overlay absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end justify-between p-3 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        {{-- Información de la canción --}}
                                        <div class="text-white flex-1 min-w-0">
                                            <p class="text-xs font-medium truncate">{{ $trackName ?: 'Canción' }}</p>
                                            <p class="text-xs opacity-75 truncate">{{ $artistName ?: 'Artista' }}</p>
                                        </div>                                       
                                    </div>

                                    {{-- Badge de tipo de contenido - visible siempre en móvil --}}
                                    <div
                                        class="absolute top-2 left-2 bg-black/70 backdrop-blur-sm rounded-full px-1.5 py-0.5 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex items-center space-x-0.5">
                                            <div class="w-1 h-1 bg-green-400 rounded-full animate-pulse"></div>
                                            <span class="text-white text-[10px] font-medium">Música</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Post de imagen normal con overlay mejorado --}}
                                <div class="relative w-full rounded-xl sm:rounded-2xl overflow-hidden shadow-md hover:shadow-xl"
                                    style="aspect-ratio:1/1;">
                                    <img src="{{ asset('uploads/' . $post->imagen) }}" alt="Imagen del post {{ $post->titulo }}"
                                        class="image-post object-cover w-full h-full transition-all duration-300">

                                    {{-- Overlay con información del post --}}
                                    <div
                                        class="image-overlay absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end justify-between p-3 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        {{-- Información del post --}}
                                        <div class="text-white flex-1 min-w-0">
                                            @if($post->titulo)
                                                <p class="text-xs font-medium truncate">{{ $post->titulo }}</p>
                                            @endif
                                            @if($post->descripcion)
                                                <p class="text-xs opacity-75 truncate {{ $post->titulo ? 'mt-1' : '' }}">
                                                    {{ $post->descripcion }}</p>
                                            @endif
                                        </div>                                       
                                    </div>

                                    {{-- Badge de tipo de contenido - visible siempre en móvil --}}
                                    <div
                                        class="badge absolute top-2 left-2 bg-black/70 backdrop-blur-sm rounded-full px-1.5 py-0.5 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex items-center space-x-0.5">
                                            <div class="w-1 h-1 bg-blue-400 rounded-full animate-pulse"></div>
                                            <span class="text-white text-[10px] font-medium">Imagen</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="flex justify-center w-full">
                {{ $posts->links() }}
            </div>

        @else
            <p class="text-gray-400 uppercase text-xs sm:text-sm text-center font-bold mt-10">No hay publicaciones aún</p>
        @endif
    </section>
@endsection