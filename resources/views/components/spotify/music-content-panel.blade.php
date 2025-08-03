<!-- Componente individual para el contenido de música, estilo Spotify -->
<div class="w-full max-w-md mx-auto">
    <!-- Header estilo Spotify -->
    <div class="bg-black rounded-t-xl p-6 border-b border-gray-800">
        <div class="flex items-center justify-center mb-4">
            <i class="fa-brands fa-spotify text-green-500 text-3xl"></i>
        </div>
        <h3 class="text-white text-xl font-bold text-center mb-2">Buscar música</h3>
        <p class="text-gray-400 text-center text-sm">Encuentra y comparte tu canción favorita</p>
    </div>

    <!-- Panel de búsqueda estilo Spotify -->
    <div class="bg-black rounded-b-xl p-6 border-t border-gray-800">

        <!-- Campo de búsqueda -->
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

        <!-- Estadísticas de búsqueda -->
        <div id="search-stats" class="text-center mb-4 text-gray-400 text-sm hidden">
            <span id="results-count">0</span> resultados encontrados
        </div>

        <!-- Contenedor de resultados -->
        <div id="search-results"
            class="max-h-96 overflow-y-auto space-y-2 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
            <!-- Los resultados se cargarán aquí dinámicamente -->
        </div>

        <!-- Panel de canción seleccionada -->
        <div id="selected-track" class="mt-4">
            <!-- La canción seleccionada se mostrará aquí -->
        </div>

        <!-- Indicadores de estado -->
        <div id="spotify-status" class="mt-4 text-center hidden">
            <div class="flex items-center justify-center space-x-2">
                <div id="status-icon" class="w-4 h-4"></div>
                <span id="status-text" class="text-sm text-gray-400"></span>
            </div>
        </div>

        <!-- Géneros populares -->
        <div id="quick-suggestions" class="mt-6 space-y-6">
            <div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" id="genre-grid">
                    <!-- Se cargarán dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        // Script optimizado para el componente de música - usa el módulo centralizado
        document.addEventListener('DOMContentLoaded', function () {
            initializeMusicComponent();
        });

        function initializeMusicComponent() {
            const searchInput = document.getElementById('spotify-search');
            const searchResults = document.getElementById('search-results');
            const quickSuggestions = document.getElementById('quick-suggestions');

            if (!searchInput) {
                console.warn('Campo de búsqueda no encontrado');
                return;
            }

            // Cargar géneros populares
            loadGenres();

            // Event listeners optimizados
            let searchTimeout;
            searchInput.addEventListener('input', function (e) {
                const query = e.target.value.trim();

                clearTimeout(searchTimeout);

                if (query.length === 0) {
                    showQuickSuggestions();
                    hideSearchStats();
                    return;
                }

                if (query.length < 2) return;

                // Usar el módulo centralizado para la búsqueda
                searchTimeout = setTimeout(() => {
                    if (window.spotifyModule) {
                        window.spotifyModule.searchSpotify(query, 'search-results');
                    } else {
                        // Fallback si el módulo no está cargado
                        performSpotifySearchFallback(query);
                    }
                }, 300);
            });

            searchInput.addEventListener('focus', function (e) {
                if (!e.target.value.trim()) {
                    showQuickSuggestions();
                }
            });
        }

        // Fallback temporal para búsqueda (hasta que el módulo esté completamente implementado)
        async function performSpotifySearchFallback(query) {
            const searchResults = document.getElementById('search-results');

            // Mostrar carga
            searchResults.innerHTML = `
                        <div class="flex items-center justify-center py-12 bg-gray-900 rounded-lg border border-gray-800">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white mr-3"></div>
                            <span class="text-white">Buscando canciones...</span>
                        </div>
                    `;

            try {
                const response = await fetch(`/spotify/search?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok && data.tracks && data.tracks.items) {
                    displaySearchResultsOptimized(data.tracks.items);
                    showSearchStats(data.tracks.items.length);
                } else {
                    showNoResults();
                }
            } catch (error) {
                console.error('Error en búsqueda:', error);
                showSearchError();
            }
        }

        // Mostrar resultados optimizado - usa funciones del módulo cuando sea posible
        function displaySearchResultsOptimized(tracks) {
            // Si el módulo centralizado está disponible, usarlo
            if (window.spotifyModule) {
                return window.spotifyModule.displaySearchResults(tracks, 'search-results');
            }

            // Fallback básico
            const searchResults = document.getElementById('search-results');
            const quickSuggestions = document.getElementById('quick-suggestions');

            quickSuggestions.classList.add('hidden');

            if (!tracks || tracks.length === 0) {
                showNoResults();
                return;
            }

            searchResults.innerHTML = tracks.map(track => `
                        <div class="spotify-track-card bg-gray-900 hover:bg-gray-800 rounded-lg p-4 cursor-pointer transition-colors duration-200 border border-gray-800 hover:border-gray-700" 
                             onclick="selectTrackFromComponent(${JSON.stringify(track).replace(/"/g, '&quot;')})">
                            <div class="flex items-center gap-4">
                                <img src="${track.image || '/img/usuario.svg'}" 
                                     alt="${track.name}" 
                                     class="w-12 h-12 rounded object-cover">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-white truncate">${track.name}</h4>
                                    <p class="text-gray-400 text-sm truncate">${track.artist}</p>
                                    ${track.album ? `<p class="text-gray-500 text-xs truncate">${track.album}</p>` : ''}
                                </div>
                                <div class="flex items-center gap-3">
                                    ${track.duration_ms ? `<span class="text-gray-400 text-xs">${formatDurationBasic(track.duration_ms)}</span>` : ''}
                                    ${track.preview_url ? `
                                        <button class="play-button bg-white hover:bg-gray-200 text-black w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-200" 
                                                onclick="togglePreviewFromComponent('${track.preview_url}', this); event.stopPropagation();" 
                                                type="button">
                                            <svg class="w-4 h-4 play-icon" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                            </svg>
                                            <svg class="w-4 h-4 pause-icon hidden" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `).join('');
        }

        // Funciones adapter que llaman al módulo centralizado o fallback
        window.selectTrackFromComponent = function (track) {
            if (window.spotifyModule) {
                return window.spotifyModule.selectTrack(track);
            } else if (typeof selectTrack === 'function') {
                return selectTrack(track);
            } else {
                console.error('No se encontró función de selección de track');
            }
        };

        window.togglePreviewFromComponent = function (previewUrl, button) {
            if (window.spotifyModule) {
                return window.spotifyModule.togglePreview(previewUrl, button);
            } else if (typeof togglePreview === 'function') {
                return togglePreview(previewUrl, button);
            } else {
                console.error('No se encontró función de reproducción');
            }
        };

        // Cargar géneros populares
        function loadGenres() {
            const genreGrid = document.getElementById('genre-grid');
            if (!genreGrid) return;

            const genres = [
                'Pop', 'Rock', 'Reggaeton', 'Salsa', 'Bachata',
                'Electrónica', 'Jazz', 'Hip Hop', 'Cumbia', 'Merengue'
            ];

            genreGrid.innerHTML = genres.map(genre => `
                        <button onclick="searchByGenreFromComponent('${genre}')" 
                                class="genre-button px-4 py-3 text-sm rounded-lg bg-gray-800 hover:bg-gray-700 text-white font-medium transition-colors duration-200 border border-gray-700 hover:border-gray-600">
                            ${genre}
                        </button>
                    `).join('');
        }

        // Búsqueda por género
        window.searchByGenreFromComponent = function (genre) {
            const searchInput = document.getElementById('spotify-search');
            if (searchInput) {
                searchInput.value = genre;

                if (window.spotifyModule) {
                    window.spotifyModule.searchSpotify(genre, 'search-results');
                } else {
                    performSpotifySearchFallback(genre);
                }
            }
        };

        // Mostrar sugerencias rápidas
        function showQuickSuggestions() {
            const searchResults = document.getElementById('search-results');
            const quickSuggestions = document.getElementById('quick-suggestions');

            if (searchResults) searchResults.innerHTML = '';
            if (quickSuggestions) quickSuggestions.classList.remove('hidden');
        }

        // Estados de UI
        function showNoResults() {
            const searchResults = document.getElementById('search-results');
            if (!searchResults) return;

            searchResults.innerHTML = `
                        <div class="text-center py-12 bg-gray-900 rounded-lg border border-gray-800">
                            <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.49.901-6.092 2.372L5 16.5v-2.5A7.5 7.5 0 0112 6.5c1.593 0 3.043.486 4.258 1.317"/>
                            </svg>
                            <p class="text-white font-medium mb-2">No se encontraron resultados</p>
                            <p class="text-gray-400 text-sm">Intenta con otros términos de búsqueda</p>
                        </div>
                    `;
            showSearchStats(0);
        }

        function showSearchError() {
            const searchResults = document.getElementById('search-results');
            if (!searchResults) return;

            searchResults.innerHTML = `
                        <div class="text-center py-12 bg-gray-900 border border-gray-800 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-white font-medium mb-2">Error en la búsqueda</p>
                            <p class="text-gray-400 text-sm">Intenta de nuevo en unos momentos</p>
                        </div>
                    `;
        }

        function showSearchStats(count) {
            const statsElement = document.getElementById('search-stats');
            const countElement = document.getElementById('results-count');

            if (countElement) countElement.textContent = count;
            if (statsElement) statsElement.classList.remove('hidden');
        }

        function hideSearchStats() {
            const statsElement = document.getElementById('search-stats');
            if (statsElement) statsElement.classList.add('hidden');
        }

        // Formatear duración básico (fallback)
        function formatDurationBasic(ms) {
            if (window.spotifyModule) {
                return window.spotifyModule.formatDuration(ms);
            }

            const minutes = Math.floor(ms / 60000);
            const seconds = ((ms % 60000) / 1000).toFixed(0);
            return `${minutes}:${seconds.padStart(2, '0')}`;
        }
    </script>
@endpush