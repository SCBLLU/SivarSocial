<!-- Componente principal para el contenido de música, estilo iTunes/Apple Music -->
<div class="w-full max-w-md mx-auto" x-data="itunesComponent()">
    <!-- Header estilo Apple Music -->
    <div class="bg-black rounded-t-xl p-6 border-b border-gray-800">
        <div class="flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15.5v-9l6 4.5-6 4.5z" />
            </svg>
        </div>
        <h3 class="text-white text-xl font-bold text-center mb-2">Buscar música</h3>
        <p class="text-gray-400 text-center text-sm">Encuentra y comparte tu canción favorita</p>
    </div>

    <!-- Panel de búsqueda -->
    <div class="bg-black rounded-b-xl p-6 border-t border-gray-800">

        <!-- Input de búsqueda -->
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" id="itunes-search"
                class="w-full pl-12 pr-12 py-3 bg-gray-900 border border-gray-700 text-white rounded-full placeholder-gray-400 focus:outline-none focus:border-white focus:bg-gray-800 transition-all duration-200"
                placeholder="¿Qué quieres compartir?" autocomplete="off" @focus="handleInputFocus()"
                @blur="handleInputBlur()">

            <!-- Loading spinner -->
            <div x-show="currentState === 'loading'" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
            </div>
        </div>

        <!-- Contenedor de resultados dinámico -->
        <div id="search-results"
            class="max-h-96 overflow-y-auto space-y-2 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">


            <!-- Estado de carga -->
            <div x-show="currentState === 'loading'" x-transition
                class="flex items-center justify-center py-6 bg-black/50 rounded-lg">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white mr-2"></div>
                <span class="text-white text-sm">Buscando en iTunes...</span>
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
                <div class="bg-black border border-gray-600 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <!-- Imagen del álbum -->
                        <div class="flex-shrink-0">
                            <img :src="selectedTrack?.artworkUrlHigh || selectedTrack?.artworkUrl100 || '/img/img.jpg'"
                                :alt="selectedTrack?.collectionName || 'Álbum'" class="w-16 h-16 rounded object-cover">
                        </div>

                        <!-- Información de la canción -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-white font-medium text-sm mb-1" x-text="selectedTrack?.trackName"></h4>
                            <p class="text-gray-400 text-xs mb-1" x-text="selectedTrack?.artistName"></p>
                            <p class="text-gray-500 text-xs" x-text="selectedTrack?.collectionName"></p>
                        </div>

                        <!-- Botón para eliminar selección -->
                        <div class="flex-shrink-0">
                            <button type="button" @click="clearSelection()"
                                class="text-red-400 hover:text-red-300 p-2 rounded-full hover:bg-red-400/10 transition-colors">
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
                    this.selectedTrack = event.detail.track;
                    this.currentState = 'suggestions'; // Volver a sugerencias después de seleccionar
                });

                document.addEventListener('itunes:trackCleared', () => {
                    this.selectedTrack = null;
                });

                document.addEventListener('itunes:clearSearch', () => {
                    this.currentState = 'suggestions';
                    this.searchResults = [];
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
                window.selectTrack(track);
            },

            clearSelection() {
                window.clearSelectedTrack();
            },

            togglePreview(previewUrl, trackId) {
                window.togglePreview(previewUrl, trackId);
            }
        }
    }
</script>