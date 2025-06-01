@extends('layouts.app')

{{-- la section inyecta el contenido dentro del yield --}}
@section('titulo')
    Página Principal
@endsection

@section('contenido')
    <p class="text-6xl font-bold mb-4">
        Bienvenido a
    </p>
    <div class="flex mb-4">
        <img srcset="https://res.cloudinary.com/dj848z4er/image/upload/v1748745537/alcb019nadktidzpwi4w.png 1x"
            alt="LOGO2">
    </div>
@endsection
