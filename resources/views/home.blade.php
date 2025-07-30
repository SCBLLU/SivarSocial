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
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path
                                    d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                            </svg>
                        </h2>
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