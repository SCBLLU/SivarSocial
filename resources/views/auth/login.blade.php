@extends('layouts.app')

@section('titulo')
    <div class="flex items-center justify-center gap-4">
        <div class="text-3xl font-bold text-white">
            Inicia Sesión
        </div>
    </div>
@endsection

@section('contenido')
    <div class="md:flex md:justify-center items-center gap-4">
        <div class="md:w-4/12 p-8 rounded-lg flex items-center">
            <form class="w-full" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                @if (session('success'))
                    <div class="flex justify-center px-4">
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 max-w-md w-full">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                @if (session('status'))
                    <div class="bg-red-500 text-white font-medium my-2 rounded-lg text-sm p-2 text-center">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-4">
                    <label for="email" class="mb-2 block uppercase text-stone-200 font-bold">
                        Email
                    </label>
                    <input 
                        value="{{ old('email') }}"
                        id="email" 
                        name="email" type="email" placeholder="Tu Correo Electrónico"
                        class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20 
                        @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="bg-red-500 text-white font-medium my-2 rounded-lg text-sm p-2 text-center">
                            {{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="mb-2 block uppercase text-stone-200 font-bold">
                        Password
                    </label>
                    <input
                        id="password" 
                        name="password" type="password" placeholder="Password de registro"
                        class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20 
                        @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="bg-red-500 text-white font-medium my-2 rounded-lg text-sm p-2 text-center">
                            {{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 control-rr d-flex justify-between">
                    <label class="inline-flex items-center text-white">
                        <input type="checkbox" name="remember" class="form-checkbox">
                        <span class="ml-2">Recuérdame</span>
                    </label>
                    <a href="{{ route('recuperar') }}" class="">Recuperar Contraseña</a>
                </div>

                <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded w-full mt-4">
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
@endsection
