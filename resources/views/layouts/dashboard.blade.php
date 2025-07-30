@extends('layouts.app')

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
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
        <div class="flex justify-center">
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 max-w-md w-full">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Vista de perfil estilo tarjeta replicada --}}
    <div
        class="bg-white rounded-[2rem] shadow-md border border-gray-200 w-full max-w-2xl mx-auto p-12 flex items-center justify-between gap-6 relative">

        {{-- Info de usuario --}}
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
            <p class="text-gray-600 font-semibold">{{ '@' . $user->username }}</p>

            {{-- Estadísticas --}}
            <div class="mt-2 space-y-1 text-sm text-gray-800">
                <p><span class="font-semibold">{{ $user->followers->count() }}</span> Seguidores</p>
                <p><span class="font-semibold">{{ $user->following->count() }}</span> Siguiendo</p>
                <p><span class="font-semibold">{{ $posts->count() }}</span> Publicaciones</p>
            </div>

            {{-- Acciones dinámicas --}}
            <div class="mt-4">
                @auth
                    @if ($user->id === auth()->id())
                        <a href="{{ route('perfil.index') }}"
                            class="inline-block border-2 border-black text-black font-medium text-sm px-12 py-2 rounded-full hover:bg-gray-100 transition">
                            Editar perfil
                        </a>
                    @else
                        @if (!auth()->user()->isFollowing($user))
                            <form action="{{ route('users.follow', $user) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="bg-[#3B25DD] text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-[#120073] transition">
                                    SEGUIR
                                </button>
                            </form>
                        @else
                            <form action="{{ route('users.unfollow', $user) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-white border-2 border-black text-black px-6 py-2 rounded-full text-sm font-medium hover:bg-gray-100 transition">
                                    NO SEGUIR
                                </button>
                            </form>
                        @endif
                    @endif
                @endauth
            </div>
        </div>

        {{-- Foto de perfil --}}
        <div class="relative">
            <div class="w-48 h-48 rounded-full border-2 border-indigo-600 overflow-hidden">
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
                <div class="absolute top-6 right-6 z-10" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-9 h-9 flex items-center justify-center border border-gray-400 rounded-full hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                    {{-- Menú desplegable --}}
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 py-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <section class="container mx-auto mt-10">
        <div class="flex justify-center mb-6">
            <h1 class="text-white text-2xl font-bold mx-auto text-center">Publicaciones</h1>
        </div>
        @if ($posts->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($posts as $post)
                    <div>
                        <a href="{{ route('posts.show', ['post' => $post, 'user' => $user]) }}">
                            <img src="{{ asset('uploads/' . $post->imagen) }}" alt="Imagen del post {{ $post->titulo }}"
                                class="object-cover w-full rounded-2xl" style="aspect-ratio:1/1; max-width:100%; max-height:100%;">
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="my-10 flex justify-center">
                {{ $posts->links() }}
            </div>
        @else
            <p class="text-gray-400 uppercase text-sm text-center font-bold mt-10">No hay publicaciones aún</p>
        @endif
    </section>
@endsection