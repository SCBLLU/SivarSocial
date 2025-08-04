<!-- Track seleccionado -->
<div id="selected-track-container" class="bg-black border border-gray-600 rounded-lg p-4">
    <div class="flex items-center gap-3">
        <!-- Imagen del álbum -->
        <div class="flex-shrink-0">
            <img src="{{ $track['image'] ?? asset('img/img.jpg') }}" 
                 alt="{{ $track['album'] ?? 'Álbum' }}"
                 class="w-16 h-16 rounded object-cover">
        </div>
        
        <!-- Información de la canción -->
        <div class="flex-1 min-w-0">
            <h4 class="text-white font-medium text-sm mb-1">{{ $track['name'] }}</h4>
            <p class="text-gray-400 text-xs mb-1">{{ $track['artist'] }}</p>
            <p class="text-gray-500 text-xs">{{ $track['album'] ?? '' }}</p>
            
            <!-- Botón de preview si está disponible -->
            @if(!empty($track['preview_url']))
                <button type="button" 
                        onclick="togglePreview('{{ $track['preview_url'] }}', '{{ $track['id'] }}')"
                        class="mt-2 text-xs text-green-400 hover:text-green-300 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                    </svg>
                    Escuchar preview
                </button>
            @endif
        </div>
        
        <!-- Botón para eliminar selección -->
        <div class="flex-shrink-0">
            <button type="button" 
                    onclick="clearSelectedTrack()"
                    class="text-red-400 hover:text-red-300 p-2 rounded-full hover:bg-red-400/10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>    
</div>
