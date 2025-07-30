@extends('layouts.app')
{{-- la section inyecta el contenido dentro del yield --}}
@section('titulo')
    Publicaciones
@endsection

@section('contenido')
    <div class="flex h-full">
        <div class="container mx-auto">
            <div class="grid h-full grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Columna 1: Vacía (espaciador) -->
                <div class="hidden lg:block"></div>

                <!-- Columna 2: Posts centrados con scroll interno -->
                <div class="w-full h-full">
                    <div id="posts-container" class="h-full pr-2 overflow-y-auto">
                        @include('components.new-post')
                        @component('components.listar-post', ['posts' => $posts])
                        @endcomponent
                    </div>
                </div>

                <!-- Columna 3: Perfiles a la derecha con altura igual a posts -->
                <div class="w-full h-full">
                    <div id="users-container" class="flex flex-col p-4 bg-white shadow-lg rounded-2xl"
                        style="height: fit-content; max-height: 600px;">
                        <h2
                            class="flex items-center justify-center flex-shrink-0 gap-2 mb-4 text-xl font-bold text-purple-700">
                            Perfiles
                            <i class="w-6 h-6 fa-solid fa-user-group"></i>
                        </h2>

                        <hr class="border-gray-300 mb-4 w-[80%] mx-auto">
                        <div class="flex-1 overflow-y-scroll scrollbar-purple"
                            style="max-height: 400px; min-height: 200px;">
                            @component('components.listar-perfiles', ['users' => $users])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Scrollbar personalizado para la sección de Perfiles */
        .scrollbar-purple {
            scrollbar-width: thin;
            scrollbar-color: #7e22ce #ede9fe;
        }

        .scrollbar-purple::-webkit-scrollbar {
            width: 12px !important;
            background-color: #ede9fe !important;
        }

        .scrollbar-purple::-webkit-scrollbar-track {
            background: #ede9fe !important;
            border-radius: 8px !important;
            margin: 4px !important;
        }

        .scrollbar-purple::-webkit-scrollbar-thumb {
            background: #7e22ce !important;
            border-radius: 8px !important;
            border: 2px solid #ede9fe !important;
        }

        .scrollbar-purple::-webkit-scrollbar-thumb:hover {
            background: #6b21a8 !important;
        }

        .scrollbar-purple::-webkit-scrollbar-corner {
            background: #ede9fe !important;
        }

        /* Forzar que siempre se muestre el scrollbar */
        .scrollbar-purple {
            overflow-y: scroll !important;
        }

        /* Scrollbar personalizado para los posts */
        #posts-container::-webkit-scrollbar {
            width: 8px;
        }

        #posts-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        #posts-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        #posts-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
@endpush
