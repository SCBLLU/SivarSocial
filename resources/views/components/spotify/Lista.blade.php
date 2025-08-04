<div class="spotify-track-card bg-black/60 rounded-xl p-2 cursor-pointer" @click="selectTrack(track)">
    <div class="flex items-center gap-3">
        <!-- Imagen del álbum -->
        <div class="flex-shrink-0">
            <img :src="track.image || '/img/img.jpg'" :alt="track.album || 'Álbum'"
                class="w-12 h-12 rounded-lg object-cover shadow">
        </div>

        <!-- Información de la canción -->
        <div class="flex-1 min-w-0">
            <h4 class="text-white font-semibold text-base truncate" x-text="track.name"></h4>
            <p class="text-gray-300 text-xs truncate" x-text="track.artist"></p>
        </div>
    </div>
</div>