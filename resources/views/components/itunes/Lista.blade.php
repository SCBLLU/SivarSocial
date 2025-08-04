<!-- Template para cada canción en los resultados de búsqueda de iTunes -->
<div class="itunes-track-card bg-gray-800/60 rounded-xl p-3 cursor-pointer hover:bg-gray-700/60 transition-all duration-200"
    @click="selectTrack(track)"
    :class="selectedTrack?.trackId === track.trackId ? 'ring-2 ring-blue-500 bg-blue-500/20' : ''">
    <div class="flex items-center gap-3">
        <!-- Imagen del álbum -->
        <div class="flex-shrink-0 relative">
            <img :src="track.artworkUrl100 || '/img/img.jpg'" :alt="track.collectionName || 'Álbum'"
                class="w-14 h-14 rounded-lg object-cover shadow-lg">

            <!-- Icono de play para preview -->
            <div x-show="track.previewUrl"
                class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-lg opacity-0 hover:opacity-100 transition-opacity">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <!-- Información de la canción -->
        <div class="flex-1 min-w-0">
            <h4 class="text-white font-semibold text-base truncate" x-text="track.trackName"></h4>
            <p class="text-gray-300 text-sm truncate" x-text="track.artistName"></p>
        </div>

        <!-- Botón de preview -->
        <div x-show="track.previewUrl" class="flex-shrink-0">
            <button @click.stop="togglePreview(track.previewUrl, track.trackId)"
                class="w-10 h-10 bg-gray-600 hover:bg-gray-700 rounded-full flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</div>