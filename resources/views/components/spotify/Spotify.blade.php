<!-- Componente principal para el contenido de música, estilo Spotify -->
<div class="w-full max-w-md mx-auto" x-data="spotifyComponent()">
    <!-- Header estilo Spotify -->
    <div class="bg-black rounded-t-xl p-6 border-b border-gray-800">
        <div class="flex items-center justify-center mb-4">
            <i class="fa-brands fa-spotify text-green-500 text-3xl"></i>
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
            <input type="text" id="spotify-search"
                class="w-full pl-12 pr-12 py-3 bg-gray-900 border border-gray-700 text-white rounded-full placeholder-gray-400 focus:outline-none focus:border-white focus:bg-gray-800 transition-all duration-200"
                placeholder="¿Qué quieres compartir?" autocomplete="off">
        </div>

        <!-- Contenedor de resultados dinámico -->
        <div id="search-results"
            class="max-h-96 overflow-y-auto space-y-2 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
            
            <!-- Estado inicial: Sugerencias de géneros -->
            <div x-show="currentState === 'suggestions'" x-transition>
                @include('components.spotify.Sugerencias', [
                    'genres' => [
                        'Pop', 'Hip Hop', 'Rock', 'Reggaeton',
                        'EDM', 'R&B', 'Latin', 'Country', 'Trap'
                    ]
                ])
            </div>
            
            <!-- Estado de carga -->
            <div x-show="currentState === 'loading'" x-transition class="flex items-center justify-center py-6 bg-black/50 rounded-lg">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-500 mr-2"></div>
                <span class="text-white text-sm">Buscando en Spotify...</span>
            </div>
            
            <!-- Resultados de búsqueda -->
            <div x-show="currentState === 'results'" x-transition class="space-y-2">
                <template x-for="track in searchResults" :key="track.id">
                    <div class="spotify-track-card bg-black border border-gray-600 rounded-lg p-3 cursor-pointer hover:bg-gray-900 hover:border-gray-500 transition-all duration-200"
                         @click="selectTrack(track)">
                        <div class="flex items-center gap-3">
                            <!-- Imagen del álbum -->
                            <div class="flex-shrink-0">
                                <img :src="track.image || '/img/img.jpg'" 
                                     :alt="track.album || 'Álbum'"
                                     class="w-12 h-12 rounded object-cover">
                            </div>
                            
                            <!-- Información de la canción -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-white font-medium text-sm truncate" x-text="track.name"></h4>
                                <p class="text-gray-400 text-xs truncate" x-text="track.artist"></p>
                                <p class="text-gray-500 text-xs truncate" x-text="track.album"></p>
                            </div>
                            
                            <!-- Botón de preview si está disponible -->
                            <div class="flex-shrink-0" x-show="track.preview_url">
                                <button type="button" 
                                        @click.stop="togglePreview(track.preview_url, track.id)"
                                        class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                
                <!-- Mensaje cuando no hay resultados -->
                <div x-show="searchResults.length === 0" class="text-center py-6 text-gray-400">
                    <p class="text-sm">No se encontraron resultados</p>
                    <p class="text-xs mt-1">Intenta con otros términos de búsqueda</p>
                </div>
            </div>
            
            <!-- Estado de error -->
            <div x-show="currentState === 'error'" x-transition class="text-center py-6 text-red-400">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                            <img :src="selectedTrack?.image || '/img/img.jpg'" 
                                 :alt="selectedTrack?.album || 'Álbum'"
                                 class="w-16 h-16 rounded object-cover">
                        </div>
                        
                        <!-- Información de la canción -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-white font-medium text-sm mb-1" x-text="selectedTrack?.name"></h4>
                            <p class="text-gray-400 text-xs mb-1" x-text="selectedTrack?.artist"></p>
                            <p class="text-gray-500 text-xs" x-text="selectedTrack?.album"></p>
                        </div>
                        
                        <!-- Botón para eliminar selección -->
                        <div class="flex-shrink-0">
                            <button type="button" 
                                    @click="clearSelection()"
                                    class="text-red-400 hover:text-red-300 p-2 rounded-full hover:bg-red-400/10 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
function spotifyComponent() {
    return {
        currentState: 'suggestions', // suggestions, loading, results, error
        searchResults: [],
        selectedTrack: null,
        errorMessage: '',
        
        init() {
            // Escuchar eventos personalizados del JavaScript
            document.addEventListener('spotify:showLoader', () => {
                this.currentState = 'loading';
            });
            
            document.addEventListener('spotify:displayResults', (event) => {
                this.searchResults = event.detail.tracks || [];
                this.currentState = 'results';
            });
            
            document.addEventListener('spotify:showSuggestions', () => {
                this.currentState = 'suggestions';
                this.searchResults = [];
            });
            
            document.addEventListener('spotify:showError', (event) => {
                this.errorMessage = event.detail.message || 'Error desconocido';
                this.currentState = 'error';
            });
            
            document.addEventListener('spotify:trackSelected', (event) => {
                this.selectedTrack = event.detail.track;
                this.currentState = 'suggestions'; // Volver a sugerencias después de seleccionar
            });
            
            document.addEventListener('spotify:trackCleared', () => {
                this.selectedTrack = null;
            });
            
            document.addEventListener('spotify:clearSearch', () => {
                this.currentState = 'suggestions';
                this.searchResults = [];
            });
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