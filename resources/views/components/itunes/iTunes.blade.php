<!-- Componente principal para el contenido de música, estilo iTunes/Apple Music -->
<div class="w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-2xl mx-auto" x-data="itunesComponent()">
    <!-- Header estilo Apple Music -->
    <div class="bg-black rounded-t-xl p-4 sm:p-6 border-b border-gray-800">
        <h2 class="text-white text-lg sm:text-xl font-bold text-center mb-2">Buscar música</h2>
        <p class="text-gray-400 text-center text-xs sm:text-sm">Encuentra y comparte una canción</p>
    </div>

    <!-- Panel de búsqueda -->
    <div class="bg-black rounded-b-xl p-4 sm:p-6 border-t border-gray-800">

        <!-- Input de búsqueda -->
        <div class="relative mb-6">
            <input type="text" id="itunes-search"
                class="w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 text-white rounded-full placeholder-gray-400 focus:outline-none focus:border-white focus:bg-gray-800 transition-all duration-200 text-sm sm:text-base"
                placeholder="¿Qué quieres compartir?" autocomplete="off" @focus="handleInputFocus()"
                @blur="handleInputBlur()">
        </div>

        <!-- Contenedor de resultados dinámico -->
        <div id="search-results"
            class="max-h-96 overflow-y-auto space-y-2 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">


            <!-- Estado de carga -->
            <div x-show="currentState === 'loading'" x-transition
                class="flex items-center justify-center py-6 bg-black/50 rounded-lg">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white mr-2"></div>
                <span class="text-white text-sm">Buscando en canciones...</span>
            </div>

            <!-- Resultados de búsqueda (similar al buscador de iTunes) -->
            <div x-show="currentState === 'results'" x-transition class="space-y-4">
                <template x-for="track in searchResults" :key="track.trackId">
                    @include('components.itunes.Lista')
                </template>

                <!-- Mensaje cuando no hay resultados -->
                <div x-show="searchResults.length === 0" class="text-center py-6 text-gray-400">
                    <p class="text-sm">No se encontraron resultados</p>
                </div>
            </div>

            <!-- Estado de error -->
            <div x-show="currentState === 'error'" x-transition class="text-center py-6 text-red-400">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm" x-text="errorMessage"></p>
            </div>
        </div>

        <!-- Panel de canción seleccionada -->
        <div id="selected-track" class="mt-4">
            <div x-show="selectedTrack" x-transition>
                <!-- Canción seleccionada con diseño similar a Lista.blade.php -->
                <div class="itunes-track-card relative bg-gray-800/60 rounded-xl p-3">
                    <div class="flex items-center gap-3">
                        <!-- Imagen del álbum -->
                        <div class="flex-shrink-0 relative">
                            <img :src="selectedTrack?.artworkUrlHigh || selectedTrack?.artworkUrl100 || '/img/img.jpg'"
                                :alt="selectedTrack?.collectionName || 'Álbum'"
                                class="w-14 h-14 rounded-lg object-cover shadow-lg">

                            <!-- Icono de play para preview -->
                            <div x-show="selectedTrack?.previewUrl"
                                @click.stop="togglePreview(selectedTrack?.previewUrl, selectedTrack?.trackId)"
                                class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-lg opacity-0 hover:opacity-100 transition-opacity cursor-pointer">
                                <div x-show="!(isPlaying && currentTrackId === selectedTrack?.trackId)"
                                    class="text-white">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M8 6.82v10.36c0 .79.87 1.27 1.54.84l8.14-5.18c.62-.39.62-1.29 0-1.68L9.54 5.98C8.87 5.55 8 6.03 8 6.82z" />
                                    </svg>
                                </div>
                                <div x-show="isPlaying && currentTrackId === selectedTrack?.trackId" class="text-white">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M8 19c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2s-2 .9-2 2v10c0 1.1.9 2 2 2zm6-12v10c0 1.1.9 2 2 2s2-.9 2-2V7c0-1.1-.9-2-2-2s-2 .9-2 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la canción -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <!-- Icono de música estilo Instagram -->
                                <div x-show="isPlaying && currentTrackId === selectedTrack?.trackId"
                                    class="flex items-end space-x-px flex-shrink-0">
                                    <div class="bg-[#6366f1] w-1 h-2 rounded-full animate-wave1"></div>
                                    <div class="bg-[#6366f1] w-1 h-2.5 rounded-full animate-wave2"></div>
                                    <div class="bg-[#6366f1] w-1 h-1.5 rounded-full animate-wave3"></div>
                                </div>

                                <h4 class="font-semibold text-base truncate transition-colors duration-300"
                                    :class="isPlaying && currentTrackId === selectedTrack?.trackId ? 'text-[#6366f1]' : 'text-white'"
                                    x-text="selectedTrack?.trackName">
                                </h4>
                            </div>
                            <p class="text-gray-400 text-sm truncate" x-text="selectedTrack?.artistName"></p>
                        </div>

                        <!-- Botón para eliminar selección -->
                        <div class="flex-shrink-0">
                            <button type="button" @click="clearSelection()"
                                class="bg-gray-800 text-gray-400 hover:text-white hover:bg-gray-700 p-2 rounded-lg transition-all duration-200 flex items-center"
                                title="Eliminar selección">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function itunesComponent() {
        return {
            currentState: 'suggestions', // suggestions, loading, results, error
            searchResults: [],
            selectedTrack: null,
            errorMessage: '',
            inputFocused: false,
            isPlaying: false,
            currentTrackId: null,

            init() {
                // Escuchar eventos personalizados del JavaScript
                document.addEventListener('itunes:showLoader', () => {
                    this.currentState = 'loading';
                });

                document.addEventListener('itunes:displayResults', (event) => {
                    this.searchResults = event.detail.tracks || [];
                    this.currentState = 'results';
                });

                document.addEventListener('itunes:showSuggestions', () => {
                    this.currentState = 'suggestions';
                    this.searchResults = [];
                });

                document.addEventListener('itunes:showError', (event) => {
                    this.errorMessage = event.detail.message || 'Error desconocido';
                    this.currentState = 'error';
                });

                document.addEventListener('itunes:trackSelected', (event) => {
                    this.selectedTrack = event.detail.track || event.detail;
                    this.currentState = 'suggestions'; // Volver a sugerencias después de seleccionar
                });

                document.addEventListener('itunes:trackCleared', () => {
                    this.selectedTrack = null;
                });

                document.addEventListener('itunes:clearSearch', () => {
                    this.currentState = 'suggestions';
                    this.searchResults = [];
                });

                // Escuchar cambios en el reproductor global
                document.addEventListener('audioStateChanged', (event) => {
                    this.isPlaying = event.detail.isPlaying;
                    this.currentTrackId = event.detail.trackId;
                });
            },

            handleInputFocus() {
                this.inputFocused = true;
            },

            handleInputBlur() {
                // Pequeño delay para permitir clicks en sugerencias
                setTimeout(() => {
                    this.inputFocused = false;
                }, 150);
            },


            selectTrack(track) {
                // Usar la función global de selección de iTunes
                if (window.itunesSelectTrack) {
                    window.itunesSelectTrack(track);
                }
            },

            clearSelection() {
                // Usar la función global para limpiar selección
                if (window.itunesClearSelection) {
                    window.itunesClearSelection();
                }
            },

            togglePreview(previewUrl, trackId) {
                // Usar la función global de reproducción de audio
                if (window.toggleAudioPreview) {
                    window.toggleAudioPreview(previewUrl, trackId, 'itunes');
                }
            }
        }
    }
</script>

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