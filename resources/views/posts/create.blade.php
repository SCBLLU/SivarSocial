@extends('layouts.app')

@section('titulo')
    Crea tu publicación
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endpush

@section('contenido')
    <div class="max-w-6xl mx-auto">
        <!-- Tabs para seleccionar tipo de post -->
        <div class="flex justify-center mb-8">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-2 flex gap-2">
                <button id="tab-imagen"
                    class="tab-button active px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 bg-gradient-to-br from-green-500 to-green-400 text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Imagen
                </button>
                <button id="tab-musica"
                    class="tab-button px-6 py-3 rounded-xl font-semibold text-sm flex items-center gap-2 bg-white/10 text-white hover:bg-white/20">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100 2 1 1 0 000-2z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Música
                </button>
            </div>
        </div>

        <div class="md:flex md:items-start md:gap-8">
            <!-- Panel de carga/búsqueda -->
            <div class="md:w-1/2 px-4">
                <!-- Contenido para post de imagen -->
                <div id="content-imagen" class="content-panel">
                    <div class="flex flex-col items-center justify-center">
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="dropzone"
                            class="dropzone text-zinc-950 border-dashed border-2 w-full h-96 rounded-2xl flex flex-col items-center justify-center bg-white/10 backdrop-blur-md border-white/20">
                            @csrf
                        </form>
                        <p class="text-gray-300 text-sm text-center mt-4">PNG, JPG, GIF, Máximo 2MB.</p>
                    </div>
                </div>

                <!-- Contenido para post de música -->
                <div id="content-musica" class="content-panel hidden">
                    <div class="bg-black border border-gray-600 rounded-xl p-8 mb-4">
                        <h3 class="text-white text-xl font-bold mb-4 text-center">Buscar en Spotify</h3>
                        <input type="text" id="spotify-search"
                            class="bg-gray-900 border-2 border-gray-600 text-white rounded-full px-6 py-3 w-full placeholder-gray-400 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-400/30 transition-all"
                            placeholder="Busca tu canción favorita..." autocomplete="off">
                        <div id="search-results" class="mt-4 max-h-96 overflow-y-auto space-y-2"></div>
                        <div id="selected-track" class="mt-4"></div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="md:w-1/2 p-8 bg-white rounded-2xl shadow-2xl mt-10 md:mt-0">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="main-form">
                    @csrf
                    <input type="hidden" name="tipo" id="post-tipo" value="imagen">

                    <div class="mb-6">
                        <label for="titulo" class="mb-2 block uppercase text-gray-500 font-bold text-sm">
                            Título
                        </label>
                        <input type="text" id="titulo" name="titulo"
                            class="border-2 p-4 w-full rounded-xl text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all {{ $errors->has('titulo') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="Título de la publicación" value="{{ old('titulo') }}">
                        @error('titulo')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="descripcion" class="mb-2 block uppercase text-gray-500 font-bold text-sm">
                            Descripción
                        </label>
                        <textarea id="descripcion" name="descripcion" rows="4"
                            class="border-2 p-4 w-full rounded-xl text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none {{ $errors->has('descripcion') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="Describe tu publicación...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campos ocultos para imagen -->
                    <div id="imagen-fields">
                        <input name="imagen" type="hidden" value="{{ old('imagen') }}">
                        @error('imagen')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campos ocultos para música -->
                    <div id="musica-fields" class="hidden">
                        <input name="spotify_track_id" type="hidden" value="{{ old('spotify_track_id') }}">
                        <input name="spotify_track_name" type="hidden" value="{{ old('spotify_track_name') }}">
                        <input name="spotify_artist_name" type="hidden" value="{{ old('spotify_artist_name') }}">
                        <input name="spotify_album_name" type="hidden" value="{{ old('spotify_album_name') }}">
                        <input name="spotify_album_image" type="hidden" value="{{ old('spotify_album_image') }}">
                        <input name="spotify_preview_url" type="hidden" value="{{ old('spotify_preview_url') }}">
                        <input name="spotify_external_url" type="hidden" value="{{ old('spotify_external_url') }}">
                        <input name="dominant_color" type="hidden" value="{{ old('dominant_color') }}">
                        @error('spotify_track_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" id="btn-submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all cursor-pointer uppercase font-bold w-full p-4 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
                        disabled>
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Crear publicación
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
