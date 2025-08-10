<div class="inline-flex items-center gap-2">
    @auth
        @if(auth()->id() !== $user->id)
            <button wire:click="toggleFollow"
                class="transition-all duration-200 font-medium rounded-full
                            {{ $size === 'small' ? 'px-3 py-1.5 sm:px-6 sm:py-2 text-xs sm:text-sm' : ($size === 'large' ? 'px-6 py-2.5 text-sm' : 'px-4 py-2 text-xs sm:text-sm') }}
                            {{ $isFollowing ? 'bg-white border border-black text-black hover:bg-gray-50' : 'bg-[#3B25DD] border border-black text-white hover:bg-[#120073]' }}">

                {{ $isFollowing ? 'NO SEGUIR' : 'SEGUIR' }}
            </button>
        @endif
    @endauth

    @if($showCount && $followersCount > 0)
        <span class="text-sm text-gray-600 font-medium">
            {{ number_format($followersCount) }} {{ $followersCount === 1 ? 'seguidor' : 'seguidores' }}
        </span>
    @endif
</div>