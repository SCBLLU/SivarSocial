@extends('layouts.app')

@section('titulo')
    {{ $post->titulo }}
@endsection

@section('contenido')
    <div class="container mx-auto flex flex-col md:flex-row gap-10 px-4 md:px-12 lg:px-24">
        <div class="md:w-1/2 flex flex-col items-center">
            <!-- Imagen del post -->
            <img src="{{ asset('uploads/' . $post->imagen) }}" alt="Imagen del post {{ $post->titulo }}"
                class="w-full max-w-md h-auto rounded-lg shadow-lg object-cover">

            <!-- Información del usuario y likes - SIEMPRE debajo de la imagen -->
            <div class="flex items-center justify-between mt-4 w-full max-w-md">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <span class="font-bold">{{ $post->user->username }}</span>
                    <span class="text-gray-300 text-sm sm:ml-2">{{ $post->created_at->diffForHumans() }}</span>
                </div>
                <span class="text-right font-medium">
                    @auth
                        @if ($post->checkLike(Auth::user()))
                            <form action="{{ route('posts.likes.destroy', $post) }}" method="POST" class="flex items-center gap-2 my-4">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>
                                    <span class="ml-2 font-bold text-white">{{ $post->likes->count() }} <span class="font-normal">Likes</span></span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('posts.likes.store', $post) }}" method="POST" class="flex items-center gap-2 my-4">
                                @csrf
                                <button type="submit" class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>
                                    <span class="ml-2 font-bold text-gray-700">{{ $post->likes->count() }} <span class="font-normal">Likes</span></span>
                                </button>
                            </form>
                        @endif
                    @endauth
                </span>
            </div>

            <!-- Descripción del post -->
            <div class="w-full max-w-md">
                <p class="text-gray-200 mt-3">{{ $post->descripcion }}</p>
            </div>

            @auth
                @if ($post->user_id === Auth::user()->id)
                    <form action=" {{ route('posts.destroy', $post) }}" method="POST" class="flex justify-start">
                        @csrf
                        @method('DELETE') {{-- metodo spoofing --}}
                        <input type="submit" value="Eliminar Post"
                            class="bg-red-500 hover:bg-red-600 transition-colors cursor-pointer font-bold p-2 text-white rounded-lg mt-5">
                    </form>
                @endif
            @endauth

        </div>

        <div class="md:w-1/2 flex flex-col justify-start p-5">
            <div class="bg-white p-5 rounded-lg shadow mb-5">
                @auth




                    @if (session('success'))
                        <div class="bg-green-500 text-white p-2 rounded-lg mb-4 text-center">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('mensaje'))
                        <div class="bg-green-500 text-white p-2 rounded-lg mb-4 text-center">
                            {{ session('mensaje') }}
                        </div>
                    @endif

                    <form action="{{ route('comentarios.store', ['post' => $post, 'user' => $user]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <div class="mb-5">
                            <label for="comentario" class="mb-2 block uppercase text-gray-500 font-bold">
                                Añade un comentario
                            </label>
                            <textarea id="comentario" name="comentario"
                                class="border-2 p-3 w-full rounded-lg text-black {{ $errors->has('comentario') ? 'border-red-500' : 'border-gray-400' }}"
                                placeholder="Agrega un comentario">{{ old('comentario') }}</textarea>
                            @error('comentario')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="submit" id="btn-submit" value="Comentar"
                            class="bg-blue-700 hover:bg-blue-800 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
                    </form>
                @endauth
            </div>
            <h2 class="text-4xl text-center font-black my-10">Comentarios</h2>
            <div class="mb-5 max-h-96 overflow-y-auto">
                @if ($post->comentarios->count())
                    @foreach ($post->comentarios as $comentario)
                        <div class="mb-4 p-5 bg-gray-100 border-b rounded-lg shadow">
                            <a href="{{ route('posts.index', $comentario->user) }}" class="font-bold text-gray-950">
                                {{ $comentario->user->username }}:
                            </a>
                            <p class="text-gray-700">{{ $comentario->comentario }}</p>
                            <p class="text-gray-500 text-sm mt-1">{{ $comentario->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-gray-500">No hay comentarios aún.</p>
                @endif

            </div>

        </div>
    </div>
@endsection
