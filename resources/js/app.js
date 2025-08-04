// Importo dropzone para manejo de imagenes
import Dropzone from "dropzone";

// Desactivo autodiscover para evitar conflictos
Dropzone.autoDiscover = false;

// Variables globales para el manejo de posts
let currentPostType = 'imagen'; // Tipo de post actual (imagen o musica)

// Función para cambiar entre tabs
function switchTab(type) {
    currentPostType = type;

    // Actualizar apariencia de tabs
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    document.getElementById(`tab-${type}`).classList.add('active');

    // Mostrar/ocultar contenido de cada tab
    document.querySelectorAll('.content-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    document.getElementById(`content-${type}`).classList.remove('hidden');

    // Actualizar campo hidden del tipo de post
    document.getElementById('post-tipo').value = type;

    // Mostrar/ocultar campos del formulario según el tipo de post
    if (type === 'imagen') {
        document.getElementById('imagen-fields').classList.remove('hidden');
        document.getElementById('musica-fields').classList.add('hidden');

        // Ocultar indicadores de opcional
        document.getElementById('titulo-optional').classList.add('hidden');
        document.getElementById('descripcion-optional').classList.add('hidden');

        // Cambiar placeholders para imagen
        document.getElementById('titulo').placeholder = 'Título de la publicación';
        document.getElementById('descripcion').placeholder = 'Describe tu publicación...';
    } else {
        document.getElementById('imagen-fields').classList.add('hidden');
        document.getElementById('musica-fields').classList.remove('hidden');

        // Mostrar indicadores de opcional
        document.getElementById('titulo-optional').classList.remove('hidden');
        document.getElementById('descripcion-optional').classList.remove('hidden');

        // Cambiar placeholders para música
        document.getElementById('titulo').placeholder = 'Título personalizado (opcional)';
        document.getElementById('descripcion').placeholder = 'Agrega un comentario sobre esta canción (opcional)...';
    }

    // Resetear validación del botón submit
    updateSubmitButton();
}

// Función para actualizar el estado del botón submit
function updateSubmitButton() {
    const submitBtn = document.getElementById('btn-submit');
    let canSubmit = false;

    console.log('updateSubmitButton called, currentPostType:', currentPostType);

    // Validación según el tipo de post
    if (currentPostType === 'imagen') {
        const imagenInput = document.querySelector('[name="imagen"]');
        const tituloInput = document.getElementById('titulo');
        const descripcionInput = document.getElementById('descripcion');

        console.log('imagen value:', imagenInput?.value || 'not found');
        console.log('titulo value:', tituloInput?.value || 'not found');
        console.log('descripcion value:', descripcionInput?.value || 'not found');

        canSubmit = imagenInput && imagenInput.value.trim() !== '' &&
            tituloInput && tituloInput.value.trim() !== '' &&
            descripcionInput && descripcionInput.value.trim() !== '';
    } else if (currentPostType === 'musica') {
        const trackIdInput = document.querySelector('[name="itunes_track_id"]') || document.querySelector('[name="spotify_track_id"]');
        canSubmit = trackIdInput && trackIdInput.value.trim() !== '';
    }

    console.log('canSubmit:', canSubmit);

    // Habilitar/deshabilitar botón submit
    if (submitBtn) {
        submitBtn.disabled = !canSubmit;
        console.log('Button disabled state:', submitBtn.disabled);
    } else {
        console.log('Submit button not found');
    }
}

// REPRODUCTOR DE AUDIO GLOBAL

let currentAudio = null;
let currentTrackId = null;

// Función global para reproducir previews de audio
function toggleAudioPreview(previewUrl, trackId, source) {
    // Si ya hay un audio reproduciéndose
    if (currentAudio && !currentAudio.paused) {
        // Si es el mismo track, pausar
        if (currentTrackId === trackId) {
            currentAudio.pause();
            updatePlayButton(currentTrackId, false);
            currentTrackId = null;
            return;
        } else {
            // Si es diferente track, parar el actual
            currentAudio.pause();
            updatePlayButton(currentTrackId, false);
        }
    }

    // Crear nuevo audio
    currentAudio = new Audio(previewUrl);
    currentTrackId = trackId;

    // Configurar eventos
    currentAudio.addEventListener('loadstart', () => {
    });

    currentAudio.addEventListener('canplay', () => {
        updatePlayButton(trackId, true);
        currentAudio.play().catch(error => {
            console.error('Error al reproducir:', error);
            showNotification('Error al reproducir el preview', 'error');
        });
    });

    currentAudio.addEventListener('ended', () => {
        updatePlayButton(trackId, false);
        currentTrackId = null;
    });

    currentAudio.addEventListener('error', () => {
        showNotification('Error al cargar el preview', 'error');
        updatePlayButton(trackId, false);
        currentTrackId = null;
    });

    // Cargar audio
    currentAudio.load();
}

// Función para actualizar el estado visual del botón de play
function updatePlayButton(trackId, isPlaying) {
    const playIcon = document.querySelector(`.play-icon-${trackId}`);
    const pauseIcon = document.querySelector(`.pause-icon-${trackId}`);

    if (playIcon && pauseIcon) {
        if (isPlaying) {
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
        } else {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
        }
    }

    // Emitir evento para Alpine.js
    document.dispatchEvent(new CustomEvent('audioStateChanged', {
        detail: {
            isPlaying: isPlaying,
            trackId: isPlaying ? trackId : null
        }
    }));
}

// FUNCIONES DE iTUNES

// Variables globales para iTunes
let itunesCurrentTracks = [];
let itunesSelectedTrack = null;
let itunesSearchDebounce = null;

// Función principal para búsqueda de iTunes
async function searchiTunes(query) {
    if (!query || query.trim() === '') {
        itunesShowSuggestions();
        return;
    }

    try {
        // Mostrar loader
        itunesShowLoader();

        const response = await fetch(`/itunes/search?query=${encodeURIComponent(query)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        if (response.ok && data.results) {
            itunesCurrentTracks = data.results;
            itunesDisplayResults(data.results);
        } else {
            itunesShowError('No se encontraron resultados');
        }
    } catch (error) {
        console.error('Error en búsqueda:', error);
        itunesShowError('Error al buscar');
    }
}

// Función para seleccionar track de iTunes
function itunesSelectTrack(track) {
    itunesSelectedTrack = track;

    // Actualizar campos del formulario
    const fields = {
        'music_source': 'itunes',
        'itunes_track_id': track.trackId,
        'itunes_track_name': track.trackName,
        'itunes_artist_name': track.artistName,
        'itunes_collection_name': track.collectionName,
        'itunes_artwork_url': track.artworkUrlHigh || track.artworkUrl100 || '',
        'itunes_preview_url': track.previewUrl || '',
        'itunes_track_view_url': track.trackViewUrl || '',
        'itunes_track_time_millis': track.trackTimeMillis || 0,
        'itunes_country': track.country || 'US',
        'itunes_primary_genre_name': track.primaryGenreName || ''
    };

    Object.entries(fields).forEach(([name, value]) => {
        const input = document.querySelector(`[name="${name}"]`);
        if (input) input.value = value;
    });

    // Limpiar búsqueda
    itunesClearSearch();

    showNotification(`${track.trackName} seleccionada`, 'success');
    updateSubmitButton();
}

// Función para mostrar loader
function itunesShowLoader() {
    document.dispatchEvent(new CustomEvent('itunes:showLoader'));
}

// Función para mostrar resultados
function itunesDisplayResults(tracks) {
    document.dispatchEvent(new CustomEvent('itunes:displayResults', {
        detail: { tracks }
    }));
}

// Función para mostrar sugerencias
function itunesShowSuggestions() {
    document.dispatchEvent(new CustomEvent('itunes:showSuggestions'));
}

// Función para mostrar error
function itunesShowError(message) {
    document.dispatchEvent(new CustomEvent('itunes:showError', {
        detail: { message }
    }));
    showNotification(message, 'error');
}

// Función para limpiar búsqueda
function itunesClearSearch() {
    document.getElementById('itunes-search').value = '';
    document.dispatchEvent(new CustomEvent('itunes:clearSearch'));
}

// Función para limpiar selección
function itunesClearSelection() {
    itunesSelectedTrack = null;

    const fieldNames = [
        'itunes_track_id', 'itunes_track_name', 'itunes_artist_name',
        'itunes_collection_name', 'itunes_artwork_url', 'itunes_preview_url',
        'itunes_track_view_url', 'itunes_track_time_millis', 'itunes_country',
        'itunes_primary_genre_name'
    ];

    fieldNames.forEach(name => {
        const input = document.querySelector(`[name="${name}"]`);
        if (input) input.value = '';
    });

    // También limpiar el music_source si era iTunes
    const musicSourceInput = document.querySelector('[name="music_source"]');
    if (musicSourceInput && musicSourceInput.value === 'itunes') {
        musicSourceInput.value = '';
    }

    document.dispatchEvent(new CustomEvent('itunes:trackCleared'));
    updateSubmitButton();
    showNotification('Selección eliminada', 'info');
}

// Función para reproducir preview de iTunes
function itunesTogglePreview(previewUrl, trackId) {
    if (!previewUrl) {
        showNotification('No hay preview disponible', 'warning');
        return;
    }

    // Usar el reproductor global
    toggleAudioPreview(previewUrl, trackId, 'itunes');
}

// Funciones globales para compatibilidad con componentes
window.clearSelectedTrack = function () {
    // Determinar qué sistema está activo y limpiar el correcto
    const musicSource = document.querySelector('[name="music_source"]')?.value;
    if (musicSource === 'itunes' || itunesSelectedTrack) {
        itunesClearSelection();
    } else if (musicSource === 'spotify' || spotifySelectedTrack) {
        spotifyClearSelection();
    }
};

// Hacer disponibles las funciones iTunes globalmente
window.itunesSelectTrack = itunesSelectTrack;
window.itunesClearSelection = itunesClearSelection;
window.itunesTogglePreview = itunesTogglePreview;
window.toggleAudioPreview = toggleAudioPreview;
window.searchiTunes = searchiTunes;

window.performiTunesSearch = function (query) {
    const searchInput = document.getElementById('itunes-search');
    if (searchInput) {
        searchInput.value = query;
        searchiTunes(query);
    }
};

// FUNCIONES DE SPOTIFY

// Variables globales para Spotify
let spotifyCurrentTracks = [];
let spotifySelectedTrack = null;
let spotifySearchDebounce = null;

// Función principal para búsqueda de Spotify
async function searchSpotify(query) {
    if (!query || query.trim() === '') {
        spotifyShowSuggestions();
        return;
    }

    try {
        // Mostrar loader
        spotifyShowLoader();

        const response = await fetch(`/spotify/search?query=${encodeURIComponent(query)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        if (response.ok && data.tracks) {
            spotifyCurrentTracks = data.tracks.items;
            spotifyDisplayResults(data.tracks.items);
        } else {
            spotifyShowError('No se encontraron resultados');
        }
    } catch (error) {
        console.error('Error en búsqueda:', error);
        spotifyShowError('Error al buscar en Spotify');
    }
}

// Función para seleccionar track
function spotifySelectTrack(track) {
    spotifySelectedTrack = track;

    // Actualizar campos del formulario
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

    // Disparar eventos personalizados para que el componente Blade los maneje
    document.dispatchEvent(new CustomEvent('spotify:trackSelected', {
        detail: { track }
    }));

    // Limpiar búsqueda
    spotifyClearSearch();

    showNotification(`${track.name} seleccionada`, 'success');
    updateSubmitButton();
}

// Función para mostrar loader
function spotifyShowLoader() {
    document.dispatchEvent(new CustomEvent('spotify:showLoader'));
}

// Función para mostrar resultados
function spotifyDisplayResults(tracks) {
    document.dispatchEvent(new CustomEvent('spotify:displayResults', {
        detail: { tracks }
    }));
}

// Función para mostrar sugerencias
function spotifyShowSuggestions() {
    document.dispatchEvent(new CustomEvent('spotify:showSuggestions'));
}

// Función para mostrar error
function spotifyShowError(message) {
    document.dispatchEvent(new CustomEvent('spotify:showError', {
        detail: { message }
    }));
    showNotification(message, 'error');
}

// Función para limpiar búsqueda
function spotifyClearSearch() {
    document.getElementById('spotify-search').value = '';
    document.dispatchEvent(new CustomEvent('spotify:clearSearch'));
}

// Función para limpiar selección
function spotifyClearSelection() {
    spotifySelectedTrack = null;

    const fieldNames = [
        'spotify_track_id', 'spotify_track_name', 'spotify_artist_name',
        'spotify_album_name', 'spotify_album_image', 'spotify_preview_url',
        'spotify_external_url'
    ];

    fieldNames.forEach(name => {
        const input = document.querySelector(`[name="${name}"]`);
        if (input) input.value = '';
    });

    document.dispatchEvent(new CustomEvent('spotify:trackCleared'));
    updateSubmitButton();
    showNotification('Selección eliminada', 'info');
}

// Función para reproducir preview
function spotifyTogglePreview(previewUrl, trackId) {
    if (!previewUrl) {
        showNotification('No hay preview disponible', 'warning');
        return;
    }

    // Usar el reproductor global
    toggleAudioPreview(previewUrl, trackId, 'spotify');
}

// Función para limpiar selección de track
window.clearSelectedTrack = function () {
    spotifyClearSelection();
};

// Función para realizar búsqueda desde sugerencias
window.performSearch = function (query) {
    document.getElementById('spotify-search').value = query;
    searchSpotify(query);
};

// Notificación flotante 
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `
        fixed top-6 right-6 z-50 px-5 py-3 rounded-xl shadow-lg transition-all duration-300 max-w-xs flex items-center gap-3
        bg-white border border-gray-200
    `.replace(/\s+/g, ' ');

    // Iconos tipo 
    let icon = '';
    switch (type) {
        case 'success':
            notification.classList.add('border-green-400');
            icon = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>`;
            break;
        case 'error':
            notification.classList.add('border-red-400');
            icon = `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>`;
            break;
        case 'warning':
            notification.classList.add('border-yellow-400');
            icon = `<svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01"/><circle cx="12" cy="12" r="10"/></svg>`;
            break;
        default:
            notification.classList.add('border-blue-400');
            icon = `<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>`;
    }

    notification.innerHTML = `
        ${icon}
        <span class="font-semibold text-gray-800">${message}</span>
    `;

    // Añadir al DOM
    document.body.appendChild(notification);

    // Animación de entrada
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(-20px)';
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 50);

    // Animación de salida y eliminación
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 2500);
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function () {
    // Event listeners para tabs
    document.getElementById('tab-imagen')?.addEventListener('click', () => switchTab('imagen'));
    document.getElementById('tab-musica')?.addEventListener('click', () => switchTab('musica'));

    // Event listener para búsqueda de iTunes con debounce
    const itunesSearch = document.getElementById('itunes-search');
    if (itunesSearch) {
        // Buscar con retardo al escribir
        itunesSearch.addEventListener('input', function (e) {
            clearTimeout(itunesSearchDebounce);
            itunesSearchDebounce = setTimeout(() => {
                searchiTunes(e.target.value.trim());
            }, 500);
        });

        // Mostrar sugerencias al enfocar si el campo está vacío
        itunesSearch.addEventListener('focus', function (e) {
            if (!e.target.value.trim()) {
                itunesShowSuggestions();
            }
        });
    }

    // Event listener para búsqueda de Spotify con debounce
    const spotifySearch = document.getElementById('spotify-search');
    if (spotifySearch) {
        // Buscar con retardo al escribir
        spotifySearch.addEventListener('input', function (e) {
            clearTimeout(spotifySearchDebounce);
            spotifySearchDebounce = setTimeout(() => {
                searchSpotify(e.target.value.trim());
            }, 500);
        });

        // Mostrar sugerencias al enfocar si el campo está vacío
        spotifySearch.addEventListener('focus', function (e) {
            if (!e.target.value.trim()) {
                spotifyShowSuggestions();
            }
        });
    }

    // Event listeners para campos de formulario para actualizar botón submit
    const tituloInput = document.getElementById('titulo');
    const descripcionInput = document.getElementById('descripcion');

    if (tituloInput) {
        tituloInput.addEventListener('input', updateSubmitButton);
        tituloInput.addEventListener('keyup', updateSubmitButton);
        tituloInput.addEventListener('change', updateSubmitButton);
    }

    if (descripcionInput) {
        descripcionInput.addEventListener('input', updateSubmitButton);
        descripcionInput.addEventListener('keyup', updateSubmitButton);
        descripcionInput.addEventListener('change', updateSubmitButton);
    }

    // Inicializar con imagen por defecto
    switchTab('imagen');

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
            // Si ya hay una imagen cargada, mostrarla como mockFile
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

    // Al subir imagen exitosamente, actualizar input
    dropzone.on("success", function (file, response) {
        document.querySelector('[name="imagen"]').value = response.imagen;
        updateSubmitButton();
    });

    // Al eliminar imagen, limpiar input
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
            // Permitir solo un archivo
            this.on('maxfilesexceeded', function (file) {
                this.removeAllFiles();
                this.addFile(file);
            });
            // Si ya hay imagen, mostrarla como mockFile
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

    // Al subir imagen exitosamente, actualizar input
    dropzoneRegister.on("success", function (file, response) {
        document.querySelector('[name="imagen"]').value = response.imagen;
    });

    // Al eliminar imagen, limpiar input
    dropzoneRegister.on("removedfile", function (file) {
        document.querySelector('[name="imagen"]').value = "";
    });

    // Manejar errores de subida
    dropzoneRegister.on("error", function (file, message) {
        console.error('Error al subir imagen:', message);
    });
}

// Exponer funciones globales para compatibilidad con componentes
window.selectTrack = function (track) {
    // Determinar si es iTunes o Spotify y llamar a la función correcta
    if (track.trackId) {
        itunesSelectTrack(track);
    } else {
        spotifySelectTrack(track);
    }
};
window.togglePreview = function (previewUrl, trackId) {
    // Determinar si es iTunes o Spotify
    if (typeof trackId === 'string' && trackId.length > 10) {
        itunesTogglePreview(previewUrl, trackId);
    } else {
        spotifyTogglePreview(previewUrl, trackId);
    }
};
window.showNotification = showNotification;
window.searchSpotify = searchSpotify;
window.searchiTunes = searchiTunes;

// Función global para reproducir preview de música (tanto iTunes como Spotify)
window.toggleMusicPreview = function (previewUrl, trackId, source) {
    if (source === 'itunes') {
        itunesTogglePreview(previewUrl, trackId);
    } else {
        spotifyTogglePreview(previewUrl, trackId);
    }
};

