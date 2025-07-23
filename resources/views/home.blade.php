@extends('layouts.app')

{{-- la section inyecta el contenido dentro del yield --}}
@section('titulo')
    <div class="items-center flex flex-row justify-center">
        <p class="text-4xl font-bold mb-4">
            Pagina principal
        </p>
    </div>
@endsection

@section('contenido')
    <div class="container mx-auto flex flex-col md:flex-row gap-8 mt-8">
        <div class="w-full md:w-2/3">
            @component('components.listar-post', ['posts' => $posts])
            @endcomponent
        </div>
        <div class="w-full md:w-1/3">
            <div class="bg-white rounded-2xl shadow-lg p-4">
                <h2 class="text-xl font-bold text-purple-700 mb-4 flex items-center gap-2 justify-center">
                    Perfiles
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path
                            d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                    </svg>
                </h2>
              <div class="max-h-80 overflow-y-auto">
                @component('components.listar-perfiles', ['users' => $users])
                @endcomponent
              </div>
            </div>
        </div>
    </div>
@endsection
