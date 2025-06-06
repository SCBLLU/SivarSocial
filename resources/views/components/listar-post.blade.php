<div>
    @if ($posts->count())
        @foreach ($posts as $post)
            <div>
                <a href="{{ route('posts.show', ['user' => $post->user ? $post->user->username : 'usuario', 'post' => $post->id]) }}">
                    <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}"
                        class="h-96 object-cover rounded-lg shadow-lg">
                </a>
            </div>
        @endforeach
        <div class="my-10 text-blue-700 font-bold">
            {{ $posts->links() }}
        </div>

    @else
        <p class="text-center text-gray-400">No hay post, sigue a alguien para ver sus posts</p>
    @endif
    
</div>