@extends('layouts.app')

@section('titulo')
    <div class="flex items-center justify-center gap-4">
        <div class="text-3xl font-bold text-white">
            Inicia Sesión
        </div>
    </div>
@endsection

@section('contenido')
    <div class="w-full max-w-xl p-8 mx-auto bg-white shadow-md rounded-2xl" x-data="{ togglePassword: false }">
        <form class="w-full" method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            @if (session('success'))
                <div class="flex justify-center px-4">
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                        class="relative w-full max-w-md px-4 py-3 mb-6 text-green-700 bg-green-100 border border-green-400 rounded">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if (session('status'))
                <div class="p-2 my-2 text-sm font-medium text-center text-white bg-red-500 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif
            <div class="mb-4">
                <label for="email" class="block mb-2 font-bold text-gray-800">
                    Correo
                </label>
                <input value="{{ old('email') }}" id="email" name="email" type="email"
                    placeholder="Tu Correo Electrónico"
                    class="w-full px-4 py-3 text-gray-900 border rounded-lg profile-form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="p-2 my-2 text-sm font-medium text-center text-white bg-red-500 rounded-lg">
                        {{ $message }}</p>
                @enderror
            </div>

            <div class="relative mb-4">
                <label for="password" class="block mb-2 font-bold text-gray-800">
                    Contraseña
                </label>
                <input :type="togglePassword ? 'text' : 'password'" id="password" name="password"
                    placeholder="Password de registro"
                    class="w-full px-4 py-3 text-gray-900 border rounded-lg profile-form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                <div class="absolute top-0 bottom-0 right-0 px-3 py-3 mt-8 cursor-pointer"
                    @click="togglePassword = !togglePassword">
                    <svg :class="{ 'hidden': !togglePassword, 'block': togglePassword }" xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-blue-900 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 19c.946 0 1.81-.103 2.598-.281l-1.757-1.757C12.568 16.983 12.291 17 12 17c-5.351 0-7.424-3.846-7.926-5 .204-.47.674-1.381 1.508-2.297L4.184 8.305c-1.538 1.667-2.121 3.346-2.132 3.379-.069.205-.069.428 0 .633C2.073 12.383 4.367 19 12 19zM12 5c-1.837 0-3.346.396-4.604.981L3.707 2.293 2.293 3.707l18 18 1.414-1.414-3.319-3.319c2.614-1.951 3.547-4.615 3.561-4.657.069-.205.069-.428 0-.633C21.927 11.617 19.633 5 12 5zM16.972 15.558l-2.28-2.28C14.882 12.888 15 12.459 15 12c0-1.641-1.359-3-3-3-.459 0-.888.118-1.277.309L8.915 7.501C9.796 7.193 10.814 7 12 7c5.351 0,7.424 3.846,7.926 5C19.624 12.692,18.76 14.342,16.972 15.558z" />
                    </svg>
                    <svg :class="{ 'hidden': togglePassword, 'block': !togglePassword }" xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-blue-900 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12,9c-1.642,0-3,1.359-3,3c0,1.642,1.358,3,3,3c1.641,0,3-1.358,3-3C15,10.359,13.641,9,12,9z" />
                        <path
                            d="M12,5c-7.633,0-9.927,6.617-9.948,6.684L1.946,12l0.105,0.316C2.073,12.383,4.367,19,12,19s9.927-6.617,9.948-6.684 L22.054,12l-0.105-0.316C21.927,11.617,19.633,5,12,5z M12,17c-5.351,0-7.424-3.846-7.926-5C4.578,10.842,6.652,7,12,7 c5.351,0,7.424,3.846,7.926,5C19.422,13.158,17.348,17,12,17z" />
                    </svg>
                </div>
                @error('password')
                    <p class="p-2 my-2 text-sm font-medium text-center text-white bg-red-500 rounded-lg">
                        {{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-4">
                <label class="inline-flex items-center text-gray-800">
                    <input type="checkbox" name="remember" class="form-checkbox">
                    <span class="ml-2">Recuérdame</span>
                </label>
                <a href="{{ route('recuperar') }}" class="text-sm text-blue-700 hover:text-purple-700">¿Olvidaste tu
                    Contraseña?</a>
            </div>

            <button type="submit" class="w-full px-4 py-2 mt-4 font-bold text-white bg-blue-700 rounded hover:bg-blue-800">
                Iniciar Sesión
            </button>
            <style>
                button.bg-blue-700 {
                    border-radius: 30px !important;
                }
            </style>
            </button>

            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-sm text-blue-700 hover:text-purple-700">
                    ¿No tienes una cuenta? Créala aquí
                </a>
            </div>
        </form>
    </div>
@endsection
