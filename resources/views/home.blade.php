@extends('layouts.app')
{{-- la section inyecta el contenido dentro del yield --}}
@section('titulo')
    Publicaciones
@endsection

@section('contenido')
    <div class="h-full flex">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-full">
                <!-- Columna 1: Vacía (espaciador) -->
                <div class="hidden lg:block"></div>

                <!-- Columna 2: Posts centrados con scroll interno -->
                <div class="w-full h-full">
                    <div id="posts-container" class="h-full overflow-y-auto pr-2">
                        @include('components.new-post')
                        @component('components.listar-post', ['posts' => $posts])
                        @endcomponent
                    </div>
                </div>

                <!-- Columna 3: Perfiles a la derecha con altura igual a posts -->
                <div class="w-full h-full">
                    <div id="users-container" class="bg-white rounded-2xl shadow-lg p-4 h-full flex flex-col">
                        <h2
                            class="text-xl font-bold text-purple-700 mb-4 flex items-center gap-2 justify-center flex-shrink-0">
                            Perfiles
                            <i class="fa-solid fa-user-group w-6 h-6"></i>
                        </h2>
                        <hr class="border-gray-300 mb-4 w-[80%] mx-auto">
                        <div class="flex-1 overflow-y-auto scrollbar-purple">
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
        .scrollbar-purple::-webkit-scrollbar {
            width: 8px;
            height: 8px;
            /* para scroll horizontal si hiciera falta */
        }

        .scrollbar-purple::-webkit-scrollbar-thumb {
            background: #7e22ce;
            /* Tailwind purple-700 */
            border-radius: 6px;
        }

        .scrollbar-purple::-webkit-scrollbar-track {
            background: #ede9fe;
            /* Tailwind purple-100 */
        }

        /* Firefox */
        .scrollbar-purple {
            scrollbar-width: thin;
            scrollbar-color: #7e22ce #ede9fe;
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