<!-- Template para cada canción en los resultados de búsqueda de iTunes -->
<div class="itunes-track-card relative bg-gray-800/60 rounded-xl p-3 cursor-pointer hover:bg-gray-700/60 transition-all duration-200"
    @click="selectTrack(track)" :class="selectedTrack?.trackId === track.trackId ?: ''">

    <div class="flex items-center gap-3">
        <!-- Imagen del álbum -->
        <div class="flex-shrink-0 relative">
            <img :src="track.artworkUrl100 || '/img/img.jpg'" :alt="track.collectionName || 'Álbum'"
                class="w-14 h-14 rounded-lg object-cover shadow-lg">

            <!-- Icono de play para preview -->
            <div x-show="track.previewUrl" @click.stop="togglePreview(track.previewUrl, track.trackId)"
                class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-lg opacity-0 hover:opacity-100 transition-opacity cursor-pointer">
                <div x-show="!(isPlaying && currentTrackId === track.trackId)" class="text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M8 6.82v10.36c0 .79.87 1.27 1.54.84l8.14-5.18c.62-.39.62-1.29 0-1.68L9.54 5.98C8.87 5.55 8 6.03 8 6.82z" />
                    </svg>
                </div>
                <div x-show="isPlaying && currentTrackId === track.trackId" class="text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M8 19c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2s-2 .9-2 2v10c0 1.1.9 2 2 2zm6-12v10c0 1.1.9 2 2 2s2-.9 2-2V7c0-1.1-.9-2-2-2s-2 .9-2 2z" />
                    </svg>
                </div>
            </div>


            <style>
                @keyframes w1 {

                    0%,
                    100% {
                        height: 0.5rem
                    }

                    50% {
                        height: 0.75rem
                    }
                }

                @keyframes w2 {

                    0%,
                    100% {
                        height: 0.625rem
                    }

                    50% {
                        height: 1rem
                    }
                }

                @keyframes w3 {

                    0%,
                    100% {
                        height: 0.375rem
                    }

                    50% {
                        height: 0.625rem
                    }
                }

                .animate-wave1 {
                    animation: w1 0.8s infinite ease-in-out
                }

                .animate-wave2 {
                    animation: w2 0.8s 0.1s infinite ease-in-out
                }

                .animate-wave3 {
                    animation: w3 0.8s 0.2s infinite ease-in-out
                }
            </style>
        </div>

        <!-- Información de la canción -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-1.5">
                <!-- Icono de música estilo Instagram -->
                <div x-show="isPlaying && currentTrackId === track.trackId"
                    class="flex items-end space-x-px flex-shrink-0">
                    <div class="bg-[#6366f1] w-1 h-2 rounded-full animate-wave1"></div>
                    <div class="bg-[#6366f1] w-1 h-2.5 rounded-full animate-wave2"></div>
                    <div class="bg-[#6366f1] w-1 h-1.5 rounded-full animate-wave3"></div>
                </div>

                <h4 class="font-semibold text-base truncate transition-colors duration-300"
                    :class="isPlaying && currentTrackId === track.trackId ? 'text-[#6366f1]' : 'text-white'"
                    x-text="track.trackName">
                </h4>
            </div>
            <p class="text-gray-400 text-sm truncate" x-text="track.artistName"></p>
        </div>
    </div>
</div>