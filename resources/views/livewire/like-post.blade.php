<div class="flex items-center gap-2">
    @auth
        <!-- Botón de like (ícono de corazón) -->
        <button wire:click="clickLike" wire:loading.attr="disabled"
            class="flex items-center gap-1 transition-all duration-200 hover:scale-105 disabled:opacity-50">
            <div wire:loading wire:target="clickLike" class="w-4 h-4 mr-1">
                <svg class="animate-spin w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
            <div wire:loading.remove wire:target="clickLike" class="relative">
                @if ($isLiked)
                    <!-- Corazón lleno (me gusta dado) -->
                    <svg class="w-6 h-6 text-red-500 fill-current transform transition-transform duration-150 hover:scale-110 active:scale-95"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                @else
                    <!-- Corazón vacío (sin me gusta) -->
                    <svg class="w-6 h-6 {{ $color === 'white' ? 'text-white' : 'text-gray-900' }} stroke-current fill-none stroke-2 transform transition-transform duration-150 hover:scale-110 active:scale-95"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                @endif
            </div>
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

    <!-- Contador de likes (para ver modal) -->
    <div class="flex flex-col items-start">
        @if($likes > 0)
            @auth
                <button onclick="openLikesModal({{ $post->id }})"
                    class="text-sm font-semibold {{ $color === 'white' ? 'text-white hover:text-gray-200' : ($color === 'red' ? 'text-gray-600 hover:text-gray-800' : 'text-gray-900 hover:text-gray-700') }} transition-colors duration-150 hover:underline focus:outline-none likes-counter-mobile px-1 py-1 rounded">
                    <span class="border-b border-transparent hover:border-current">
                        {{ number_format($likes) }}
                    </span>
                </button>
            @else
                <span
                    class="text-sm font-medium cursor-default {{ $color === 'white' ? 'text-white' : ($color === 'red' ? 'text-gray-600' : 'text-gray-600') }} px-1 py-1">
                    {{ number_format($likes) }}
                </span>
            @endauth
        @else
            <div
                class="text-sm font-medium {{ $color === 'white' ? 'text-white' : ($color === 'red' ? 'text-gray-600' : 'text-gray-600') }} px-1 py-1">
                0
            </div>
        @endif
    </div>
</div>