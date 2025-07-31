@extends('layouts.app')
{{-- la section inyecta el contenido dentro del yield --}}
@section('titulo')
    Publicaciones
@endsection

@section('contenido')
    <div class="flex h-full">
        <div class="container mx-auto px-4">
            <div class="grid h-full grid-cols-1 gap-4 lg:gap-8 lg:grid-cols-3">
                <!-- Columna 1: Vacía (espaciador) -->
                <div class="hidden lg:block"></div>

                <!-- Columna 2: Posts centrados sin scroll interno -->
                <div class="w-full flex flex-col">
                    <div id="posts-container" class="pr-0 lg:pr-2 flex flex-col">
                        @include('components.new-post')
                        @component('components.listar-post', ['posts' => $posts])
                        @endcomponent
                    </div>
                </div>

                <!-- Columna 3: Perfiles a la derecha con altura igual a posts -->
                <div class="w-full h-full  lg:flex lg:flex-col">
                    <div id="users-container" class="flex flex-col p-4 bg-white shadow-lg rounded-2xl h-full max-h-[600px]">
                        <h2
                            class="flex items-center justify-center flex-shrink-0 gap-2 mb-4 text-xl font-bold text-purple-700">
                            Perfiles
                            <i class="w-6 h-6 fa-solid fa-user-group"></i>
                        </h2>

                        <hr class="border-gray-300 mb-4 w-[80%] mx-auto">
                        <div class="flex-1 overflow-y-auto scrollbar-purple min-h-0">
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
            overflow-y: auto !important;
        }

        /* Asegurar que las columnas se alineen correctamente */
        .grid {
            align-items: start;
        }

        /* Altura consistente para ambas columnas */
        #posts-container,
        #users-container {
            height: 100%;
        }

        /* Optimización específica para la paginación en el grid */
        #posts-container {
            min-height: 70vh; /* Asegurar altura mínima */
            display: flex;
            flex-direction: column;
        }

        /* Asegurar que la paginación se mantenga en la parte inferior */
        #posts-container > div:last-child {
            margin-top: auto;
        }

        /* Espaciado optimizado para la columna central */
        @media (min-width: 1024px) {
            #posts-container {
                max-width: 100%;
                overflow: visible;
            }
        }

        /* Ajustes responsivos para la paginación */
        @media (max-width: 1023px) {
            #posts-container {
                min-height: auto;
            }
        }

        /* Espaciado entre posts y paginación */
        #posts-container .mt-auto {
            margin-top: 2rem !important;
        }
    </style>
@endpush