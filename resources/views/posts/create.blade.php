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
        @include('posts.post-type-tabs')

        <div class="md:flex md:items-start md:gap-8">
            <!-- Panel de carga/búsqueda -->
            @include('posts.post-content-panel')

            <!-- Formulario -->
            <div class="md:w-1/2 p-8 bg-white rounded-2xl shadow-2xl mt-10 md:mt-0">
                @include('posts.post-form')
            </div>
        </div>
    </div>
@endsection