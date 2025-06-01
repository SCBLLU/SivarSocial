@extends('layouts.app')

@section('titulo')
    Crea tu publicación
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endpush

@section('contenido')
    <div class="md:flex md:items-center">
        <div class="md:w-1/2 px-10 flex flex-col items-center justify-center">
            <form action="{{ route('imagenes.store')}}" method="POST" enctype="multipart/form-data" id="dropzone" class="dropzone text-zinc-950 border-dashed border-2 w-full h-96 rounded flex flex-col items-center justify-center">
                @csrf                                                
            </form>
            <p class="text-gray-500 text-sm text-center mt-2">PNG, JPG, GIF,  Máximo 2MB.</p> 
        </div>
        <div class="md:w-1/2 p-10 bg-white rounded-lg shadow-xl mt-10 md:mt-0">
            <form action="">
                <div class="mb-5">
                    <label for="titulo" class="mb-2 block uppercase text-gray-500 font-bold">
                        Título
                    </label>
                    <input type="text" id="titulo" name="titulo" class="border-2 border-gray-300 p-3 w-full rounded-lg"
                        placeholder="Título de la publicación" value="{{ old('titulo') }}">
                    @error('titulo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="descripcion" class="mb-2 block uppercase text-gray-500 font-bold">
                        Descripción
                    </label>
                    <textarea id="descripcion" name="descripcion"
                        class="border-2 border-gray-300 p-3 w-full rounded-lg"
                        placeholder="Descripción de la publicación">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <input type="submit" value="Crear publicación"
                    class="bg-blue-700 hover:bg-blue-800 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
            </form>
        </div>
    </div>
@endsection
