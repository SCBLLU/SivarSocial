@extends('layouts.app')

@section('titulo')
@endsection

@section('contenido-recover')
    <main class="container items-center justify-center p-5 mx-auto mt-10 mb-5 d-flex">
        <div>
            <h2 class="mb-10 text-3xl font-bold text-center">
                <div class="flex items-center justify-center gap-4">
                    <div class="text-3xl font-bold text-white">
                        Recuperar Contraseña
                    </div>
                </div>
            </h2>
            <div class="items-center gap-4 md:justify-center">
                <div class="flex items-center rounded-lg">
                    <form class="w-full" method="POST" action="{{ route('recuperar.enviar') }}">
                        @csrf

                        @if (session('status'))
                            <div class="p-2 my-2 text-sm font-medium text-center text-white bg-red-500 rounded-lg">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="mb-4">
                            <label for="email" class="block mb-2 font-bold uppercase text-stone-200">
                                Correo
                            </label>
                            <input value="" id="emailre" name="email" type="email"
                                placeholder="Tu Correo electrónico"
                                class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20 
                        @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="p-2 my-2 text-sm font-medium text-center text-white bg-red-500 rounded-lg">
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2 mt-4 font-bold text-white bg-blue-700 rounded hover:bg-blue-800"
                            style="border-radius:30px">
                            Recuperar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </main>
@endsection
