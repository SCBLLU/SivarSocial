@extends('layouts.app')

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
@endpush

@section('titulo')
    Perfil: {{ $user->username }}
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

    <div class="flex justify-center">
        <div class="w-full md:w-8/12 lg:w-6/12 flex flex-col justify-center md:flex-row md:items-center">
            <div class="md:w-8/12 lg:w-6/12 px-5 flex justify-center">
                <img src="{{ $user->imagen_url }}" alt="imagen usuario" class="w-56 h-56 rounded-full object-cover border-4"
                    style="border-color: #e3f1ff;">
            </div>
            <div
                class="md:w-8/12 lg:w-6/12 px-5 flex flex-col items-center md:justify-center md:items-start py-10 md:py-10">

                <div class="flex items-center gap-2">
                    <p class="font-bold text-3xl">{{ $user->username }}</p>

                    @auth
                        @if ($user->id === Auth::user()->id)
                            <a href="{{ route('perfil.index') }}"
                                class="text-gray-300 hover:text-gray-400 p-3 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                        @endif
                    @endauth
                </div>
                <p class="text-gray-300 text-sm mb-3 font-bold mt-5">
                    0
                    <span class="font-normal">Seguidores</span>
                </p>
                <p class="text-gray-300 text-sm mb-3 font-bold">
                    0
                    <span class="font-normal">Siguiendo</span>
                </p>
                <p class="text-gray-300 text-sm mb-3 font-bold">
                    {{ $posts->count() }}
                    <span class="font-normal">Posts</span>
                </p>
            </div>

        </div>

    </div>

    <section class="container mx-auto mt-10">
        <h2 class="text-4xl text-center font-black my-10">Publicaciones</h2>
        {{-- para iterar un arreglo se utiliza un foreach --}}
        @if ($posts->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                {{-- $posts es el arreglo que contiene las publicaciones del usuario --}}
                {{-- cada post es un objeto que contiene los datos de la publicacion --}}
                @foreach ($posts as $post)
                    <div>
                        <a href="{{ route('posts.show', ['post' => $post, 'user' => $user]) }}">
                            <img src="{{ asset('uploads') . '/' . $post->imagen }}"
                                alt="Imagen del post {{ $post->titulo }}">
                        </a>

                    </div>
                @endforeach
            </div>

            <div class="my-10 text-blue-700 font-bold">
                {{ $posts->links() }}
            </div>
        @else
            <p class="text-gray-300 uppercase text-sm text-center font-bold mt-10">No hay publicaciones aún</p>
        @endif
    </section>
@endsection
