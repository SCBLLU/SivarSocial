<div class="inline-block">
    @if($shouldShowFollowButton && $postAuthor && isset($postAuthor->name))
        <button wire:click="followUser" type="button"
            class="transition-all duration-200 font-medium rounded-full px-3 py-1.5 text-xs bg-[#3B25DD]  text-white hover:bg-[#120073]"
            title="Seguir a {{ $postAuthor->name }}">
            SEGUIR
        </button>
    @endif
</div>