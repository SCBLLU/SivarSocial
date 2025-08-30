<div class="inline-flex items-center gap-2">
    @auth
        @if(auth()->id() !== $user->id)
            <button wire:click="toggleFollow"
                class="transition-all duration-200 font-medium rounded-full border
                            {{ $size === 'small' ? 'px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs' : ($size === 'large' ? 'px-6 py-2.5 text-sm' : 'px-4 py-2 text-xs sm:text-sm') }}
                            {{ $isFollowing ? 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' : 'bg-[#3B25DD] border-[#3B25DD] text-white hover:bg-[#120073]' }}">

                <span class="hidden sm:inline">{{ $isFollowing ? 'NO SEGUIR' : 'SEGUIR' }}</span>
                <span class="sm:hidden">{{ $isFollowing ? 'Siguiendo' : 'Seguir' }}</span>
            </button>
        @endif
    @endauth

    @if($showCount && $followersCount > 0)
        <span class="text-sm text-gray-600 font-medium">
            {{ number_format($followersCount) }} {{ $followersCount === 1 ? 'seguidor' : 'seguidores' }}
        </span>
    @endif
</div>