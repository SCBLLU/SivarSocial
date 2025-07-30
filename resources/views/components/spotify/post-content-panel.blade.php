<!-- Componente para el panel de contenido de posts -->
<div class="md:w-1/2 px-4">
    <!-- Contenido para post de imagen -->
    <div id="content-imagen" class="content-panel">
        <div class="flex flex-col items-center justify-center">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="dropzone"
                class="dropzone text-zinc-950 border-dashed border-2 w-full h-96 rounded-2xl flex flex-col items-center justify-center bg-white/10 backdrop-blur-md border-white/20">
                @csrf
            </form>
            <p class="text-gray-300 text-sm text-center mt-4">PNG, JPG, GIF, Máximo 2MB.</p>
        </div>
    </div>

    <!-- Contenido para post de música -->
    <div id="content-musica" class="content-panel hidden">
        @include('components.spotify.music-content-panel')
    </div>
</div>

@push('styles')
    <style>
        /* Estilos para el panel de contenido */
        .content-panel {
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .content-panel.hidden {
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
        }

        .content-panel:not(.hidden) {
            opacity: 1;
            transform: translateY(0);
        }

        /* Estilos para el dropzone */
        .dropzone {
            transition: all 0.3s ease;
        }

        .dropzone:hover {
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.15);
        }

        .dropzone.dz-drag-hover {
            border-color: rgba(34, 197, 94, 0.8) !important;
            background: rgba(34, 197, 94, 0.1) !important;
            transform: scale(1.02);
        }
    </style>
@endpush