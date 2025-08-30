<div class="flex items-center gap-2">
    <a href="{{ route('posts.show', ['user' => $post->user ? $post->user->username : 'usuario', 'post' => $post->id]) }}"
        class="flex items-center gap-1 transition-all duration-200 hover:scale-105">
        <svg class="w-6 h-6 {{ $color === 'white' ? 'text-white' : 'text-gray-600' }}" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <span class="text-sm font-medium {{ $color === 'white' ? 'text-white' : 'text-gray-600' }}">
            {{ $comments }}
        </span>
    </a>
</div>