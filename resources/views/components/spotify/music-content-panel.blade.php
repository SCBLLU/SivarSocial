<!-- Componente individual para el contenido de música -->
<div class="w-full">
    <!-- Header con diseño moderno -->
    <div
        class="bg-gradient-to-br from-gray-900 via-black to-purple-900 rounded-t-2xl p-6 mb-0 relative overflow-hidden">
        <!-- Fondo decorativo -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=" 60" height="60" viewBox="0 0 60 60"
            xmlns="http://www.w3.org/2000/svg" %3E%3Cg fill="none" fill-rule="evenodd" %3E%3Cg fill="%23ffffff"
            fill-opacity="0.05" %3E%3Ccircle cx="7" cy="7" r="7" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>

        <!-- Contenido del header -->
        <div class="relative z-10">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-green-500 p-3 rounded-full shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17V9l4 2-4 2v4z" />
                    </svg>
                </div>
            </div>

            <h3 class="text-white text-2xl font-bold text-center mb-2">Buscar en Spotify</h3>
            <p class="text-gray-300 text-center text-sm">Encuentra y comparte tu música favorita</p>
        </div>
    </div>

    <!-- Panel de búsqueda principal -->
    <div class="bg-black border-2 border-gray-800 rounded-b-2xl p-6 shadow-2xl">
        <!-- Campo de búsqueda con diseño mejorado -->
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <input type="text" id="spotify-search"
                class="w-full pl-12 pr-4 py-4 bg-gray-900 border-2 border-gray-700 text-white rounded-2xl placeholder-gray-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/30 transition-all duration-300 text-lg"
                placeholder="Busca tu canción favorita..." autocomplete="off">

            <!-- Botón de limpiar búsqueda -->
            <button type="button" id="clear-search"
                class="absolute inset-y-0 right-0 pr-4 items-center text-gray-400 hover:text-white transition-colors duration-200 hidden"
                style="display: none;">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Estadísticas de búsqueda -->
        <div id="search-stats" class="text-center mb-4 text-gray-400 text-sm hidden">
            <span id="results-count">0</span> resultados encontrados
        </div>

        <!-- Contenedor de resultados con scroll personalizado -->
        <div id="search-results"
            class="max-h-96 overflow-y-auto space-y-3 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
            <!-- Los resultados se cargarán aquí dinámicamente -->
        </div>

        <!-- Panel de canción seleccionada -->
        <div id="selected-track" class="mt-6">
            <!-- La canción seleccionada se mostrará aquí -->
        </div>

        <!-- Indicadores de estado -->
        <div id="spotify-status" class="mt-4 text-center hidden">
            <div class="flex items-center justify-center space-x-2">
                <div id="status-icon" class="w-4 h-4"></div>
                <span id="status-text" class="text-sm"></span>
            </div>
        </div>

        <!-- Sugerencias rápidas (visible por defecto) -->
        <div id="quick-suggestions" class="mt-6 space-y-4">
            <!-- Búsquedas recientes -->
            <div id="recent-searches-section" class="hidden">
                <h4 class="text-white font-medium mb-3 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    Búsquedas recientes
                </h4>
                <div id="recent-searches-list" class="flex flex-wrap gap-2">
                    <!-- Se cargarán dinámicamente -->
                </div>
            </div>

            <!-- Géneros populares -->
            <div>
                <h4 class="text-white font-medium mb-3 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7z"
                            clip-rule="evenodd" />
                    </svg>
                    Explorar por género
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2" id="genre-grid">
                    <!-- Se cargarán dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Estilos específicos para el componente de música */
        .music-content-panel {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Campo de búsqueda mejorado */
        #spotify-search {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        #spotify-search:focus {
            box-shadow: 0 4px 25px rgba(34, 197, 94, 0.2);
            transform: translateY(-1px);
        }

        /* Resultados de búsqueda con animaciones */
        .search-result-item {
            opacity: 0;
            transform: translateY(10px);
            animation: slideIn 0.3s ease forwards;
        }

        .search-result-item:nth-child(1) {
            animation-delay: 0ms;
        }

        .search-result-item:nth-child(2) {
            animation-delay: 50ms;
        }

        .search-result-item:nth-child(3) {
            animation-delay: 100ms;
        }

        .search-result-item:nth-child(4) {
            animation-delay: 150ms;
        }

        .search-result-item:nth-child(5) {
            animation-delay: 200ms;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tarjetas de resultados mejoradas */
        .spotify-track-card {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            border: 1px solid #374151;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .spotify-track-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .spotify-track-card:hover::before {
            left: 100%;
        }

        .spotify-track-card:hover {
            background: linear-gradient(135deg, #2d2d2d 0%, #3a3a3a 100%);
            border-color: #6b7280;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        /* Botones de género mejorados */
        .genre-button {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            border: 1px solid #6b7280;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .genre-button:hover {
            background: linear-gradient(135deg, #4b5563 0%, #6b7280 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(75, 85, 99, 0.3);
        }

        .genre-button:active {
            transform: scale(0.98);
        }

        /* Canción seleccionada */
        .selected-track-card {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            border: 2px solid #10b981;
            animation: selectedPulse 2s infinite;
        }

        @keyframes selectedPulse {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
            }

            50% {
                box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
            }
        }

        /* Scrollbar personalizado */
        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #374151;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #6b7280;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Indicadores de estado */
        .status-success {
            color: #10b981;
        }

        .status-error {
            color: #ef4444;
        }

        .status-warning {
            color: #f59e0b;
        }

        .status-info {
            color: #3b82f6;
        }

        /* Animaciones de carga */
        .loading-spinner {
            border: 2px solid #374151;
            border-top: 2px solid #10b981;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .music-content-panel {
                margin: 0 -1rem;
            }

            #genre-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            #spotify-search {
                font-size: 16px;
                /* Evita zoom en iOS */
            }
        }

        @media (max-width: 480px) {
            #search-results {
                max-height: 250px;
            }

            .spotify-track-card {
                padding: 0.75rem;
            }
        }

        /* Estados de focus mejorados para accesibilidad */
        .genre-button:focus,
        #spotify-search:focus,
        .spotify-track-card:focus {
            outline: 2px solid #10b981;
            outline-offset: 2px;
        }

        /* Animación de entrada del componente */
        .music-content-panel {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Script específico para el componente de música
        document.addEventListener('DOMContentLoaded', function () {
            initializeMusicComponent();
        });

        function initializeMusicComponent() {
            const searchInput = document.getElementById('spotify-search');
            const clearButton = document.getElementById('clear-search');
            const searchResults = document.getElementById('search-results');
            const quickSuggestions = document.getElementById('quick-suggestions');

            if (!searchInput) return;

            // Inicializar sugerencias
            loadGenres();
            loadRecentSearches();

            // Event listeners
            searchInput.addEventListener('input', handleSearchInput);
            searchInput.addEventListener('focus', handleSearchFocus);
            searchInput.addEventListener('blur', handleSearchBlur);

            if (clearButton) {
                clearButton.addEventListener('click', clearSearch);
            }

            // Mostrar/ocultar botón de limpiar
            searchInput.addEventListener('input', function () {
                const clearBtn = document.getElementById('clear-search');
                if (clearBtn) {
                    if (this.value.length === 0) {
                        clearBtn.style.display = 'none';
                    } else {
                        clearBtn.style.display = 'flex';
                    }
                }
            });
        }

        // Manejar entrada de búsqueda con debounce mejorado
        let searchTimeout;
        function handleSearchInput(e) {
            const query = e.target.value.trim();

            clearTimeout(searchTimeout);

            if (query.length === 0) {
                showQuickSuggestions();
                hideSearchStats();
                return;
            }

            if (query.length < 2) {
                return;
            }

            // Mostrar indicador de carga inmediatamente
            showSearchLoading();

            searchTimeout = setTimeout(() => {
                performSpotifySearch(query);
            }, 300);
        }

        function handleSearchFocus() {
            const searchInput = document.getElementById('spotify-search');
            if (!searchInput.value.trim()) {
                showQuickSuggestions();
            }
        }

        function handleSearchBlur() {
            // Delay para permitir clicks en resultados
            setTimeout(() => {
                // No ocultar si hay resultados activos
            }, 200);
        }

        function clearSearch() {
            const searchInput = document.getElementById('spotify-search');
            const clearButton = document.getElementById('clear-search');

            searchInput.value = '';
            clearButton.classList.add('hidden');
            showQuickSuggestions();
            hideSearchStats();
            searchInput.focus();
        }

        // Mostrar sugerencias rápidas
        function showQuickSuggestions() {
            const searchResults = document.getElementById('search-results');
            const quickSuggestions = document.getElementById('quick-suggestions');

            searchResults.innerHTML = '';
            quickSuggestions.classList.remove('hidden');
        }

        // Cargar géneros populares
        function loadGenres() {
            const genreGrid = document.getElementById('genre-grid');
            const genres = [
                'Pop', 'Rock', 'Reggaeton', 'Salsa', 'Bachata',
                'Electrónica', 'Jazz', 'Hip Hop', 'Cumbia', 'Merengue',
                'Indie', 'Folk', 'Blues', 'R&B', 'Country', 'Clásica'
            ];

            genreGrid.innerHTML = genres.map(genre => `
                <button onclick="searchByGenre('${genre}')" 
                        class="genre-button px-3 py-2 text-sm rounded-lg text-white font-medium focus:outline-none focus:ring-2 focus:ring-green-500">
                    ${genre}
                </button>
            `).join('');
        }

        // Cargar búsquedas recientes
        function loadRecentSearches() {
            const recentSearches = JSON.parse(localStorage.getItem('spotify_recent_searches') || '[]');
            const recentSection = document.getElementById('recent-searches-section');
            const recentList = document.getElementById('recent-searches-list');

            if (recentSearches.length > 0) {
                recentSection.classList.remove('hidden');
                recentList.innerHTML = recentSearches.map(search => `
                    <button onclick="searchByQuery('${search}')" 
                            class="px-3 py-1 text-xs bg-gray-700 hover:bg-gray-600 rounded-full text-white border border-gray-600 transition-all">
                        ${search}
                        <button onclick="removeRecentSearch('${search}', event)" 
                                class="ml-2 text-gray-400 hover:text-white">
                            ×
                        </button>
                    </button>
                `).join('');
            }
        }

        // Funciones de búsqueda
        window.searchByGenre = function (genre) {
            document.getElementById('spotify-search').value = genre;
            performSpotifySearch(genre);
        };

        window.searchByQuery = function (query) {
            document.getElementById('spotify-search').value = query;
            performSpotifySearch(query);
        };

        window.removeRecentSearch = function (search, event) {
            event.stopPropagation();
            let recentSearches = JSON.parse(localStorage.getItem('spotify_recent_searches') || '[]');
            recentSearches = recentSearches.filter(s => s !== search);
            localStorage.setItem('spotify_recent_searches', JSON.stringify(recentSearches));
            loadRecentSearches();
        };

        // Mostrar indicador de carga
        function showSearchLoading() {
            const searchResults = document.getElementById('search-results');
            const quickSuggestions = document.getElementById('quick-suggestions');

            quickSuggestions.classList.add('hidden');

            searchResults.innerHTML = `
                <div class="flex items-center justify-center py-12 bg-gradient-to-br from-gray-900 to-black rounded-lg">
                    <div class="loading-spinner mr-3"></div>
                    <span class="text-white">Buscando canciones...</span>
                </div>
            `;
        }

        // Realizar búsqueda en Spotify
        async function performSpotifySearch(query) {
            try {
                // Añadir a búsquedas recientes
                addToRecentSearches(query);

                const response = await fetch(`/spotify/search?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok && data.tracks && data.tracks.items) {
                    displaySearchResults(data.tracks.items);
                    showSearchStats(data.tracks.items.length);
                } else {
                    showNoResults();
                }
            } catch (error) {
                console.error('Error en búsqueda:', error);
                showSearchError();
            }
        }

        // Mostrar resultados de búsqueda
        function displaySearchResults(tracks) {
            const searchResults = document.getElementById('search-results');
            const quickSuggestions = document.getElementById('quick-suggestions');

            quickSuggestions.classList.add('hidden');

            if (!tracks || tracks.length === 0) {
                showNoResults();
                return;
            }

            searchResults.innerHTML = tracks.map((track, index) => `
                <div class="search-result-item spotify-track-card rounded-lg p-4 cursor-pointer" 
                     data-track='${JSON.stringify(track)}' 
                     style="animation-delay: ${index * 50}ms">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <img src="${track.image || '/img/usuario.svg'}" 
                                 alt="${track.name}" 
                                 class="w-16 h-16 rounded-lg object-cover shadow-lg">
                            <div class="absolute inset-0 bg-black/30 rounded-lg opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-white truncate text-lg">${track.name}</h4>
                            <p class="text-gray-300 text-sm truncate">${track.artist}</p>
                            <p class="text-gray-400 text-xs truncate">${track.album}</p>
                            ${track.duration_ms ? `<p class="text-gray-500 text-xs mt-1">${formatDuration(track.duration_ms)}</p>` : ''}
                        </div>

                        <div class="flex flex-col items-center gap-2">
                            ${track.preview_url ? `
                                <button class="play-button bg-green-600 hover:bg-green-500 w-10 h-10 rounded-full flex items-center justify-center transition-all" 
                                        onclick="togglePreview('${track.preview_url}', this); event.stopPropagation();" 
                                        type="button">
                                    <svg class="w-5 h-5 text-white play-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <svg class="w-5 h-5 text-white pause-icon hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            ` : `
                                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center opacity-50">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-14-14z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            `).join('');

            // Añadir event listeners para selección
            searchResults.querySelectorAll('.spotify-track-card').forEach(card => {
                card.addEventListener('click', function (e) {
                    if (e.target.closest('.play-button')) return;

                    const track = JSON.parse(this.dataset.track);
                    selectTrack(track);
                });
            });
        }

        // Funciones auxiliares
        function showNoResults() {
            document.getElementById('search-results').innerHTML = `
                <div class="text-center py-12 bg-gray-900 rounded-lg border border-gray-700">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.49.901-6.092 2.372L5 16.5v-2.5A7.5 7.5 0 0112 6.5c1.593 0 3.043.486 4.258 1.317"/>
                    </svg>
                    <p class="text-white text-lg font-medium mb-2">No se encontraron resultados</p>
                    <p class="text-gray-400">Intenta con otros términos de búsqueda</p>
                </div>
            `;
            showSearchStats(0);
        }

        function showSearchError() {
            document.getElementById('search-results').innerHTML = `
                <div class="text-center py-12 bg-red-900/20 border border-red-700 rounded-lg">
                    <svg class="mx-auto h-16 w-16 text-red-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-white text-lg font-medium mb-2">Error en la búsqueda</p>
                    <p class="text-gray-400">Intenta de nuevo en unos momentos</p>
                </div>
            `;
        }

        function showSearchStats(count) {
            const statsElement = document.getElementById('search-stats');
            const countElement = document.getElementById('results-count');

            countElement.textContent = count;
            statsElement.classList.remove('hidden');
        }

        function hideSearchStats() {
            document.getElementById('search-stats').classList.add('hidden');
        }

        function addToRecentSearches(query) {
            if (query.length < 3) return;

            let recentSearches = JSON.parse(localStorage.getItem('spotify_recent_searches') || '[]');
            recentSearches = recentSearches.filter(s => s !== query);
            recentSearches.unshift(query);
            recentSearches = recentSearches.slice(0, 5);

            localStorage.setItem('spotify_recent_searches', JSON.stringify(recentSearches));
            loadRecentSearches();
        }

        // Formatear duración
        function formatDuration(ms) {
            const minutes = Math.floor(ms / 60000);
            const seconds = ((ms % 60000) / 1000).toFixed(0);
            return `${minutes}:${seconds.padStart(2, '0')}`;
        }
    </script>
@endpush