@extends('layouts.app')
@section('titulo')
    Editar Perfil: {{ Auth::user()->username }}
@endsection

@section('contenido')
    <div class="md:flex md:justify-center">
        <div class="md:w-1/2 bg-white p-6 rounded shadow flex flex-col items-center">
            <div class="flex justify-center mb-6 w-full">
                <img src="/perfiles/{{ Auth::user()->imagen }}" alt="Avatar" class="w-40 h-40 rounded-full object-cover border-4" style="border-color: #4b00fd;">
            </div>
            <form action="{{ route('perfil.store') }}" method="POST" enctype="multipart/form-data" class="w-full mt-0">
                <div class="mb-5">
                    @csrf
                    <label for="username" class="block text-gray-700">Nombre de usuario</label>
                    <input type="text" name="username" id="username"
                        class="w-full border p-2 rounded text-gray-800 font-semibold {{ $errors->has('username') ? 'border-red-500' : 'border-gray-500' }}"
                        value="{{ old('username', Auth::user()->username) }}" placeholder="Nombre de usuario">
                    @error('username')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="email" class="block text-gray-700">Correo electrónico</label>
                    <input type="email" name="email" id="email"
                        class="w-full border p-2 rounded text-gray-800 font-semibold {{ $errors->has('email') ? 'border-red-500' : 'border-gray-500' }}"
                        value="{{ old('email', Auth::user()->email) }}" placeholder="Correo electrónico">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="imagen" class="block text-gray-700">Imagen de perfil</label>
                    <input accept=".jpg, .jpeg, .png" type="file" name="imagen" id="imagen" class="w-full mt-1 border border-gray-500 p-2 rounded text-gray-700 file:bg-[#4b00fd] file:text-white file:border-0 file:rounded file:px-4 file:py-2 bg-white">
                    @error('imagen')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-gray-700">Nueva contraseña</label>
                    <input type="password" name="password" id="password"
                        class="w-full border p-2 rounded text-gray-800 font-semibold {{ $errors->has('password') ? 'border-red-500' : 'border-gray-500' }}"
                        placeholder="Dejar en blanco para no cambiar">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password_confirmation" class="block text-gray-700">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border p-2 rounded text-gray-800 font-semibold {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-500' }}"
                        placeholder="Repite la nueva contraseña">
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                    Guardar Cambios
                </button>
                <a href="{{ route('posts.index', ['user' => Auth::user()->username]) }}" class="ml-4 inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</a>
        </div>
        </form>
    </div>
    </div>
@endsection
