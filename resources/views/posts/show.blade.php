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
                <span class="text-right font-medium">0 Likes</span>
            </div>
            
            <!-- Descripción del post -->
            <div class="w-full max-w-md">
                <p class="text-gray-200 mt-3">{{ $post->descripcion }}</p>
            </div>
        </div>
        
        <div class="md:w-1/2 flex flex-col justify-start p-5">
            <div class="bg-white p-5 rounded-lg shadow mb-5">
                @auth
                    
                
                <p class="text-xl font-extrabold text-center mb-4 text-blue-700">Agrega un comentario</p>
                <form action="">
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
                        class="bg-blue-700 hover:bg-blue-800 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg"
                        disabled>
                </form>
                @endauth
            </div>
            <h2 class="text-4xl text-center font-black my-10">Comentarios</h2>
            <div class="">
                {{-- <p><strong>{{ $comment->user->username }}:</strong> {{ $comment->content }}</p> --}}
                {{-- <p class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</p --}}
            </div>
            <p class="text-center text-gray-500">No hay comentarios aún.</p>
        </div>
    </div>
@endsection