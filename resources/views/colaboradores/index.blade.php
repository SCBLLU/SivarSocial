@extends('layouts.app')

@section('contenido')
    @php
        // Obtener colaboradores desde la base de datos
        $colaboradores = App\Models\User::where('insignia', 'Colaborador')
            ->select(['id', 'name', 'username', 'imagen', 'profession', 'insignia', 'created_at'])
            ->orderBy('created_at', 'asc')
            ->get();


    @endphp

    <div class="flex items-center justify-center">
        <div class="w-full max-w-7xl mx-auto">

            @if($colaboradores->count() > 0)
                {{-- Contenedor principal con diseño simple --}}
                <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">

                    {{-- Encabezado --}}
                    <div class="text-center mb-12">
                        <div class="mb-6">
                            <i class="fas fa-users text-4xl text-[#3B25DD]"></i>
                        </div>
                        <h2 class="text-3xl md:text-2xl font-bold text-gray-800 mb-4">
                            Nuestro Equipo de Colaboradores
                        </h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Conoce a las personas que hacen posible SivarSocial. Estos colaboradores han contribuido al
                            desarrollo y crecimiento de nuestra plataforma.
                        </p>
                    </div>

                    {{-- Grid de colaboradores mejorado tipo dashboard --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
                        @foreach($colaboradores as $colaborador)
                            <div
                                class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 flex flex-col items-center relative">
                                {{-- Foto de perfil --}}
                                <div class="relative mb-4">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-indigo-600 overflow-hidden">
                                        @if($colaborador->imagen_url)
                                            <img src="{{ $colaborador->imagen_url }}" alt="{{ $colaborador->name }}"
                                                class="w-full h-full object-cover" loading="lazy" onerror="this.src='/img/img.jpg'">
                                        @else
                                            <svg class="w-full h-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                {{-- Badge de colaborador --}}
                                <div class="mb-2">
                                    <x-user-badge badge="Colaborador" size="medium" :show-hover="false" />
                                </div>
                                {{-- Nombre y usuario --}}
                                <h3
                                    class="font-bold text-lg text-gray-900 mt-2 text-center flex items-center justify-center gap-2 min-h-6">
                                    {{ $colaborador->name }}
                                </h3>
                                <p class="text-[#3B25DD] text-xs font-semibold mb-2">{{ '@' . $colaborador->username }}</p>
                                {{-- Profesión --}}
                                <div class="mb-3 text-center">
                                    <span
                                        class="inline-block bg-indigo-50 text-indigo-700 text-xs font-medium px-3 py-1 rounded-full">
                                        {{ $colaborador->profession ?? 'Colaborador' }}
                                    </span>
                                </div>
                                {{-- Botón de perfil --}}
                                <a href="{{ route('posts.index', $colaborador->username) }}"
                                    class="inline-block w-full text-center bg-[#3B25DD] text-white font-semibold py-2 rounded-full text-xs hover:bg-[#2F1CB8] transition">
                                    Ver Perfil
                                </a>
                            </div>
                        @endforeach
                    </div>


                    {{-- Botón de vuelta --}}
                    <div class="text-center">
                        <button onclick="goBackOrHome()"
                            class="inline-flex items-center px-6 py-3 bg-[#3B25DD] text-white font-semibold rounded-full hover:bg-[#2F1CB8] transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                            Regresar
                        </button>
                    </div>
                </div>

            @else
                {{-- Estado vacío --}}
                <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                    {{-- Icono principal --}}
                    <div class="text-center mb-8">
                        <i class="fas fa-users text-8xl md:text-9xl text-gray-300 mb-4"></i>
                    </div>

                    {{-- Mensaje principal --}}
                    <div class="text-center mb-8">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                            No hay colaboradores registrados
                        </h2>
                        <p class="text-gray-600 text-lg mb-6 max-w-md mx-auto">
                            Actualmente no hay usuarios con el badge de colaborador en la plataforma.
                        </p>
                    </div>

                    {{-- Botón de acción --}}
                    <div class="text-center">
                        <button onclick="goBackOrHome()"
                            class="inline-flex items-center px-6 py-3 bg-[#3B25DD] text-white font-semibold rounded-full hover:bg-[#2F1CB8] transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                            Regresar
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function goBackOrHome() {
            // Si hay historial de navegación, regresa atrás
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // Si no hay historial, va al inicio
                window.location.href = "{{ route('home') }}";
            }
        }
    </script>
@endsection