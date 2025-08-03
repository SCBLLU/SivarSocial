// Importo dropzone para manejo de imagenes
import Dropzone from "dropzone";

// Importar módulo centralizado de Spotify para evitar duplicación
// import SpotifyModule from './spotify-module.js'; // Descomentado cuando el servidor esté listo

// Desactivo autodiscover para evitar conflictos
Dropzone.autoDiscover = false;

// Variables globales para el manejo de posts (no duplicar Spotify vars)
let currentPostType = 'imagen';
let currentPostAudio = null; // Para el reproductor de posts en el feed (diferente del reproductor de búsqueda)

// Inicializar módulo de Spotify cuando esté disponible
// window.spotifyModule = new SpotifyModule();

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
        // Comprobar si hay una canción seleccionada (usar spotifyModule cuando esté disponible)
        const trackIdInput = document.querySelector('[name="spotify_track_id"]');
        canSubmit = trackIdInput && trackIdInput.value.trim() !== '';
    }

    if (submitBtn) {
        submitBtn.disabled = !canSubmit;
    }
}

// FUNCIONES DE SPOTIFY - Usar el módulo centralizado para evitar duplicación
// Estas funciones son adapters que llaman al módulo centralizado

// Adapter para búsqueda - llama al módulo centralizado
async function searchSpotify(query) {
    // Si el módulo está disponible, usarlo
    if (window.spotifyModule) {
        return window.spotifyModule.searchSpotify(query);
    }

    // Fallback temporal para compatibilidad
    console.log('Spotify module not loaded, using fallback');
    if (!query || query.length < 2) {
        showSearchSuggestions();
        return;
    }

    const resultsContainer = document.getElementById('search-results');
    if (!resultsContainer) return;

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
        if (response.ok && data.tracks) {
            displaySearchResults(data.tracks.items);
        }
    } catch (error) {
        console.error('Error en búsqueda:', error);
        showNotification('Error al buscar en Spotify', 'error');
    }
}

// Adapter para mostrar resultados
function displaySearchResults(tracks) {
    if (window.spotifyModule) {
        return window.spotifyModule.displaySearchResults(tracks);
    }

    // Fallback temporal
    const resultsContainer = document.getElementById('search-results');
    if (!resultsContainer || !tracks) return;

    resultsContainer.innerHTML = tracks.map(track => `
        <div class="spotify-track-card bg-black border border-gray-600 rounded-lg p-3 cursor-pointer" 
             onclick="selectTrack(${JSON.stringify(track).replace(/"/g, '&quot;')})">
            <div class="flex items-center gap-3">
                <img src="${track.image || '/img/usuario.svg'}" class="w-12 h-12 rounded object-cover">
                <div class="flex-1">
                    <h4 class="text-white font-medium">${track.name}</h4>
                    <p class="text-gray-400 text-sm">${track.artist}</p>
                </div>
            </div>
        </div>
    `).join('');

    // Añadir listeners
    resultsContainer.querySelectorAll('.spotify-track-card').forEach(card => {
        card.addEventListener('click', () => {
            const track = JSON.parse(card.querySelector('.spotify-track-card').getAttribute('onclick').match(/selectTrack\((.*?)\)/)[1]);
            selectTrack(track);
        });
    });
}

// Adapter para seleccionar track
async function selectTrack(track) {
    if (window.spotifyModule) {
        return window.spotifyModule.selectTrack(track);
    }

    // Fallback temporal - funcionalidad básica
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

    // Mostrar selección básica
    const container = document.getElementById('selected-track');
    if (container) {
        container.innerHTML = `
            <div class="bg-black border border-gray-600 rounded-lg p-4">
                <h4 class="text-white mb-2">🎵 ${track.name}</h4>
                <p class="text-gray-400">${track.artist}</p>
                <button onclick="clearSelectedTrack()" class="mt-2 text-gray-400 hover:text-white">
                    Eliminar selección
                </button>
            </div>
        `;
    }

    document.getElementById('search-results').innerHTML = '';
    document.getElementById('spotify-search').value = '';

    showNotification(`🎵 ${track.name} seleccionada`, 'success');
    updateSubmitButton();
}

// Adapter para reproducir preview
function togglePreview(previewUrl, button) {
    if (window.spotifyModule) {
        return window.spotifyModule.togglePreview(previewUrl, button);
    }

    // Fallback temporal - implementación básica
    console.log('Toggle preview fallback:', previewUrl);
    showNotification('Función de reproducción en desarrollo', 'info');
}

// Función para limpiar selección
window.clearSelectedTrack = function () {
    if (window.spotifyModule) {
        return window.spotifyModule.clearSelectedTrack();
    }

    // Fallback temporal
    document.getElementById('selected-track').innerHTML = '';
    const fieldNames = [
        'spotify_track_id', 'spotify_track_name', 'spotify_artist_name',
        'spotify_album_name', 'spotify_album_image', 'spotify_preview_url',
        'spotify_external_url', 'dominant_color'
    ];

    fieldNames.forEach(name => {
        const input = document.querySelector(`[name="${name}"]`);
        if (input) input.value = '';
    });

    updateSubmitButton();
    showNotification('Selección eliminada', 'info');
};

// Función para mostrar sugerencias de búsqueda
function showSearchSuggestions() {
    if (window.spotifyModule) {
        return window.spotifyModule.showSearchSuggestions();
    }

    const resultsContainer = document.getElementById('search-results');
    if (!resultsContainer) return;

    const genres = ['Pop', 'Rock', 'Reggaeton', 'Salsa', 'Bachata', 'Electrónica'];

    resultsContainer.innerHTML = `
        <div class="bg-black p-4 rounded-lg">
            <h4 class="text-white font-medium mb-2">🎵 Explorar por género</h4>
            <div class="grid grid-cols-2 gap-2">
                ${genres.map(genre => `
                    <button onclick="performSearch('${genre}')" 
                            class="p-2 text-sm bg-gray-800 hover:bg-gray-700 rounded-lg text-white">
                        ${genre}
                    </button>
                `).join('')}
            </div>
        </div>
    `;
}

// Función para realizar búsqueda desde sugerencias
window.performSearch = function (query) {
    document.getElementById('spotify-search').value = query;
    searchSpotify(query);
};

// FUNCIONES ESPECÍFICAS PARA POSTS DEL FEED (no duplicar con búsqueda)
// Estas funciones son solo para reproducir música en el feed, no para buscar

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

// Función para mostrar notificaciones (usada por todas las funciones)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 max-w-sm`;

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

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

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

    // Event listener para búsqueda de Spotify con debounce
    const spotifySearch = document.getElementById('spotify-search');
    if (spotifySearch) {
        let searchTimeout;

        spotifySearch.addEventListener('input', function (e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchSpotify(e.target.value.trim());
            }, 500);
        });

        spotifySearch.addEventListener('focus', function (e) {
            if (!e.target.value.trim()) {
                showSearchSuggestions();
            }
        });
    }

    // Inicializar con imagen por defecto
    switchTab('imagen');

    // Intentar cargar el módulo de Spotify si no está disponible
    if (!window.spotifyModule) {
        console.log('SpotifyModule no disponible, usando funciones fallback');
    }
});

// DROPZONE PARA CREAR POSTS
if (document.getElementById('dropzone')) {
    let dropzone = new Dropzone('#dropzone', {
        url: '/imagenes',
        dictDefaultMessage: 'Sube tu post aquí',
        acceptedFiles: '.jpg,.jpeg,.png,.gif',
        addRemoveLinks: true,
        dictRemoveFile: 'Eliminar archivo',
        maxFiles: 1,
        maxFilesize: 2,
        uploadMultiple: false,
        paramName: 'imagen',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function () {
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
                updateSubmitButton();
            }
        }
    });

    dropzone.on("success", function (file, response) {
        document.querySelector('[name="imagen"]').value = response.imagen;
        updateSubmitButton();
    });

    dropzone.on("removedfile", function (file) {
        document.querySelector('[name="imagen"]').value = "";
        updateSubmitButton();
    });
}

// DROPZONE PARA REGISTRO DE USUARIO
if (document.getElementById('dropzone-register')) {
    let dropzoneRegister = new Dropzone('#dropzone-register', {
        url: '/imagenes',
        dictDefaultMessage: 'Arrastra aquí tu imagen de perfil o haz clic',
        acceptedFiles: '.jpg,.jpeg,.png,.gif',
        addRemoveLinks: true,
        dictRemoveFile: 'Eliminar',
        maxFiles: 1,
        maxFilesize: 2,
        uploadMultiple: false,
        paramName: 'imagen',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function () {
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

    dropzoneRegister.on("success", function (file, response) {
        document.querySelector('[name="imagen"]').value = response.imagen;
    });

    dropzoneRegister.on("removedfile", function (file) {
        document.querySelector('[name="imagen"]').value = "";
    });

    dropzoneRegister.on("error", function (file, message) {
        console.error('Error al subir imagen:', message);
    });
}

// Exponer funciones globales para compatibilidad con componentes
window.selectTrack = selectTrack;
window.togglePreview = togglePreview;
window.showNotification = showNotification;

