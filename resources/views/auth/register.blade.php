@extends('layouts.app')

@section('titulo')
    <div class="flex items-center justify-center gap-4">
        <div class="text-3xl font-bold text-white">
            Regístrate
        </div>
    </div>
@endsection

@section('contenido')
    <div class="w-full max-w-xl p-8 mx-auto bg-white shadow-md rounded-2xl">
        @include('auth.register-form')
    </div>
@endsection
