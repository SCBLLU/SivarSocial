@extends('layouts.app')

@section('contenido')
    <div class=" flex items-center justify-center">
        <div class="w-full max-w-2xl mx-auto text-center">
            {{-- Contenedor principal con diseño simple --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">

                {{-- Icono principal --}}
                <div class="mb-8">
                    {{-- Número 500 --}}
                    <h1 class="text-8xl md:text-9xl font-bold text-orange-600 mb-4">
                        500
                    </h1>
                </div>

                {{-- Mensaje principal --}}
                <div class="mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                        ¡Houston, tenemos un problema!
                    </h2>
                    <p class="text-gray-600 text-lg mb-6 max-w-md mx-auto">
                        Algo salió mal en nuestros servidores. Nuestro equipo técnico ya está trabajando en solucionarlo.
                    </p>
                </div>

                {{-- Botones de acción --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center px-6 py-3 bg-[#3B25DD] text-white font-semibold rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        Ir al Inicio
                    </a>

                    <button onclick="location.reload()"
                        class="inline-flex items-center px-6 py-3 bg-white border-2 border-[#3B25DD] text-[#3B25DD] font-semibold rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Intentar de nuevo
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection