    @extends('layouts.app')
    @section('titulo')
        Editar Perfil: {{ Auth::user()->username }}
    @endsection

    @section('contenido')
        <div class="md:flex md:justify-center">
            <div class="md:w-1/2 bg-white p-6 rounded shadow flex flex-col items-center" x-data="{ openDelete: false }">
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
                        <input accept=".jpg, .jpeg, .png, .gif" type="file" name="imagen" id="imagen" class="w-full mt-1 border border-gray-500 p-2 rounded text-gray-700 file:bg-[#4b00fd] file:text-white file:border-0 file:rounded file:px-4 file:py-2 bg-white cursor-pointer">
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
                    {{-- botón eliminar cuenta alineado a la derecha --}}
                    <button type="button" @click="openDelete = true" class="ml-auto bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded float-right cursor-pointer">
                        Eliminar cuenta
                    </button>
                </form>
                {{-- modal de confirmación para eliminar cuenta --}}
                <div x-show="openDelete" x-transition class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none">
                    <div class="bg-white border border-gray-300 rounded-lg shadow-2xl p-8 max-w-sm w-full pointer-events-auto">
                        <h2 class="text-xl font-bold mb-4 text-gray-800">¿Estás seguro de que deseas eliminar tu cuenta?</h2>
                        <p class="mb-6 text-gray-600">Esta acción es irreversible y eliminará todos tus datos.</p>
                        <div class="flex justify-end gap-4">
                            <button @click="openDelete = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancelar</button>
                            <form method="POST" action="{{ route('user.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-bold">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection