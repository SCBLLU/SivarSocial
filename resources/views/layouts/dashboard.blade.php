@extends('layouts.app')
<script src="//unpkg.com/alpinejs" defer></script>


@section('titulo')
    Tu Cuenta en SivarSocial
@endsection

@section('contenido')
    @if (session('success'))
        <div class="flex justify-center">
            <div 
                x-data="{ show: true }" 
                x-init="setTimeout(() => show = false, 3000)" 
                x-show="show"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 max-w-md w-full"
            >
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="flex justify-center">
        <div class="w-full md:w-8/12 lg:w-6/12 md:flex">
            <div class="md:w-8/12 lg:w-6/12 px-5">
                <img src="{{ asset('img/usuario.svg') }}" alt="imagen usuario">
            </div>
            <div class="md:w-8/12 lg:w-6/12 px-5">
                <p class="font-bold text-3xl">{{ $user->username }}</p>
            </div>

        </div>

    </div>
@endsection
