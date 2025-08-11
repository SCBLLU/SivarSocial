@extends('layouts.app')

@push('scripts')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@section('titulo')
    <div class="flex items-center justify-center relative w-full">
        <a href="{{ route('posts.index', $user->username) }}"
            class="absolute left-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-white text-2xl font-bold mx-auto">Siguiendo</h1>
    </div>
@endsection

@section('contenido')
    {{-- Información del usuario --}}
    <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-md border border-gray-200 w-full max-w-2xl mx-auto p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
        <div class="flex items-center gap-3 sm:gap-4">
            {{-- Foto de perfil --}}
            <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full border-2 border-indigo-600 overflow-hidden flex-shrink-0">
                @if($user->imagen_url)
                    <img src="{{ $user->imagen_url }}" alt="Foto de perfil" class="w-full h-full object-cover">
                @else
                    <svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                @endif
            </div>
            
            {{-- Info del usuario --}}
            <div class="flex-1 min-w-0">
                <h2 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 truncate">{{ $user->name }}</h2>
                <p class="text-gray-600 font-semibold text-sm">{{ '@' . $user->username }}</p>
                <p class="text-gray-500 text-xs sm:text-sm mt-1">
                    Sigue a <span class="font-semibold">{{ number_format($totalFollowing) }}</span> usuarios
                </p>
            </div>
        </div>
        
        {{-- Navegación entre seguidores y seguidos optimizada para móvil --}}
        <div class="flex mt-3 sm:mt-4 border-t pt-3 sm:pt-4">
            <a href="{{ route('users.followers', $user->username) }}" 
               class="flex-1 text-center py-2 px-2 sm:px-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                <span class="hidden sm:inline">Seguidores</span>
                <span class="sm:hidden">Seguidores</span>
            </a>
            <a href="{{ route('users.following', $user->username) }}" 
               class="flex-1 text-center py-2 px-2 sm:px-4 text-sm font-medium border-b-2 border-blue-500 text-blue-600">
                <span class="hidden sm:inline">Siguiendo</span>
                <span class="sm:hidden">Siguiendo</span>
            </a>
        </div>
    </div>

    {{-- Lista de usuarios seguidos --}}
    <section class="container mx-auto px-4 sm:px-6 lg:px-8">
        <livewire:followers-list :user="$user" type="following" />
    </section>
@endsection
