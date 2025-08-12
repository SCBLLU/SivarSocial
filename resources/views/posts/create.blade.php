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
            bottom: 80px;
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
                /* Usar altura de ventana disponible */
                height: 100vh;
                height: 100dvh;
                /* Altura dinámica en móviles modernos */
            }

            #camera-preview {
                flex: 1;
                max-width: none;
                height: auto;
                border-radius: 0;
            }

            .camera-controls {
                /* En móvil, posicionar más arriba para evitar conflictos con UI del navegador */
                bottom: 100px;
                min-height: 70px;
                /* Para devices con notch o barra de navegación segura */
                padding-bottom: env(safe-area-inset-bottom, 0px);
            }

            /* Para pantallas con altura limitada */
            @media (max-height: 600px) {
                .camera-controls {
                    bottom: 60px;
                }
            }

            /* Para pantallas muy pequeñas */
            @media (max-height: 500px) {
                .camera-controls {
                    bottom: 40px;
                }

                .capture-btn {
                    width: 60px;
                    height: 60px;
                }
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

        /* Estilos adicionales para mejorar la experiencia móvil */
        @media (max-width: 768px) {

            /* Manejar mejor el overlay cuando cambia la altura del viewport */
            .mobile-camera-overlay.viewport-adjusted {
                height: calc(100vh - 60px);
                /* Ajuste para barras de navegación */
            }

            /* Botón más visible con sombra */
            .capture-btn {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                border: 3px solid #ffffff;
            }

            /* Header de la cámara más compacto en pantallas pequeñas */
            .mobile-camera-overlay .flex.justify-between {
                padding: 12px 16px;
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(10px);
            }
        }

        /* Ocultar menú de navegación móvil cuando la cámara esté activa */
        .camera-active .header,
        .camera-active .header2,
        .camera-active #header {
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            z-index: -1 !important;
        }

        /* Pantalla de permisos de cámara */
        .permission-screen-hidden {
            display: none;
        }

        .permission-screen-visible {
            display: flex;
        }

        /* Animaciones para la pantalla de permisos */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .permission-screen-visible {
            animation: fadeIn 0.3s ease-out;
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
        <!-- Pantalla de permisos -->
        <div id="camera-permission-screen"
            class="permission-screen-hidden absolute inset-0 bg-black flex-col items-center justify-center z-20">
            <div class="text-center px-6">
                <!-- Ícono animado de cámara -->
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full animate-pulse">
                        <i class="fas fa-camera text-4xl text-gray-700"></i>
                    </div>
                </div>

                <!-- Mensaje principal -->
                <h3 class="text-white text-xl font-semibold mb-4">
                    Solicitando permisos de cámara
                </h3>

                <!-- Instrucciones -->
                <p class="text-gray-300 text-base mb-6 max-w-sm">
                    Tu navegador te pedirá permiso para acceder a la cámara.
                    <span class="text-white font-medium">Presiona "Permitir"</span> para continuar.
                </p>

                <!-- Indicador de carga -->
                <div class="flex items-center justify-center space-x-2">
                    <div class="w-2 h-2 bg-white rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>

                <!-- Botón cancelar -->
                <button id="cancel-permission"
                    class="mt-8 px-6 py-2 border border-gray-400 text-gray-300 rounded-full hover:bg-white hover:text-black transition-colors">
                    Cancelar
                </button>
            </div>
        </div>

        <!-- Pantalla de error de permisos -->
        <div id="camera-error-screen"
            class="permission-screen-hidden absolute inset-0 bg-black flex-col items-center justify-center z-20">
            <div class="text-center px-6">
                <!-- Ícono de error -->
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-red-500 rounded-full">
                        <i class="fas fa-exclamation-triangle text-4xl text-white"></i>
                    </div>
                </div>

                <!-- Mensaje de error -->
                <h3 id="error-title" class="text-white text-xl font-semibold mb-4">
                    Error de permisos
                </h3>

                <!-- Descripción del error -->
                <p id="error-description" class="text-gray-300 text-base mb-6 max-w-sm">
                    No se pudo acceder a la cámara. Verifica los permisos en tu navegador.
                </p>

                <!-- Botones -->
                <div class="flex flex-col space-y-3">
                    <button id="retry-permission"
                        class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                        Intentar de nuevo
                    </button>
                    <button id="close-error"
                        class="px-6 py-2 border border-gray-400 text-gray-300 rounded-full hover:bg-white hover:text-black transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center p-4 bg-black/70 backdrop-blur-sm">
            <button id="close-camera" class="text-white text-xl p-2 rounded-full hover:bg-white/20 transition-colors">
                <i class="fas fa-times"></i>
            </button>
            <span class="text-white font-medium text-lg">Tomar Foto</span>
            <button id="switch-camera"
                class="switch-camera-btn text-white p-2 rounded-full hover:bg-white/20 transition-colors">
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