<!-- Componente de búsqueda de Spotify -->
<div class="bg-black border border-gray-600 rounded-xl p-8 mb-4">
    <h3 class="text-white text-xl font-bold mb-4 text-center">Buscar en Spotify</h3>

    <!-- Campo de búsqueda -->
    <div class="relative">
        <input type="text" id="spotify-search"
            class="bg-gray-900 border-2 border-gray-600 text-white rounded-full px-6 py-3 w-full placeholder-gray-400 focus:outline-none focus:border-gray-400 focus:ring-2 focus:ring-gray-400/30 transition-all"
            placeholder="Busca tu canción favorita..." autocomplete="off">

        <!-- Icono de búsqueda -->
        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"></path>
            </svg>
        </div>
    </div>

    <!-- Resultados de búsqueda -->
    <div id="search-results" class="mt-4 max-h-96 overflow-y-auto space-y-2">
        <!-- Los resultados se cargarán aquí dinámicamente -->
    </div>

    <!-- Canción seleccionada -->
    <div id="selected-track" class="mt-4">
        <!-- La canción seleccionada se mostrará aquí -->
    </div>

    <!-- Indicador de estado -->
    <div id="spotify-status" class="mt-2 text-center text-sm text-gray-400 hidden">
        <span id="status-text"></span>
    </div>
</div>

@push('scripts')
    <script>
        // Script específico para el componente de Spotify
        document.addEventListener('DOMContentLoaded', function () {
            const spotifySearch = document.getElementById('spotify-search');

            if (spotifySearch) {
                // Event listener para búsqueda en tiempo real
                spotifySearch.addEventListener('input', function (e) {
                    const query = e.target.value.trim();

                    // Clear previous timeout
                    if (window.spotifySearchTimeout) {
                        clearTimeout(window.spotifySearchTimeout);
                    }

                    // Set new timeout for search
                    window.spotifySearchTimeout = setTimeout(() => {
                        if (query.length >= 2) {
                            searchSpotifyInComponent(query);
                        } else if (query.length === 0) {
                            showSearchSuggestions();
                        }
                    }, 300);
                });

                // Mostrar sugerencias al hacer focus
                spotifySearch.addEventListener('focus', function () {
                    if (this.value.trim() === '') {
                        showSearchSuggestions();
                    }
                });
            }
        });

        // Función específica del componente para buscar en Spotify
        async function searchSpotifyInComponent(query) {
            if (!query || query.length < 2) {
                return;
            }

            const resultsContainer = document.getElementById('search-results');
            resultsContainer.innerHTML = `
            <div class="flex items-center justify-center py-8 bg-black rounded-lg">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                <span class="ml-3 text-white">Buscando canciones...</span>
            </div>
        `;

            try {
                const response = await fetch(`{{ route('spotify.search') }}?query=${encodeURIComponent(query)}`);
                const data = await response.json();

                if (data.tracks && data.tracks.items) {
                    displaySearchResults(data.tracks.items);
                    showSpotifyStatus(`Encontradas ${data.tracks.items.length} canciones`, 'success');
                } else {
                    resultsContainer.innerHTML = `
                    <div class="text-center py-8 bg-black rounded-lg">
                        <p class="text-gray-400">No se encontraron canciones para "${query}"</p>
                    </div>
                `;
                    showSpotifyStatus('No se encontraron resultados', 'info');
                }
            } catch (error) {
                console.error('Error buscando en Spotify:', error);
                resultsContainer.innerHTML = `
                <div class="text-center py-8 bg-black rounded-lg">
                    <p class="text-red-400">Error al buscar canciones. Inténtalo de nuevo.</p>
                </div>
            `;
                showSpotifyStatus('Error en la búsqueda', 'error');
            }
        }

        // Función para mostrar estado del componente
        function showSpotifyStatus(message, type = 'info') {
            const statusElement = document.getElementById('spotify-status');
            const statusText = document.getElementById('status-text');

            if (statusElement && statusText) {
                statusText.textContent = message;
                statusElement.classList.remove('hidden');

                // Cambiar color según el tipo
                statusElement.className = statusElement.className.replace(/text-\w+-\d+/, '');
                switch (type) {
                    case 'success':
                        statusElement.classList.add('text-green-400');
                        break;
                    case 'error':
                        statusElement.classList.add('text-red-400');
                        break;
                    default:
                        statusElement.classList.add('text-gray-400');
                }

                // Auto-hide después de 3 segundos
                setTimeout(() => {
                    statusElement.classList.add('hidden');
                }, 3000);
            }
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Estilos específicos para el componente de Spotify */
        #spotify-search:focus {
            box-shadow: 0 0 0 2px rgba(75, 85, 99, 0.3);
        }

        .spotify-search-container {
            position: relative;
        }

        /* Animaciones para los resultados */
        .search-result-enter {
            opacity: 0;
            transform: translateY(-10px);
        }

        .search-result-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        /* Scrollbar personalizado para los resultados */
        #search-results::-webkit-scrollbar {
            width: 6px;
        }

        #search-results::-webkit-scrollbar-track {
            background: rgba(75, 85, 99, 0.2);
            border-radius: 3px;
        }

        #search-results::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        #search-results::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }
    </style>
@endpush