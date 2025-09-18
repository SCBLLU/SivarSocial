@extends('layouts.app')

@push('scripts')
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Asegurar que el modal esté completamente oculto inicialmente */
        .modal-backdrop[style*="display: none"] {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }

        /* Prevenir flash de contenido Alpine.js */
        [x-cloak] {
            display: none !important;
        }

        /* Asegurar visibilidad correcta de elementos con x-show */
        [x-show]:not([style*="display: none"]) {
            visibility: visible !important;
        }

        /* Ocultar elementos con x-show false por defecto */
        [x-show][style*="display: none"] {
            display: none !important;
            visibility: hidden !important;
        }
    </style>
@endpush

@section('titulo')
    <div class="flex items-center justify-center relative w-full">
        <a href="{{ url()->previous() }}"
            class="absolute left-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-white text-2xl font-bold mx-auto">Perfil</h1>
    </div>
@endsection

@section('contenido')
    <div class="min-h-screen flex items-center justify-center py-8 px-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 relative" x-data="{ 
                                openDelete: false, 
                                showMenu: false,
                                init() {
                                    // Asegurar que los modales inicien cerrados y prevenir cualquier flash
                                    this.openDelete = false;
                                    this.showMenu = false;
                                    // Usar nextTick para asegurar que DOM esté listo
                                    this.$nextTick(() => {
                                        this.openDelete = false;
                                        this.showMenu = false;
                                    });
                                }
                            }" x-cloak>
            <!-- Menú de tres puntos -->
            <div class="absolute top-4 right-4">
                <button @click="showMenu = !showMenu"
                    class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                    </svg>
                </button>
                <!-- Dropdown menu -->
                <div x-show="showMenu" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95" @click.away="showMenu = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10 py-2"
                    style="display: none;">

                    {{-- Eliminar cuenta --}}
                    <button @click="openDelete = true; showMenu = false"
                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center transition-colors">
                        <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar cuenta
                    </button>
                </div>
            </div>
            <!-- Header con título -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-600 mb-2">Editar Cuenta</h1>
            </div>

            <!-- Avatar section -->
            <div class="flex justify-center mb-8">
                <div class="relative profile-avatar">
                    @if(Auth::user()->imagen)
                        <img src="/perfiles/{{ Auth::user()->imagen }}" alt="Avatar"
                            class="w-32 h-32 rounded-full object-cover border-4" style="border-color: #4b00fd;">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-200 border-4 flex items-center justify-center"
                            style="border-color: #4b00fd;">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-lg border-2"
                        style="border-color: #4b00fd;">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <form action="{{ route('perfil.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Campo Nombre de Perfil -->
                <div>
                    @csrf
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de Perfil</label>
                    <input type="text" name="name" id="name"
                        class="profile-form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 {{ $errors->has('name') ? 'border-red-500' : '' }}"
                        value="{{ old('name', Auth::user()->name ?? '') }}" placeholder="Rafael">
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Nombre de Usuario -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Nombre de Usuario</label>
                    <input type="text" name="username" id="username"
                        class="profile-form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 {{ $errors->has('username') ? 'border-red-500' : '' }}"
                        value="{{ old('username', Auth::user()->username ?? '') }}" placeholder="usuario.ejemplo">
                    @error('username')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Puedes usar letras, números, puntos (.) y guiones (-_)</p>
                </div>

                <!-- Campo Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" id="email"
                        class="profile-form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 {{ $errors->has('email') ? 'border-red-500' : '' }}"
                        value="{{ old('email', Auth::user()->email ?? '') }}" placeholder="rafael@ejemplo.com">
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Profesión -->
                <div>
                    <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">Profesión</label>
                    <input type="text" name="profession" id="profession"
                        class="profile-form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 {{ $errors->has('profession') ? 'border-red-500' : '' }}"
                        value="{{ old('profession', Auth::user()->profession ?? '') }}" placeholder="Desarrollador UX / UI">
                    @error('profession')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Contraseña (Opcional) -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña
                        (Opcional)</label>
                    <input type="password" name="password" id="password"
                        class="profile-form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 {{ $errors->has('password') ? 'border-red-500' : '' }}"
                        placeholder="Dejar vacío si no deseas cambiarla">
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Solo completa este campo si deseas cambiar tu contraseña</p>
                </div>

                <!-- Campo Repetir Contraseña (Opcional) -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nueva
                        Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="profile-form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 {{ $errors->has('password_confirmation') ? 'border-red-500' : '' }}"
                        placeholder="Repetir la nueva contraseña">
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo oculto para imagen -->
                <input accept=".jpg, .jpeg, .png, .gif" type="file" name="imagen" id="imagen" class="hidden">

                <!-- Botón principal -->
                <button type="submit"
                    class="w-full bg-gray-700 text-white font-semibold py-4 px-6 rounded-lg hover:bg-gray-800 transition duration-200">
                    Listo
                </button>
            </form>

            <!-- Gestión de enlaces sociales (fuera del formulario) -->
            <div class="bg-gray-50 p-6 rounded-lg border mt-6">
                <livewire:social-links-manager />
            </div>

            <!-- Script para manejar el click en la imagen -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const avatarContainer = document.querySelector('.profile-avatar');
                    const fileInput = document.getElementById('imagen');
                    const avatarImg = document.querySelector('img[alt="Avatar"]');
                    const avatarDiv = document.querySelector('.profile-avatar div');

                    // Manejar click en el contenedor del avatar
                    avatarContainer.addEventListener('click', function () {
                        fileInput.click();
                    });

                    // Manejar cambio de archivo
                    fileInput.addEventListener('change', function (e) {
                        if (e.target.files && e.target.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                // Si ya hay una imagen, actualizarla
                                if (avatarImg) {
                                    avatarImg.src = e.target.result;
                                } else {
                                    // Si no hay imagen, crear una nueva y ocultar el div placeholder
                                    const newImg = document.createElement('img');
                                    newImg.src = e.target.result;
                                    newImg.alt = 'Avatar';
                                    newImg.className = 'w-32 h-32 rounded-full object-cover border-4';
                                    newImg.style.borderColor = '#4b00fd';

                                    // Ocultar el div placeholder si existe
                                    if (avatarDiv) {
                                        avatarDiv.style.display = 'none';
                                    }

                                    // Insertar la nueva imagen antes del botón de editar
                                    const editButton = avatarContainer.querySelector('div.absolute');
                                    avatarContainer.insertBefore(newImg, editButton);
                                }
                            }
                            reader.readAsDataURL(e.target.files[0]);
                        }
                    });
                });
            </script>

            {{-- Modal simple y minimalista para eliminar cuenta --}}
            <div x-show="openDelete" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
                style="display: none;">
                <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full mx-4 transform transition-all"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                    <div class="p-6">
                        <!-- Icono de advertencia -->
                        <div class="flex justify-center mb-4">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Título y mensaje -->
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar cuenta?</h3>
                            <p class="text-gray-600 text-sm">
                                Esta acción eliminará permanentemente tu cuenta y todos tus datos.
                                <span class="font-semibold text-red-600">No se puede deshacer.</span>
                            </p>
                        </div>

                        <!-- Botones -->
                        <div class="flex gap-3">
                            <button @click="openDelete = false"
                                class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition duration-200">
                                Cancelar
                            </button>
                            <form method="POST" action="{{ route('user.destroy') }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition duration-200">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection