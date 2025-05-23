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
        <img srcset="https://res.cloudinary.com/dj848z4er/image/upload/v1746637776/dp3pooq2vfhgqfgygkm3.png 2x"
            alt="LOGO2">
    </div>
@endsection
