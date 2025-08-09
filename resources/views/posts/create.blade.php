@extends('layouts.app')

@section('titulo')
    <div class="flex items-center justify-center relative w-full">
        <a href="{{ url()->previous() }}"
            class="absolute left-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-white text-2xl font-bold mx-auto">Crear Publicación</h1>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <style>
        /* Estilos para la cámara */
        #camera-preview {
            width: 100%;
            max-width: 400px;
            height: 400px;
            object-fit: cover;
            border-radius: 12px;
            background: #000;
        }

        .camera-controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            z-index: 10;
        }

        .capture-btn {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: white;
            border: 4px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .capture-btn:hover {
            transform: scale(1.1);
        }

        .switch-camera-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-camera-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: #000;
                z-index: 50;
                display: flex;
                flex-direction: column;
            }

            #camera-preview {
                flex: 1;
                max-width: none;
                height: auto;
                border-radius: 0;
            }
        }

        /* En desktop, asegurar que el overlay nunca se muestre */
        @media (min-width: 769px) {
            .mobile-camera-overlay {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                z-index: -1 !important;
                pointer-events: none !important;
            }
        }

        /* Asegurar que hidden funcione correctamente */
        .hidden {
            display: none !important;
        }

        /* Mejoras adicionales para móvil en el componente iTunes */
        @media (max-width: 640px) {

            /* Contenedor principal más compacto */
            .max-w-md {
                max-width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* Mejora el padding en dispositivos muy pequeños */
            .bg-black.rounded-t-xl,
            .bg-black.rounded-b-xl {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }

            /* Optimizar el espaciado del contenedor principal del formulario */
            .bg-white.rounded-2xl {
                margin-top: 0.75rem;
                border-radius: 1rem;
            }
        }

        /* Para pantallas muy pequeñas */
        @media (max-width: 375px) {
            .px-2 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }
    </style>
@endpush

@section('contenido')
    <div class="max-w-2xl mx-auto px-4">
        <!-- Tabs minimalistas -->
        @include('posts.post-type-tabs')

        <!-- Contenedor principal del formulario -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Panel de contenido -->
            <div class="p-6">
                <!-- Contenido para post de imagen -->
                <div id="content-imagen" class="content-panel">
                    @include('posts.image-upload-panel')
                </div>

                <!-- Contenido para post de música -->
                <div id="content-musica" class="content-panel hidden">
                    @include('components.itunes.iTunes')
                </div>
            </div>

            <!-- Formulario -->
            <div class="border-t border-gray-100 p-6">
                @include('posts.form')
            </div>
        </div>
    </div>

    <!-- Overlay para cámara en móvil -->
    <div id="camera-overlay" class="mobile-camera-overlay hidden">
        <div class="flex justify-between items-center p-4 bg-black/50">
            <button id="close-camera" class="text-white text-xl">
                <i class="fas fa-times"></i>
            </button>
            <span class="text-white font-medium">Tomar Foto</span>
            <button id="switch-camera" class="switch-camera-btn">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        <video id="camera-preview" autoplay playsinline></video>

        <div class="camera-controls">
            <button id="capture-photo" class="capture-btn">
                <i class="fas fa-camera text-gray-600 text-xl"></i>
            </button>
        </div>

        <canvas id="photo-canvas" style="display: none;"></canvas>
    </div>
@endsection