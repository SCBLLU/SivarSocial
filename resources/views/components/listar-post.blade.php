<div class="flex flex-col items-center w-full">
    @if ($posts->count())
        @foreach ($posts as $post)
            <div class="bg-white rounded-2xl shadow-lg mb-10 w-full max-w-md flex flex-col items-center">
                <!-- Header: perfil y username -->
                <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
                    <a href="{{ $post->user ? route('posts.index', $post->user) : '#' }}" class="flex items-center group">
                        <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/default-avatar.png') }}" alt="Avatar de {{ $post->user ? $post->user->username : 'usuario' }}" class="w-10 h-10 rounded-full object-cover border-2 border-black group-hover:border-purple-700 transition">
                        <span class="ml-3 font-bold text-black group-hover:underline">
                            {{ $post->user ? ($post->user->name ?? $post->user->username) : 'usuario' }}
                        </span>
                    </a>
                </div>
                <!-- Imagen del post -->
                <a href="{{ route('posts.show', ['user' => $post->user ? $post->user->username : 'usuario', 'post' => $post->id]) }}" class="w-full flex justify-center bg-black rounded-b-none rounded-t-none">
                    <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}" class="object-cover w-full max-h-96 aspect-square rounded-none">
                </a>
                <!-- Detalles debajo de la imagen -->
                <div class="w-full px-4 py-2 flex items-center justify-between">
                    <div class="flex flex-col flex-1">
                        <span class="font-semibold text-black">{{ $post->titulo }}</span>
                        <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="ml-2 flex items-center">
                        @auth
                            <livewire:like-post :post="$post" color="purple" />
                        @endauth
                    </div>
                </div>
                <!-- Descripción -->
                <div class="w-full px-4 pb-4">
                    <p class="text-black">{{ $post->descripcion }}</p>
                </div>
            </div>
        @endforeach
        <div class="my-10 text-black font-bold">
            {{ $posts->links() }}
        </div>
    @else
        <p class="text-center text-gray-500">No hay post, sigue a alguien para ver sus posts</p>
    @endif
</div>