@extends('layouts.app')

{{-- la section inyecta el contenido dentro del yield --}}
@section('titulo')
<div class="items-center flex flex-row justify-center">
    <p class="text-4xl font-bold mb-4">
        Pagina principal
    </p>
</div>
@endsection

@section('contenido')
<div class="container mx-auto flex flex-col md:flex-row gap-8 mt-8">
    <div class="w-full md:w-2/3">
        @component('components.listar-post', ['posts' => $posts])
        @endcomponent
    </div>
    <div class="w-full md:w-1/3">
        <div class="bg-white rounded-2xl shadow-lg p-4">
            <h2 class="text-xl font-bold text-purple-700 mb-4">Perfiles</h2>
            @component('components.listar-perfiles', ['users' => $users])
            @endcomponent
        </div>
    </div>
</div>
@endsection
