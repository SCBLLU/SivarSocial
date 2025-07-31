@props(['post', 'user'])

<div class="bg-white rounded-2xl shadow-lg w-full max-w-md flex flex-col overflow-hidden" 
     style="background: linear-gradient(135deg, {{ $post->dominant_color ?? '#1DB954' }}15 0%, {{ $post->dominant_color ?? '#1DB954' }}05 100%);">
    
    <!-- Header: perfil y username -->
    <div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
        <a href="{{ route('posts.index', $user->username) }}" class="flex items-center">
            @if($user->imagen)
                <img src="{{ asset('perfiles') . '/' . $user->imagen }}" 
                     alt="Imagen de perfil de {{ $user->username }}" 
                     class="rounded-full w-8 h-8 object-cover">
            @else
                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            @endif
        </a>
        <div class="ml-3 flex-1">
            <a href="{{ route('posts.index', $user->username) }}" class="font-semibold text-gray-900 hover:underline">{{ $user->name }}</a>
            <p class="text-sm text-gray-500">{{ '@' . $user->username }}</p>
        </div>
        
        <!-- Icono de música -->
        <div class="flex items-center gap-1 text-{{ $post->dominant_color ?? 'green' }}-600">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs font-medium">Música</span>
        </div>
    </div>

    <!-- Portada del álbum y controles de música -->
    <div class="w-full flex justify-center" style="background: linear-gradient(135deg, {{ $post->dominant_color ?? '#1DB954' }}20 0%, {{ $post->dominant_color ?? '#1DB954' }}40 100%);">
        <div class="relative w-full max-w-sm p-6">
            <!-- Portada del álbum -->
            <div class="relative mb-4">
                <img src="{{ $post->spotify_album_image }}" 
                     alt="{{ $post->spotify_album_name }}" 
                     class="w-full aspect-square rounded-2xl shadow-2xl object-cover">
                
                <!-- Botón de play superpuesto -->
                @if($post->spotify_preview_url)
                    <button onclick="toggleMusicPreview('{{ $post->spotify_preview_url }}', '{{ $post->id }}', this)" 
                            class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-2xl opacity-0 hover:opacity-100 transition-all duration-300 group">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-gray-800 play-icon-{{ $post->id }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                            </svg>
                            <svg class="w-8 h-8 text-gray-800 pause-icon-{{ $post->id }} hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </button>
                @endif
            </div>
            
            <!-- Información de la canción -->
            <div class="text-center text-white">
                <h3 class="font-bold text-lg mb-1 line-clamp-2">{{ $post->spotify_track_name }}</h3>
                <p class="text-white/80 text-sm mb-1">{{ $post->spotify_artist_name }}</p>
                <p class="text-white/60 text-xs">{{ $post->spotify_album_name }}</p>
            </div>
            
            <!-- Enlace a Spotify -->
            @if($post->spotify_external_url)
                <div class="mt-4 text-center">
                    <a href="{{ $post->spotify_external_url }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm font-medium transition-all backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.43 0-.8-.17-1.14-.5-.35-.34-.53-.74-.53-1.21 0-.97.69-2.14 2.06-3.52l.01-.01c.59-.59 1.15-1.09 1.69-1.5.54-.41 1.03-.77 1.45-1.07.07-.05.14-.1.2-.15v2.29c0 .42-.16.78-.47 1.08-.31.3-.67.45-1.09.45-.42 0-.78-.15-1.08-.45-.3-.3-.45-.66-.45-1.08 0-.42.15-.78.45-1.08.3-.3.66-.45 1.08-.45.14 0 .28.02.41.07l.01-2.28c-.06-.05-.13-.1-.2-.15-.42-.3-.91-.66-1.45-1.07-.54-.41-1.1-.91-1.69-1.5l-.01-.01C6.29 7.96 5.6 6.79 5.6 5.82c0-.47.18-.87.53-1.21.34-.33.74-.5 1.14-.5.97 0 2.14.69 3.52 2.06.59.59 1.09 1.15 1.5 1.69.41.54.77 1.03 1.07 1.45.05.07.1.14.15.2h2.29c.42 0 .78.16 1.08.47.3.31.45.67.45 1.09 0 .42-.15.78-.45 1.08-.3.3-.66.45-1.08.45-.42 0-.78-.15-1.08-.45-.3-.3-.45-.66-.45-1.08 0-.42.15-.78.45-1.08.3-.3.66-.45 1.08-.45.14 0 .28.02.41.07h2.28c.05-.06.1-.13.15-.2.3-.42.66-.91 1.07-1.45.41-.54.91-1.1 1.5-1.69C19.31 6.29 20.48 5.6 21.45 5.6c.47 0 .87.18 1.21.53.33.34.5.74.5 1.14 0 .97-.69 2.14-2.06 3.52-.59.59-1.15 1.09-1.69 1.5-.54.41-1.03.77-1.45 1.07-.07.05-.14.1-.2.15v2.29c0 .42-.16.78-.47 1.08-.31.3-.67.45-1.09.45z"/>
                        </svg>
                        Abrir en Spotify
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Detalles del post -->
    <div class="w-full px-4 py-3">
        <!-- Título y descripción -->
        <div class="mb-3">
            <h4 class="font-semibold text-gray-900 mb-1">{{ $post->titulo }}</h4>
            <p class="text-gray-600 text-sm">{{ $post->descripcion }}</p>
        </div>

        <!-- Fecha -->
        <p class="text-gray-400 text-xs">{{ $post->created_at->diffForHumans() }}</p>

        <!-- Acciones (likes y comentarios) -->
        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
            <div class="flex items-center space-x-6">
                @auth
                    @if($post->checkLike(auth()->user()))
                        <form method="POST" action="{{ route('posts.likes.destroy', $post) }}">
                            @method('DELETE')
                            @csrf
                            <div class="my-4">
                                <button type="submit" class="flex items-center space-x-2 text-red-500 hover:text-red-600 transition-colors">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                        <path d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5 2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z"/>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $post->likes->count() }} Likes</span>
                                </button>
                            </div>
                        </form>
                    @else
                        <form method="POST" action="{{ route('posts.likes.store', $post) }}">
                            @csrf
                            <div class="my-4">
                                <button type="submit" class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $post->likes->count() }} Likes</span>
                                </button>
                            </div>
                        </form>
                    @endif
                @endauth

                @guest
                    <div class="my-4">
                        <a href="{{ route('login') }}" class="flex items-center space-x-2 text-gray-500 hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $post->likes->count() }} Likes</span>
                        </a>
                    </div>
                @endguest
            </div>

            <!-- Comentarios -->
            <div class="flex items-center space-x-2 text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <span class="text-sm font-medium">{{ $post->comentarios->count() }}</span>
            </div>
        </div>
    </div>
</div>

<script>
// Audio global para reproducción de música
let currentMusicAudio = null;
let currentPostId = null;

window.toggleMusicPreview = function(previewUrl, postId, button) {
    const playIcon = document.querySelector(`.play-icon-${postId}`);
    const pauseIcon = document.querySelector(`.pause-icon-${postId}`);
    
    // Si hay música reproduciéndose
    if (currentMusicAudio && !currentMusicAudio.paused) {
        // Si es la misma canción, pausar
        if (currentPostId === postId) {
            currentMusicAudio.pause();
            currentMusicAudio = null;
            currentPostId = null;
            
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            return;
        } else {
            // Pausar canción anterior
            currentMusicAudio.pause();
            // Resetear iconos de la canción anterior
            if (currentPostId) {
                document.querySelector(`.play-icon-${currentPostId}`)?.classList.remove('hidden');
                document.querySelector(`.pause-icon-${currentPostId}`)?.classList.add('hidden');
            }
        }
    }
    
    // Reproducir nueva canción
    currentMusicAudio = new Audio(previewUrl);
    currentPostId = postId;
    
    currentMusicAudio.play().then(() => {
        playIcon.classList.add('hidden');
        pauseIcon.classList.remove('hidden');
    }).catch(error => {
        console.error('Error al reproducir:', error);
    });
    
    // Cuando termine la canción
    currentMusicAudio.addEventListener('ended', () => {
        playIcon.classList.remove('hidden');
        pauseIcon.classList.add('hidden');
        currentMusicAudio = null;
        currentPostId = null;
    });
};
</script>
