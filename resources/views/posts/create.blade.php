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
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                background: #000 !important;
                z-index: 9999 !important;
                display: flex !important;
                flex-direction: column !important;
                overflow: hidden !important;
                /* Propiedades para fullscreen inmersivo */
                -webkit-transform: translate3d(0, 0, 0) !important;
                transform: translate3d(0, 0, 0) !important;
                /* Ocultar barras de navegación en móvil */
                -webkit-appearance: none !important;
                -webkit-user-select: none !important;
                -webkit-touch-callout: none !important;
                -webkit-tap-highlight-color: transparent !important;
            }

            .mobile-camera-overlay.hidden {
                display: none !important;
            }

            #camera-preview {
                flex: 1 !important;
                max-width: none !important;
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                border-radius: 0 !important;
                background: #000 !important;
            }

            /* Ocultar elementos de la página cuando la cámara está activa */
            body:has(.mobile-camera-overlay:not(.hidden)) {
                overflow: hidden !important;
                position: fixed !important;
                width: 100% !important;
                height: 100% !important;
            }

            /* Asegurar que los controles de la cámara estén en el frente */
            .camera-controls {
                position: absolute !important;
                bottom: 40px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                z-index: 10000 !important;
            }
        }

        /* En desktop, asegurar que el overlay nunca se muestre */
        @media (min-width: 769px) {
            .mobile-camera-overlay {
                display: none !important;
            }
        }

        /* Estilos adicionales para fullscreen inmersivo */
        .mobile-camera-overlay {
            /* Evitar zoom al hacer doble tap */
            touch-action: manipulation !important;
            /* Optimización de rendimiento */
            will-change: transform !important;
            /* Asegurar que cubra toda la pantalla */
            position: fixed !important;
            inset: 0 !important;
        }

        /* Ocultar elementos de UI cuando la cámara está activa */
        .camera-active {
            overflow: hidden !important;
            position: fixed !important;
            width: 100% !important;
            height: 100% !important;
        }

        .camera-active .header,
        .camera-active .header2,
        .camera-active .menu-mobile {
            display: none !important;
        }

        /* Estilos para el header de la cámara */
        .mobile-camera-overlay .camera-header {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 10001 !important;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 50%, transparent 100%) !important;
            padding: env(safe-area-inset-top, 20px) 20px 20px 20px !important;
        }

        /* Ajustes específicos para dispositivos móviles */
        .mobile-camera-overlay {
            /* iOS Safari y Android Chrome específico */
            -webkit-user-select: none !important;
            -webkit-touch-callout: none !important;
            -webkit-tap-highlight-color: transparent !important;
            -webkit-backface-visibility: hidden !important;
            -webkit-perspective: 1000 !important;
            -webkit-transform: translate3d(0, 0, 0) !important;
            transform: translate3d(0, 0, 0) !important;
        }

        /* Para iOS con notch */
        .mobile-camera-overlay .camera-header {
            padding-top: max(env(safe-area-inset-top), 44px) !important;
        }

        /* Ajustes para Android Chrome */
        @media screen and (-webkit-min-device-pixel-ratio: 1) and (min-device-width: 320px) and (max-device-width: 1024px) {
            .mobile-camera-overlay {
                /* Asegurar que cubra la barra de navegación en Android */
                height: 100dvh !important;
                min-height: 100vh !important;
            }
        }

        /* Prevenir selección de texto y zoom en controles */
        .mobile-camera-overlay * {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            user-select: none !important;
            -webkit-touch-callout: none !important;
            -webkit-tap-highlight-color: transparent !important;
        }

        /* Optimización para el botón de captura */
        .capture-btn {
            -webkit-transform: translateZ(0) !important;
            transform: translateZ(0) !important;
            will-change: transform !important;
        }

        .capture-btn:active {
            transform: scale(0.95) translateZ(0) !important;
            -webkit-transform: scale(0.95) translateZ(0) !important;
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
        <div class="camera-header flex justify-between items-center">
            <button id="close-camera" class="text-white text-xl p-2 rounded-full bg-black/30 backdrop-blur-sm">
                <i class="fas fa-times"></i>
            </button>
            <span class="text-white font-medium text-lg">Tomar Foto</span>
            <button id="switch-camera" class="switch-camera-btn bg-black/30 backdrop-blur-sm">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        <video id="camera-preview" autoplay playsinline muted></video>

        <div class="camera-controls">
            <button id="capture-photo" class="capture-btn shadow-2xl">
                <i class="fas fa-camera text-gray-600 text-xl"></i>
            </button>
        </div>

        <canvas id="photo-canvas" style="display: none;"></canvas>
    </div>
@endsection