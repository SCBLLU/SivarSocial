@extends('layouts.app')

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
@endpush

@section('titulo')
    Perfil: {{ $user->username }}
@endsection

@section('contenido')
    @if (session('success'))
        <div class="flex justify-center">
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 max-w-md w-full">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="flex justify-center">
        <div class="w-full md:w-8/12 lg:w-6/12 flex flex-col justify-center md:flex-row md:items-center">
            <div class="md:w-8/12 lg:w-6/12 px-5">
                <img src="{{ asset('uploads/' . $user->imagen) }}" alt="imagen usuario">
            </div>
            <div
                class="md:w-8/12 lg:w-6/12 px-5 flex flex-col items-center md:justify-center md:items-start py-10 md:py-10">
                <p class="font-bold text-3xl">{{ $user->username }}</p>
                <p class="text-gray-300 text-sm mb-3 font-bold mt-5">
                    0
                    <span class="font-normal">Seguidores</span>
                </p>
                <p class="text-gray-300 text-sm mb-3 font-bold">
                    0
                    <span class="font-normal">Siguiendo</span>
                </p>
                <p class="text-gray-300 text-sm mb-3 font-bold">
                    0
                    <span class="font-normal">Posts</span>
                </p>
            </div>

        </div>

    </div>

    <section class="container mx-auto mt-10">
        <h2 class="text-4xl text-center font-black my-10">Publicaciones</h2>
        {{-- para iterar un arreglo se utiliza un foreach --}}
        @if ($posts->count())

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {{-- $posts es el arreglo que contiene las publicaciones del usuario --}}
            {{-- cada post es un objeto que contiene los datos de la publicacion --}}
            {{-- se utiliza asset para obtener la ruta de la imagen --}}
            {{-- se utiliza el operador de concatenacion . para unir la ruta de la imagen con el nombre de la imagen --}}
            {{-- se utiliza el operador de interpolacion {{ }} para mostrar el valor de la variable --}}
            {{-- se utiliza el operador de acceso a propiedades -> para acceder a las propiedades del objeto post --}}
            {{-- se utiliza el operador de acceso a propiedades -> para acceder a las propiedades del objeto user --}}
            @foreach ($posts as $post)
                <div>
                    <a href="">
                        <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}">
                    </a>

                </div>
            @endforeach
        </div>

        <div class="my-10 text-blue-700 font-bold">
            {{ $posts->links() }}
        </div>

        @else

        <p class="text-gray-300 uppercase text-sm text-center font-bold mt-10">No hay publicaciones aún</p>
        @endif
    </section>
@endsection

@php($dashboardBg = true)
