@extends('layouts.app')

@section('titulo')
    <div class="flex items-center justify-center gap-4">
        <div class="text-3xl font-bold text-white">
            Regístrate
        </div>
    </div>
@endsection

@section('contenido')
    <div class="w-full max-w-xl mx-auto px-4">
        @include('auth.register-form')
    </div>
@endsection
