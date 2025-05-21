@extends('layouts.app')

@section('titulo')
    <div class="flex items-center justify-center gap-4">
        <div class="text-3xl font-bold text-white">
            Regístrate en
        </div>
        <div>
            <img srcset="https://res.cloudinary.com/dj848z4er/image/upload/v1746637776/dp3pooq2vfhgqfgygkm3.png 2x"
                alt="LOGO2" class="h-25">
        </div>
    </div>
@endsection

@section('contenido')
    <div class="w-full max-w-xl mx-auto px-4">
        @include('auth.register-form')
    </div>
@endsection
