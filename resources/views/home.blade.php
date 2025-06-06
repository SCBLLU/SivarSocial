@extends('layouts.app')

{{-- la section inyecta el contenido dentro del yield --}}
@section('titulo')
<div class="items-center flex flex-row justify-center">
    <p class="text-4xl font-bold mb-4">
        Pagina principal
    </p>
    <div class="m-4">
        <img srcset="https://res.cloudinary.com/dj848z4er/image/upload/v1748745537/alcb019nadktidzpwi4w.png 3x"
            alt="LOGO2">
    </div>
</div>
@endsection

@section('contenido')
    <x-listar-post :posts="$posts" />
        
@endsection
