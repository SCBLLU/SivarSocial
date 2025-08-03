// Módulo centralizado para funcionalidad de Spotify
// Evita duplicación de código entre app.js y componentes

class SpotifyModule {
    constructor() {
        this.currentAudio = null;
        this.selectedTrack = null;
        this.searchTimeout = null;
    }

    // Función para buscar en Spotify (unificada)
    async searchSpotify(query, resultsContainerId = 'search-results') {
        if (!query || query.length < 2) {
            this.showSearchSuggestions(resultsContainerId);
            return;
        }

        const resultsContainer = document.getElementById(resultsContainerId);
        if (!resultsContainer) return;

        this.showLoadingState(resultsContainer);

        try {
            const response = await fetch(`/spotify/search?query=${encodeURIComponent(query)}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok && data.tracks && data.tracks.items) {
                this.displaySearchResults(data.tracks.items, resultsContainerId);
                this.showSearchStats(data.tracks.items.length);
            } else {
                this.showNoResults(resultsContainer);
            }
        } catch (error) {
            console.error('Error al buscar en Spotify:', error);
            this.showSearchError(resultsContainer);
        }
    }

    // Mostrar estado de carga unificado
    showLoadingState(container) {
        container.innerHTML = `
            <div class="flex items-center justify-center py-8 bg-gray-900 rounded-lg">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white mr-3"></div>
                <span class="text-white">Buscando canciones...</span>
            </div>
        `;
    }

    // Mostrar resultados de búsqueda (unificado)
    displaySearchResults(tracks, containerId = 'search-results') {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (!tracks || tracks.length === 0) {
            this.showNoResults(container);
            return;
        }

        container.innerHTML = tracks.map((track, index) => {
            const duration = track.duration_ms ? this.formatDuration(track.duration_ms) : '';

            return `
                <div class="spotify-track-card bg-gray-900 hover:bg-gray-800 rounded-lg p-4 cursor-pointer transition-colors duration-200 border border-gray-800 hover:border-gray-700" 
                     data-track='${JSON.stringify(track)}'>
                    <div class="flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <img src="${track.image || '/img/usuario.svg'}" 
                                 alt="${track.name}" 
                                 class="w-12 h-12 rounded object-cover">
                            <div class="absolute inset-0 bg-black/40 rounded opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-white truncate">${track.name}</h4>
                            <p class="text-gray-400 text-sm truncate">${track.artist}</p>
                            <p class="text-gray-500 text-xs truncate">${track.album}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            ${duration ? `<span class="text-gray-400 text-xs">${duration}</span>` : ''}
                            ${track.preview_url ? `
                                <button class="play-button bg-white hover:bg-gray-200 text-black w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-200" 
                                        onclick="spotifyModule.togglePreview('${track.preview_url}', this); event.stopPropagation();" 
                                        type="button">
                                    <svg class="w-4 h-4 play-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <svg class="w-4 h-4 pause-icon hidden" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            ` : `
                                <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center opacity-50">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-14-14z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        // Añadir event listeners
        this.attachTrackListeners(containerId);
    }

    // Adjuntar event listeners a las tarjetas de canciones
    attachTrackListeners(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.querySelectorAll('.spotify-track-card').forEach(card => {
            card.addEventListener('click', (e) => {
                if (e.target.closest('.play-button')) return;

                const track = JSON.parse(card.dataset.track);
                this.selectTrack(track);
            });
        });
    }

    // Función para reproducir/pausar preview (unificada)
    togglePreview(previewUrl, button) {
        const playIcon = button.querySelector('.play-icon');
        const pauseIcon = button.querySelector('.pause-icon');

        if (!previewUrl) {
            this.showNotification('Esta canción no tiene vista previa disponible', 'warning');
            return;
        }

        if (this.currentAudio && !this.currentAudio.paused) {
            this.currentAudio.pause();
            this.currentAudio = null;
            this.resetAllPlayButtons();
        } else {
            this.currentAudio = new Audio(previewUrl);

            button.classList.add('playing');
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');

            this.currentAudio.addEventListener('ended', () => {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                button.classList.remove('playing');
            });

            this.currentAudio.addEventListener('error', () => {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                button.classList.remove('playing');
                this.showNotification('Error al reproducir la vista previa', 'error');
            });

            this.currentAudio.play().catch(error => {
                console.error('Error al reproducir audio:', error);
                this.showNotification('Error al reproducir la vista previa', 'error');
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                button.classList.remove('playing');
            });
        }
    }

    // Resetear todos los botones de reproducción
    resetAllPlayButtons() {
        document.querySelectorAll('.play-button').forEach(btn => {
            const playIcon = btn.querySelector('.play-icon');
            const pauseIcon = btn.querySelector('.pause-icon');
            if (playIcon && pauseIcon) {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                btn.classList.remove('playing');
            }
        });
    }

    // Seleccionar una canción (unificado)
    async selectTrack(track) {
        this.selectedTrack = track;

        // Actualizar campos del formulario
        this.updateFormFields(track);

        // Extraer color dominante
        if (track.image) {
            await this.extractDominantColor(track.image);
        }

        // Mostrar canción seleccionada
        this.displaySelectedTrack(track);

        // Limpiar búsqueda
        this.clearSearchResults();

        this.showNotification(`🎵 ${track.name} seleccionada`, 'success');

        // Actualizar botón submit si existe la función
        if (typeof updateSubmitButton === 'function') {
            updateSubmitButton();
        }
    }

    // Actualizar campos del formulario
    updateFormFields(track) {
        const fields = {
            'spotify_track_id': track.id,
            'spotify_track_name': track.name,
            'spotify_artist_name': track.artist,
            'spotify_album_name': track.album,
            'spotify_album_image': track.image || '',
            'spotify_preview_url': track.preview_url || '',
            'spotify_external_url': track.external_url || ''
        };

        Object.entries(fields).forEach(([name, value]) => {
            const input = document.querySelector(`[name="${name}"]`);
            if (input) input.value = value;
        });
    }

    // Extraer color dominante
    async extractDominantColor(imageUrl) {
        try {
            const response = await fetch('/spotify/color', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ image_url: imageUrl })
            });

            const data = await response.json();
            const color = data.dominant_color || '#1DB954';

            const colorInput = document.querySelector('[name="dominant_color"]');
            if (colorInput) colorInput.value = color;

            return color;
        } catch (error) {
            console.error('Error extrayendo color:', error);
            const colorInput = document.querySelector('[name="dominant_color"]');
            if (colorInput) colorInput.value = '#1DB954';
            return '#1DB954';
        }
    }

    // Mostrar canción seleccionada
    displaySelectedTrack(track) {
        const container = document.getElementById('selected-track');
        if (!container) return;

        container.innerHTML = `
            <div class="music-player animate-fade-in bg-black border border-gray-600 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-white font-semibold">🎵 Canción seleccionada</h4>
                    <button onclick="spotifyModule.clearSelectedTrack()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-4">
                    <img src="${track.image || '/img/usuario.svg'}" 
                         alt="${track.name}" 
                         class="w-16 h-16 rounded-lg object-cover shadow-lg">
                    <div class="flex-1 min-w-0">
                        <h5 class="text-white font-semibold text-lg truncate">${track.name}</h5>
                        <p class="text-gray-300 text-sm truncate">${track.artist}</p>
                        <p class="text-gray-400 text-xs truncate">${track.album}</p>
                        ${track.duration_ms ? `<p class="text-gray-500 text-xs mt-1">${this.formatDuration(track.duration_ms)}</p>` : ''}
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        ${track.preview_url ? `
                            <button class="play-button bg-gray-700 hover:bg-gray-600 w-10 h-10 rounded-full flex items-center justify-center transition-colors" onclick="spotifyModule.togglePreview('${track.preview_url}', this)" type="button">
                                <svg class="w-6 h-6 text-white play-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                </svg>
                                <svg class="w-6 h-6 text-white pause-icon hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        ` : ''}
                        ${track.external_url ? `
                            <a href="${track.external_url}" target="_blank" 
                               class="text-gray-400 hover:text-white transition-colors text-xs border border-gray-600 px-2 py-1 rounded hover:border-gray-400">
                                Ver en Spotify ↗
                            </a>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // Limpiar selección de track
    clearSelectedTrack() {
        this.selectedTrack = null;

        const container = document.getElementById('selected-track');
        if (container) container.innerHTML = '';

        // Limpiar campos del formulario
        const fieldNames = [
            'spotify_track_id', 'spotify_track_name', 'spotify_artist_name',
            'spotify_album_name', 'spotify_album_image', 'spotify_preview_url',
            'spotify_external_url', 'dominant_color'
        ];

        fieldNames.forEach(name => {
            const input = document.querySelector(`[name="${name}"]`);
            if (input) input.value = '';
        });

        if (typeof updateSubmitButton === 'function') {
            updateSubmitButton();
        }

        this.showNotification('Selección eliminada', 'info');
    }

    // Limpiar resultados de búsqueda
    clearSearchResults() {
        const searchResults = document.getElementById('search-results');
        const searchInput = document.getElementById('spotify-search');

        if (searchResults) {
            searchResults.style.opacity = '0';
            setTimeout(() => {
                searchResults.innerHTML = '';
                searchResults.style.opacity = '1';
            }, 300);
        }

        if (searchInput) searchInput.value = '';
    }

    // Formatear duración (unificado)
    formatDuration(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = ((ms % 60000) / 1000).toFixed(0);
        return `${minutes}:${seconds.padStart(2, '0')}`;
    }

    // Mostrar sugerencias rápidas
    showSearchSuggestions(containerId = 'search-results') {
        const container = document.getElementById(containerId);
        if (!container) return;

        const genres = ['Pop', 'Rock', 'Reggaeton', 'Salsa', 'Bachata', 'Electrónica', 'Jazz', 'Hip Hop'];

        container.innerHTML = `
            <div class="bg-black p-4 rounded-lg">
                <h4 class="text-white font-medium mb-2 text-sm">🎵 Explorar por género</h4>
                <div class="grid grid-cols-2 gap-2">
                    ${genres.map(genre => `
                        <button onclick="spotifyModule.searchByGenre('${genre}')" 
                                class="p-2 text-sm bg-gray-800 hover:bg-gray-700 rounded-lg text-white border border-gray-600 transition-all text-left">
                            ${genre}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;
    }

    // Buscar por género
    searchByGenre(genre) {
        const searchInput = document.getElementById('spotify-search');
        if (searchInput) {
            searchInput.value = genre;
            this.searchSpotify(genre);
        }
    }

    // Estados de error y carga
    showNoResults(container) {
        container.innerHTML = `
            <div class="text-center py-12 bg-gray-900 rounded-lg border border-gray-800">
                <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.49.901-6.092 2.372L5 16.5v-2.5A7.5 7.5 0 0112 6.5c1.593 0 3.043.486 4.258 1.317"/>
                </svg>
                <p class="text-white font-medium mb-2">No se encontraron resultados</p>
                <p class="text-gray-400">Intenta con otros términos de búsqueda</p>
            </div>
        `;
    }

    showSearchError(container) {
        container.innerHTML = `
            <div class="text-center py-12 bg-red-900/20 border border-red-700 rounded-lg">
                <svg class="mx-auto h-16 w-16 text-red-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-white text-lg font-medium mb-2">Error en la búsqueda</p>
                <p class="text-gray-400">Intenta de nuevo en unos momentos</p>
            </div>
        `;
    }

    showSearchStats(count) {
        const statsElement = document.getElementById('search-stats');
        const countElement = document.getElementById('results-count');

        if (statsElement && countElement) {
            countElement.textContent = count;
            statsElement.classList.remove('hidden');
        }
    }

    // Función para mostrar notificaciones
    showNotification(message, type = 'info') {
        // Si existe la función global, usarla
        if (typeof showNotification === 'function') {
            return showNotification(message, type);
        }

        // Implementación básica
        console.log(`[${type.toUpperCase()}] ${message}`);
    }
}

// Crear instancia global
window.spotifyModule = new SpotifyModule();

// Exportar para uso en otros módulos
export default SpotifyModule;
