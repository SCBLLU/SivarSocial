@extends('layouts.app')

@section('contenido')
    <div class=" flex items-center justify-center">
        <div class="w-full max-w-2xl mx-auto text-center">
            {{-- Contenedor principal con diseño simple --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">

                {{-- Icono principal --}}
                <div class="mb-8">
                    {{-- Número 404 --}}
                    <h1 class="text-8xl md:text-9xl font-bold text-[#3B25DD] mb-4">
                        404
                    </h1>
                </div>

                {{-- Mensaje principal --}}
                <div class="mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                        ¡Oops! Esta página se perdió en el feed
                    </h2>
                    <p class="text-gray-600 text-lg mb-6 max-w-md mx-auto">
                        La página que buscas no existe o fue movida. Pero no te preocupes, ¡hay mucho contenido esperándote!
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

                    <button onclick="history.back()"
                        class="inline-flex items-center px-6 py-3 bg-white border-2 border-[#3B25DD] text-[#3B25DD] font-semibold rounded-full">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver Atrás
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection