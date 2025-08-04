<!-- Tarjeta individual de track de Spotify -->
<div class="spotify-track-card bg-black border border-gray-600 rounded-lg p-3 cursor-pointer hover:bg-gray-900 hover:border-gray-500 transition-all duration-200"
     onclick="selectTrack({{ json_encode($track) }})" data-track-id="{{ $track['id'] }}">
    <div class="flex items-center gap-3">
        <!-- Imagen del álbum -->
        <div class="flex-shrink-0">
            <img src="{{ $track['image'] ?? asset('img/img.jpg') }}" 
                 alt="{{ $track['album'] ?? 'Álbum' }}"
                 class="w-12 h-12 rounded object-cover">
        </div>
        
        <!-- Información de la canción -->
        <div class="flex-1 min-w-0">
            <h4 class="text-white font-medium text-sm truncate">{{ $track['name'] }}</h4>
            <p class="text-gray-400 text-xs truncate">{{ $track['artist'] }}</p>
        </div>
        
        <!-- Botón de preview si está disponible -->
        @if(!empty($track['preview_url']))
            <div class="flex-shrink-0">
                <button type="button" 
                        onclick="event.stopPropagation(); togglePreview('{{ $track['preview_url'] }}', '{{ $track['id'] }}')"
                        class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>
