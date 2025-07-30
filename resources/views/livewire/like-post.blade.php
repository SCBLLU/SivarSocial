<div class="flex items-center gap-2">
    @auth
        <button wire:click="clickLike" class="flex items-center gap-1 transition-all duration-200 hover:scale-105">
            @if ($isLiked)
                <!-- Corazón lleno cuando está liked -->
                <svg class="w-6 h-6 {{ $color === 'white' ? 'text-white' : ($color === 'red' ? 'text-red-500' : 'text-purple-600') }} fill-current"
                    viewBox="0 0 24 24">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
            @else
                <!-- Corazón vacío cuando no está liked -->
                <svg class="w-6 h-6 {{ $color === 'white' ? 'text-white border-white' : ($color === 'red' ? 'text-gray-600 border-gray-600' : 'text-purple-600 border-purple-600') }} stroke-current fill-none stroke-2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
            @endif
        </button>
    @else
        <!-- Botón deshabilitado para usuarios no logueados -->
        <div class="flex items-center gap-1 opacity-70 cursor-not-allowed">
            <svg class="w-6 h-6 {{ $color === 'white' ? 'text-white border-white' : ($color === 'red' ? 'text-gray-600 border-gray-600' : 'text-purple-600 border-purple-600') }} stroke-current fill-none stroke-2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
            </svg>
        </div>
    @endauth

    <div class="flex flex-col items-start">
        <span
            class="text-sm font-medium {{ $color === 'white' ? 'text-white' : ($color === 'red' ? 'text-gray-600' : 'text-purple-600') }}">
            {{ $likes }}
        </span>
    </div>
</div>