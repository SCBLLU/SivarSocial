// Importo dropzone para manejo de imagenes
import Dropzone from "dropzone";

// Desactivo autodiscover para evitar conflictos
Dropzone.autoDiscover = false;

// Variables globales para el manejo de posts
let currentPostType = 'imagen';
let selectedTrack = null;
let currentAudio = null;
let searchTimeout = null;
let recentSearches = JSON.parse(localStorage.getItem('spotify_recent_searches') || '[]');
let popularGenres = ['pop', 'rock', 'reggaeton', 'salsa', 'bachata', 'electronica', 'jazz', 'hip hop'];
let currentPostAudio = null; // Para el reproductor de posts en el feed

// Función para cambiar entre tabs
function switchTab(type) {
    currentPostType = type;

    // Actualizar apariencia de tabs
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    document.getElementById(`tab-${type}`).classList.add('active');

    // Mostrar/ocultar contenido
    document.querySelectorAll('.content-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    document.getElementById(`content-${type}`).classList.remove('hidden');

    // Actualizar campo hidden del tipo
    document.getElementById('post-tipo').value = type;

    // Mostrar/ocultar campos del formulario
    if (type === 'imagen') {
        document.getElementById('imagen-fields').classList.remove('hidden');
        document.getElementById('musica-fields').classList.add('hidden');
    } else {
        document.getElementById('imagen-fields').classList.add('hidden');
        document.getElementById('musica-fields').classList.remove('hidden');
    }

    // Resetear validación del botón submit
    updateSubmitButton();
}

// Función para actualizar el estado del botón submit
function updateSubmitButton() {
    const submitBtn = document.getElementById('btn-submit');
    let canSubmit = false;

    if (currentPostType === 'imagen') {
        const imagenInput = document.querySelector('[name="imagen"]');
        canSubmit = imagenInput && imagenInput.value.trim() !== '';
    } else if (currentPostType === 'musica') {
        canSubmit = selectedTrack !== null;
    }

    if (submitBtn) {
        submitBtn.disabled = !canSubmit;
    }
}

// Función para buscar en Spotify
async function searchSpotify(query) {
    if (!query || query.length < 2) {
        // Mostrar sugerencias cuando no hay búsqueda
        showSearchSuggestions();
        return;
    }

    // Añadir a búsquedas recientes
    if (query.length >= 3 && !recentSearches.includes(query)) {
        recentSearches.unshift(query);
        recentSearches = recentSearches.slice(0, 5); // Mantener solo 5 búsquedas recientes
        localStorage.setItem('spotify_recent_searches', JSON.stringify(recentSearches));
    }

    // Mostrar indicador de carga
    const resultsContainer = document.getElementById('search-results');
    resultsContainer.innerHTML = `
        <div class="flex items-center justify-center py-8 bg-black rounded-lg">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
            <span class="ml-3 text-white">Buscando canciones...</span>
        </div>
    `;

    try {
        const response = await fetch(`/spotify/search?query=${encodeURIComponent(query)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (response.ok) {
            displaySearchResults(data.tracks.items);
        } else {
            throw new Error(data.error || 'Error en la búsqueda');
        }
    } catch (error) {
        console.error('Error al buscar en Spotify:', error);
        resultsContainer.innerHTML = `
            <div class="text-center py-8 bg-black rounded-lg">
                <svg class="mx-auto h-12 w-12 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-white mt-2">Error al buscar canciones</p>
                <button onclick="searchSpotify('${query}')" class="mt-2 text-white underline hover:text-gray-300 bg-gray-800 px-3 py-1 rounded">
                    Intentar de nuevo
                </button>
            </div>
        `;
        showNotification('Error al buscar en Spotify', 'error');
    }
}

// Función para mostrar sugerencias de búsqueda
function showSearchSuggestions() {
    const resultsContainer = document.getElementById('search-results');

    let suggestionsHTML = '';

    // Búsquedas recientes
    if (recentSearches.length > 0) {
        suggestionsHTML += `
            <div class="mb-4 bg-black p-4 rounded-lg">
                <h4 class="text-white font-medium mb-2 text-sm">🕒 Búsquedas recientes</h4>
                <div class="flex flex-wrap gap-2">
                    ${recentSearches.map(search => `
                        <button onclick="performSearch('${search}')" 
                                class="px-3 py-1 text-xs bg-gray-800 hover:bg-gray-700 rounded-full text-white border border-gray-600 transition-all">
                            ${search}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;
    }

    // Géneros populares
    suggestionsHTML += `
        <div class="bg-black p-4 rounded-lg">
            <h4 class="text-white font-medium mb-2 text-sm">🎵 Explorar por género</h4>
            <div class="grid grid-cols-2 gap-2">
                ${popularGenres.map(genre => `
                    <button onclick="performSearch('${genre}')" 
                            class="p-2 text-sm bg-gray-800 hover:bg-gray-700 rounded-lg text-white border border-gray-600 transition-all text-left">
                        ${genre.charAt(0).toUpperCase() + genre.slice(1)}
                    </button>
                `).join('')}
            </div>
        </div>
    `;

    resultsContainer.innerHTML = `
        <div class="space-y-4">
            ${suggestionsHTML}
        </div>
    `;
}

// Función para realizar búsqueda desde sugerencias
window.performSearch = function (query) {
    document.getElementById('spotify-search').value = query;
    searchSpotify(query);
};

// Función para reproducir/pausar preview en los posts del feed
window.togglePostPreview = function (previewUrl, button, postId) {
    const playIcon = button.querySelector('.play-icon');
    const pauseIcon = button.querySelector('.pause-icon');

    if (!previewUrl) {
        showNotification('Esta canción no tiene vista previa disponible', 'warning');
        return;
    }

    // Si hay un audio reproduciéndose actualmente
    if (currentPostAudio && !currentPostAudio.paused) {
        // Si es el mismo botón, pausar
        if (currentPostAudio.dataset && currentPostAudio.dataset.postId == postId) {
            currentPostAudio.pause();
            currentPostAudio = null;
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            button.classList.remove('animate-pulse');
            return;
        } else {
            // Pausar el audio anterior y resetear su botón
            currentPostAudio.pause();
            const previousButton = document.querySelector(`button[onclick*="togglePostPreview"][onclick*="${currentPostAudio.dataset.postId}"]`);
            if (previousButton) {
                const prevPlay = previousButton.querySelector('.play-icon');
                const prevPause = previousButton.querySelector('.pause-icon');
                if (prevPlay && prevPause) {
                    prevPlay.classList.remove('hidden');
                    prevPause.classList.add('hidden');
                    previousButton.classList.remove('animate-pulse');
                }
            }
        }
    }

    // Reproducir nuevo audio
    currentPostAudio = new Audio(previewUrl);
    currentPostAudio.dataset = { postId: postId };

    // Añadir efectos visuales
    button.classList.add('animate-pulse');
    playIcon.classList.add('hidden');
    pauseIcon.classList.remove('hidden');

    // Configurar eventos del audio
    currentPostAudio.addEventListener('loadstart', () => {
        button.style.opacity = '0.7';
    });

    currentPostAudio.addEventListener('canplay', () => {
        button.style.opacity = '1';
    });

    currentPostAudio.addEventListener('ended', () => {
        playIcon.classList.remove('hidden');
        pauseIcon.classList.add('hidden');
        button.classList.remove('animate-pulse');
        currentPostAudio = null;
    });

    currentPostAudio.addEventListener('error', () => {
        playIcon.classList.remove('hidden');
        pauseIcon.classList.add('hidden');
        button.classList.remove('animate-pulse');
        showNotification('Error al reproducir la vista previa', 'error');
        currentPostAudio = null;
    });

    // Reproducir
    currentPostAudio.play().catch(error => {
        console.error('Error al reproducir audio:', error);
        showNotification('Error al reproducir la vista previa', 'error');
        playIcon.classList.remove('hidden');
        pauseIcon.classList.add('hidden');
        button.classList.remove('animate-pulse');
        currentPostAudio = null;
    });
};

// Función para mostrar resultados de búsqueda
function displaySearchResults(tracks) {
    const resultsContainer = document.getElementById('search-results');

    if (!tracks || tracks.length === 0) {
        resultsContainer.innerHTML = `
            <div class="text-center py-8 bg-black rounded-lg">
                <svg class="mx-auto h-12 w-12 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.49.901-6.092 2.372L5 16.5v-2.5A7.5 7.5 0 0112 6.5c1.593 0 3.043.486 4.258 1.317" />
                </svg>
                <p class="text-white mt-2">No se encontraron resultados</p>
                <p class="text-gray-300 text-sm mt-1">Intenta con otros términos de búsqueda</p>
            </div>
        `;
        return;
    }

    resultsContainer.innerHTML = tracks.map((track, index) => {
        // Formatear duración
        const duration = track.duration_ms ? formatDuration(track.duration_ms) : '';

        // Crear barra de popularidad
        const popularityBar = track.popularity ?
            `<div class="w-full bg-gray-600 rounded-full h-1.5 mt-1">
                <div class="bg-white h-1.5 rounded-full transition-all duration-500" style="width: ${track.popularity}%"></div>
            </div>` : '';

        return `
            <div class="spotify-track-card opacity-0 animate-fade-in bg-black border border-gray-600 rounded-lg p-3 hover:bg-gray-900 transition-all duration-200 cursor-pointer" 
                 data-track='${JSON.stringify(track)}' 
                 style="animation-delay: ${index * 50}ms">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <img src="${track.image || '/img/usuario.svg'}" 
                             alt="${track.name}" 
                             class="spotify-album-art shadow-lg w-16 h-16 rounded-lg object-cover">
                        <div class="absolute inset-0 bg-black/40 rounded-lg opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-white truncate text-sm">${track.name}</h4>
                        <p class="text-gray-300 text-xs truncate">${track.artist}</p>
                        <p class="text-gray-400 text-xs truncate">${track.album}</p>
                        ${popularityBar}
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        ${duration ? `<span class="text-xs text-gray-300">${duration}</span>` : ''}
                        ${track.preview_url ? `
                            <button class="play-button bg-gray-700 hover:bg-gray-600 w-8 h-8 rounded-full flex items-center justify-center transition-colors" onclick="togglePreview('${track.preview_url}', this)" type="button">
                                <svg class="w-4 h-4 text-white play-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                </svg>
                                <svg class="w-4 h-4 text-white pause-icon hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        ` : `
                            <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center opacity-50">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-14-14z" clip-rule="evenodd"></path>
                                    <path fill-rule="evenodd" d="M13.646 14.354L12 12.708V8a1 1 0 10-2 0v3.292l-1.646-1.646a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        `}
                    </div>
                </div>
            </div>
        `;
    }).join('');

    // Añadir event listeners para selección
    resultsContainer.querySelectorAll('.spotify-track-card').forEach(card => {
        card.addEventListener('click', function (e) {
            if (e.target.closest('.play-button')) return; // No seleccionar si se hace clic en play

            const track = JSON.parse(this.dataset.track);

            // Añadir efecto visual de selección
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
                selectTrack(track);
            }, 150);
        });

        // Efecto hover mejorado
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-2px)';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = '';
        });
    });
}

// Función auxiliar para formatear duración
function formatDuration(ms) {
    const minutes = Math.floor(ms / 60000);
    const seconds = Math.floor((ms % 60000) / 1000);
    return `${minutes}:${seconds.toString().padStart(2, '0')}`;
}

// Función para seleccionar una canción
async function selectTrack(track) {
    selectedTrack = track;

    // Actualizar campos del formulario
    document.querySelector('[name="spotify_track_id"]').value = track.id;
    document.querySelector('[name="spotify_track_name"]').value = track.name;
    document.querySelector('[name="spotify_artist_name"]').value = track.artist;
    document.querySelector('[name="spotify_album_name"]').value = track.album;
    document.querySelector('[name="spotify_album_image"]').value = track.image || '';
    document.querySelector('[name="spotify_preview_url"]').value = track.preview_url || '';
    document.querySelector('[name="spotify_external_url"]').value = track.external_url || '';

    // Extraer color dominante
    if (track.image) {
        try {
            showNotification('Analizando colores del álbum...', 'info');
            const colorResponse = await fetch('/spotify/color', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ image_url: track.image })
            });
            const colorData = await colorResponse.json();
            const dominantColor = colorData.dominant_color || '#1DB954';
            document.querySelector('[name="dominant_color"]').value = dominantColor;

            // Actualizar el color del preview
            updateSelectedTrackColor(dominantColor);
        } catch (error) {
            console.error('Error extrayendo color:', error);
            document.querySelector('[name="dominant_color"]').value = '#1DB954';
        }
    }

    // Mostrar canción seleccionada con animación
    const selectedTrackContainer = document.getElementById('selected-track');
    selectedTrackContainer.innerHTML = `
        <div class="music-player animate-fade-in bg-black border border-gray-600 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-white font-semibold">🎵 Canción seleccionada</h4>
                <button onclick="clearSelectedTrack()" class="text-gray-400 hover:text-white transition-colors">
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
                    ${track.duration_ms ? `<p class="text-gray-500 text-xs mt-1">${formatDuration(track.duration_ms)}</p>` : ''}
                </div>
                <div class="flex flex-col items-center gap-2">
                    ${track.preview_url ? `
                        <button class="play-button bg-gray-700 hover:bg-gray-600 w-10 h-10 rounded-full flex items-center justify-center transition-colors" onclick="togglePreview('${track.preview_url}', this)" type="button">
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

    // Limpiar resultados de búsqueda con animación
    const searchResults = document.getElementById('search-results');
    searchResults.style.opacity = '0';
    setTimeout(() => {
        searchResults.innerHTML = '';
        searchResults.style.opacity = '1';
    }, 300);

    document.getElementById('spotify-search').value = '';

    // Mostrar notificación de éxito
    showNotification(`🎵 ${track.name} seleccionada`, 'success');

    updateSubmitButton();
}

// Función auxiliar para ajustar brillo de color
function adjustBrightness(hex, percent) {
    const num = parseInt(hex.slice(1), 16);
    const amt = Math.round(2.55 * percent);
    const R = (num >> 16) + amt;
    const G = (num >> 8 & 0x00FF) + amt;
    const B = (num & 0x0000FF) + amt;
    return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
        (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
        (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
}

// Función para limpiar selección de track
window.clearSelectedTrack = function () {
    selectedTrack = null;
    document.getElementById('selected-track').innerHTML = '';

    // Limpiar campos del formulario
    document.querySelector('[name="spotify_track_id"]').value = '';
    document.querySelector('[name="spotify_track_name"]').value = '';
    document.querySelector('[name="spotify_artist_name"]').value = '';
    document.querySelector('[name="spotify_album_name"]').value = '';
    document.querySelector('[name="spotify_album_image"]').value = '';
    document.querySelector('[name="spotify_preview_url"]').value = '';
    document.querySelector('[name="spotify_external_url"]').value = '';
    document.querySelector('[name="dominant_color"]').value = '';

    updateSubmitButton();
    showNotification('Selección eliminada', 'info');
};

// Función para actualizar color del track seleccionado
function updateSelectedTrackColor(color) {
    const musicPlayer = document.querySelector('.music-player');
    if (musicPlayer) {
        const brighterColor = adjustBrightness(color, 20);
        musicPlayer.style.background = `linear-gradient(135deg, ${color} 0%, ${brighterColor} 100%)`;
    }
}

// Función para reproducir/pausar preview
window.togglePreview = function (previewUrl, button) {
    const playIcon = button.querySelector('.play-icon');
    const pauseIcon = button.querySelector('.pause-icon');

    if (!previewUrl) {
        // Mostrar notificación si no hay preview disponible
        showNotification('Esta canción no tiene vista previa disponible', 'warning');
        return;
    }

    if (currentAudio && !currentAudio.paused) {
        // Pausar audio actual
        currentAudio.pause();
        currentAudio = null;

        // Resetear todos los botones
        document.querySelectorAll('.play-button').forEach(btn => {
            btn.querySelector('.play-icon').classList.remove('hidden');
            btn.querySelector('.pause-icon').classList.add('hidden');
            btn.classList.remove('playing');
        });
    } else {
        // Reproducir nuevo audio
        currentAudio = new Audio(previewUrl);

        // Añadir efectos visuales
        button.classList.add('playing');
        playIcon.classList.add('hidden');
        pauseIcon.classList.remove('hidden');

        // Configurar eventos del audio
        currentAudio.addEventListener('loadstart', () => {
            button.style.opacity = '0.7';
        });

        currentAudio.addEventListener('canplay', () => {
            button.style.opacity = '1';
        });

        currentAudio.addEventListener('ended', () => {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            button.classList.remove('playing');
        });

        currentAudio.addEventListener('error', () => {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            button.classList.remove('playing');
            showNotification('Error al reproducir la vista previa', 'error');
        });

        // Reproducir
        currentAudio.play().catch(error => {
            console.error('Error al reproducir audio:', error);
            showNotification('Error al reproducir la vista previa', 'error');
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            button.classList.remove('playing');
        });
    }
};

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 max-w-sm`;

    // Aplicar estilos según el tipo
    switch (type) {
        case 'success':
            notification.classList.add('bg-green-500', 'text-white');
            break;
        case 'error':
            notification.classList.add('bg-red-500', 'text-white');
            break;
        case 'warning':
            notification.classList.add('bg-yellow-500', 'text-white');
            break;
        default:
            notification.classList.add('bg-blue-500', 'text-white');
    }

    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;

    // Añadir al DOM
    document.body.appendChild(notification);

    // Animar entrada
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Remover después de 3 segundos
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function () {
    // Event listeners para tabs
    document.getElementById('tab-imagen')?.addEventListener('click', () => switchTab('imagen'));
    document.getElementById('tab-musica')?.addEventListener('click', () => switchTab('musica'));

    // Event listener para búsqueda de Spotify
    const spotifySearch = document.getElementById('spotify-search');
    if (spotifySearch) {
        spotifySearch.addEventListener('input', function (e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchSpotify(e.target.value.trim());
            }, 500);
        });

        // Mostrar sugerencias al hacer focus si no hay valor
        spotifySearch.addEventListener('focus', function (e) {
            if (!e.target.value.trim()) {
                showSearchSuggestions();
            }
        });
    }

    // Inicializar con imagen por defecto
    switchTab('imagen');
});

// dropzone para crear posts (solo si existe el elemento)
if (document.getElementById('dropzone')) {
    // inicializo dropzone en el formulario de posts
    let dropzone = new Dropzone('#dropzone', {
        url: '/imagenes', // ruta para subir imagenes de posts
        dictDefaultMessage: 'Sube tu post aquí', // mensaje por defecto
        acceptedFiles: '.jpg,.jpeg,.png,.gif', // tipos de archivos permitidos
        addRemoveLinks: true, // permite eliminar archivos
        dictRemoveFile: 'Eliminar archivo', // texto del boton eliminar
        maxFiles: 1, // solo una imagen por post
        maxFilesize: 2, // tamaño maximo en mb
        uploadMultiple: false, // no permite multiples archivos
        paramName: 'imagen', // nombre del campo para el backend
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // token csrf
        },
        init: function () {
            // si ya hay una imagen (por error de validacion), la muestro
            const imagenInput = document.querySelector('[name="imagen"]');
            if (imagenInput && imagenInput.value.trim()) {
                const mockFile = {
                    name: imagenInput.value,
                    size: 1234
                };
                this.emit('addedfile', mockFile);
                this.emit('thumbnail', mockFile, `/uploads/${mockFile.name}`);
                this.emit('complete', mockFile);
                mockFile.previewElement.classList.add('dz-success', 'dz-complete');
                // habilito el boton de crear si hay imagen
                updateSubmitButton();
            }
        }
    });

    // cuando la imagen se sube correctamente
    dropzone.on("success", function (file, response) {
        document.querySelector('[name="imagen"]').value = response.imagen; // guardo el nombre en el input oculto
        updateSubmitButton();
    });

    // cuando se elimina la imagen
    dropzone.on("removedfile", function (file) {
        document.querySelector('[name="imagen"]').value = ""; // limpio el input
        updateSubmitButton();
    });
}

// dropzone para registro de usuario (solo si existe el elemento)
if (document.getElementById('dropzone-register')) {
    // inicializo dropzone en el formulario de registro
    let dropzoneRegister = new Dropzone('#dropzone-register', {
        url: '/imagenes', // ruta para subir imagen de perfil
        dictDefaultMessage: 'Arrastra aquí tu imagen de perfil o haz clic', // mensaje por defecto
        acceptedFiles: '.jpg,.jpeg,.png,.gif', // tipos de archivos permitidos
        addRemoveLinks: true, // permite eliminar archivos
        dictRemoveFile: 'Eliminar', // texto del boton eliminar
        maxFiles: 1, // solo una imagen de perfil
        maxFilesize: 2, // tamaño maximo en mb
        uploadMultiple: false, // no permite multiples archivos
        paramName: 'imagen', // nombre del campo para el backend
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // token csrf
        },
        init: function () {
            // si ya hay una imagen (por error de validacion), la muestro
            this.on('maxfilesexceeded', function (file) {
                this.removeAllFiles();
                this.addFile(file);
            });
            const imagenInput = document.querySelector('[name="imagen"]');
            if (imagenInput && imagenInput.value.trim()) {
                const mockFile = {
                    name: imagenInput.value,
                    size: 1234
                };
                this.emit('addedfile', mockFile);
                this.emit('thumbnail', mockFile, `/perfiles/${mockFile.name}`);
                this.emit('complete', mockFile);
                mockFile.previewElement.classList.add('dz-success', 'dz-complete');
            }
        }
    });

    // cuando la imagen de perfil se sube correctamente
    dropzoneRegister.on("success", function (file, response) {
        // guardo el nombre en el input oculto
        document.querySelector('[name="imagen"]').value = response.imagen;
    });

    // cuando se elimina la imagen de perfil
    dropzoneRegister.on("removedfile", function (file) {
        document.querySelector('[name="imagen"]').value = "";
    });

    // si hay error al subir la imagen
    dropzoneRegister.on("error", function (file, message) {
        console.error('Error al subir imagen:', message);
    });
}

// Funciones adicionales para el nuevo componente de música
// Asegurar compatibilidad con el nuevo componente
window.selectTrack = function (track) {
    if (typeof selectTrack === 'function') {
        selectTrack(track);
    } else {
        // Fallback si la función no existe
        console.error('selectTrack function not found');
    }
};

window.togglePreview = function (previewUrl, button) {
    if (typeof togglePreview === 'function') {
        togglePreview(previewUrl, button);
    } else {
        // Implementación básica si no existe
        const playIcon = button.querySelector('.play-icon');
        const pauseIcon = button.querySelector('.pause-icon');

        if (!previewUrl) {
            showNotification('Esta canción no tiene vista previa disponible', 'warning');
            return;
        }

        if (currentAudio && !currentAudio.paused) {
            currentAudio.pause();
            currentAudio = null;
            document.querySelectorAll('.play-button').forEach(btn => {
                btn.querySelector('.play-icon').classList.remove('hidden');
                btn.querySelector('.pause-icon').classList.add('hidden');
            });
        } else {
            currentAudio = new Audio(previewUrl);
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');

            currentAudio.addEventListener('ended', () => {
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            });

            currentAudio.play().catch(error => {
                console.error('Error al reproducir:', error);
                showNotification('Error al reproducir la vista previa', 'error');
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            });
        }
    }
};