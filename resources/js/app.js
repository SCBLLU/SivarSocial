// Importo dropzone para manejo de imagenes
import Dropzone from "dropzone";
// Desactivo autodiscover para evitar conflictos
Dropzone.autoDiscover = false;
// Variables globales para el manejo de posts
let currentPostType = 'imagen'; // Tipo de post actual (imagen o musica)
// Función para cambiar entre tabs
function switchTab(type) {
    currentPostType = type;

    // Verificar que los elementos existan antes de manipularlos
    const tabButton = document.getElementById(`tab-${type}`);
    const contentPanel = document.getElementById(`content-${type}`);
    const postTipoField = document.getElementById('post-tipo');

    if (!tabButton || !contentPanel || !postTipoField) {
        return; // Salir si los elementos no existen
    }

    // Actualizar apariencia de tabs
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    tabButton.classList.add('active');

    // Mostrar/ocultar contenido de cada tab
    document.querySelectorAll('.content-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    contentPanel.classList.remove('hidden');

    // Actualizar campo hidden del tipo de post
    postTipoField.value = type;

    // Obtener elementos comunes
    const imagenFields = document.getElementById('imagen-fields');
    const musicaFields = document.getElementById('musica-fields');
    const archivoFields = document.getElementById('archivo-fields');
    const tituloContainer = document.querySelector('#titulo').closest('.mb-4'); // Contenedor del título
    const descripcionContainer = document.getElementById('descripcion-container');
    const textoContainer = document.getElementById('texto-container');
    const tituloOptional = document.getElementById('titulo-optional');
    const descripcionOptional = document.getElementById('descripcion-optional');
    const tituloInput = document.getElementById('titulo');
    const descripcionInput = document.getElementById('descripcion');

    // Mostrar/ocultar campos del formulario según el tipo de post
    if (type === 'imagen') {
        if (imagenFields) imagenFields.classList.remove('hidden');
        if (musicaFields) musicaFields.classList.add('hidden');
        if (archivoFields) archivoFields.classList.add('hidden');
        if (tituloContainer) tituloContainer.classList.remove('hidden');
        if (descripcionContainer) descripcionContainer.classList.remove('hidden');
        if (textoContainer) textoContainer.classList.add('hidden');
        if (tituloOptional) tituloOptional.classList.add('hidden');
        if (descripcionOptional) descripcionOptional.classList.remove('hidden');

        // Cambiar placeholders para imagen
        if (tituloInput) tituloInput.placeholder = 'Título de la publicación';
        if (descripcionInput) descripcionInput.placeholder = 'Describe tu publicación (opcional)';
    } else if (type === 'musica') {
        if (imagenFields) imagenFields.classList.add('hidden');
        if (musicaFields) musicaFields.classList.remove('hidden');
        if (archivoFields) archivoFields.classList.add('hidden');
        if (tituloContainer) tituloContainer.classList.remove('hidden');
        if (descripcionContainer) descripcionContainer.classList.remove('hidden');
        if (textoContainer) textoContainer.classList.add('hidden');
        if (tituloOptional) tituloOptional.classList.remove('hidden');
        if (descripcionOptional) descripcionOptional.classList.remove('hidden');

        // Cambiar placeholders para música
        if (tituloInput) tituloInput.placeholder = 'Título personalizado (opcional)';
        if (descripcionInput) descripcionInput.placeholder = 'Describe la música (opcional)';
    } else if (type === 'texto') {
        if (imagenFields) imagenFields.classList.add('hidden');
        if (musicaFields) musicaFields.classList.add('hidden');
        if (archivoFields) archivoFields.classList.add('hidden');
        if (tituloContainer) tituloContainer.classList.add('hidden'); // 🔥 OCULTAR TÍTULO
        if (descripcionContainer) descripcionContainer.classList.add('hidden');
        if (textoContainer) textoContainer.classList.remove('hidden');

        // No se necesitan placeholders porque el título está oculto
    } else if (type === 'archivo') {
        if (imagenFields) imagenFields.classList.add('hidden');
        if (musicaFields) musicaFields.classList.add('hidden');
        if (archivoFields) archivoFields.classList.remove('hidden');
        if (tituloContainer) tituloContainer.classList.remove('hidden');
        if (descripcionContainer) descripcionContainer.classList.remove('hidden');
        if (textoContainer) textoContainer.classList.add('hidden');
        if (tituloOptional) tituloOptional.classList.remove('hidden');
        if (descripcionOptional) descripcionOptional.classList.remove('hidden');

        // Cambiar placeholders para archivo
        if (tituloInput) tituloInput.placeholder = 'Título del archivo (opcional)';
        if (descripcionInput) descripcionInput.placeholder = 'Describe el archivo (opcional)';
    }

    // Resetear validación del botón submit
    updateSubmitButton();
}

// Exportar la función para uso global
window.switchTab = switchTab;
// Función para actualizar el estado del botón submit - VERSION SIMPLE
function updateSubmitButton() {
    const submitBtn = document.getElementById('btn-submit');
    let canSubmit = false;
    // Validación según el tipo de post
    if (currentPostType === 'imagen') {
        const imagenInput = document.querySelector('[name="imagen"]');
        const tituloInput = document.querySelector('[name="titulo"]');
        const descripcionInput = document.querySelector('[name="descripcion"]');
        // Para imagen: requiere imagen y título, descripción es opcional
        const hasImage = imagenInput && imagenInput.value.trim() !== '';
        const hasTitle = tituloInput && tituloInput.value.trim() !== '';
        const hasDescription = descripcionInput && descripcionInput.value.trim() !== '';
        canSubmit = hasImage && hasTitle; // Descripción ya no es requerida
    } else if (currentPostType === 'musica') {
        const trackIdInput = document.querySelector('[name="itunes_track_id"]');
        const hasTrack = trackIdInput && trackIdInput.value.trim() !== '';
        canSubmit = hasTrack;
    } else if (currentPostType === 'texto') {
        const textoInput = document.querySelector('[name="texto"]');
        const hasTexto = textoInput && textoInput.value.trim() !== '';
        canSubmit = hasTexto;
    } else if (currentPostType === 'archivo') {
        const archivoInput = document.querySelector('[name="archivo"]');
        const hasArchivo = archivoInput && archivoInput.value.trim() !== '';
        canSubmit = hasArchivo;
    }
    // Habilitar/deshabilitar botón submit de forma simple
    if (submitBtn) {
        submitBtn.disabled = !canSubmit;
    } else {
    }
    // Llamar a la función del formulario si existe
    if (typeof window.updateSubmitButton === 'function' && window.updateSubmitButton !== updateSubmitButton) {
        window.updateSubmitButton();
    }
}
// REPRODUCTOR DE AUDIO GLOBAL
let currentAudio = null;
let currentTrackId = null;
// Función para guardar el estado actual del audio
function saveAudioState() {
    if (currentAudio && currentTrackId && !currentAudio.paused) {
        const audioState = {
            trackId: currentTrackId,
            previewUrl: currentAudio.src,
            currentTime: currentAudio.currentTime,
            isPlaying: !currentAudio.paused,
            timestamp: Date.now()
        };
        sessionStorage.setItem('sivarsocial_audio_state', JSON.stringify(audioState));
    }
}
// Función para restaurar el estado del audio
function restoreAudioState() {
    // Verificar si estamos en una página de perfil - NO restaurar audio en perfiles
    const currentPath = window.location.pathname;
    // Detectar páginas de perfil:
    // - /editar-perfil
    // - /{username} (rutas de perfil de usuario)
    // Excluir: /posts/create, /{username}/posts/{id}, etc.
    const isEditProfile = currentPath === '/editar-perfil';
    const isUserProfile = currentPath.split('/').length === 2 &&
        currentPath !== '/' &&
        !currentPath.includes('/posts') &&
        !currentPath.includes('/login') &&
        !currentPath.includes('/register') &&
        !currentPath.includes('/spotify') &&
        !currentPath.includes('/itunes') &&
        !currentPath.includes('/imagenes');
    if (isEditProfile || isUserProfile) {
        // Si estamos en un perfil, limpiar el estado sin restaurar
        sessionStorage.removeItem('sivarsocial_audio_state');
        return;
    }
    const savedState = sessionStorage.getItem('sivarsocial_audio_state');
    if (savedState) {
        try {
            const audioState = JSON.parse(savedState);
            // Solo restaurar si la sesión es reciente (menos de 5 minutos)
            const timeDiff = Date.now() - audioState.timestamp;
            if (timeDiff < 300000 && audioState.isPlaying) { // 5 minutos = 300000ms
                // Buscar el botón del track correspondiente
                const playButton = document.querySelector(`.play-button-${audioState.trackId}`);
                if (playButton && audioState.previewUrl) {
                    // Restaurar audio con el tiempo guardado
                    setTimeout(() => {
                        toggleAudioPreview(audioState.previewUrl, audioState.trackId, 'restore');
                        // Saltar al tiempo guardado después de que el audio se cargue
                        if (currentAudio) {
                            currentAudio.addEventListener('canplay', function () {
                                if (audioState.currentTime > 0) {
                                    currentAudio.currentTime = audioState.currentTime;
                                }
                            }, { once: true });
                        }
                    }, 200); // Reducido de 500ms a 200ms
                }
            }
            // Limpiar el estado guardado después de restaurar o si es muy viejo
            sessionStorage.removeItem('sivarsocial_audio_state');
        } catch (error) {
            sessionStorage.removeItem('sivarsocial_audio_state');
        }
    }
    // Solo intentar restaurar el estado local si existe la función Y no viene de navegación externa
    if (typeof window.restoreLocalAudioState === 'function') {
        // Verificar si viene de una navegación interna apropiada
        const referrer = document.referrer;
        const isInternalNavigation = referrer && referrer.includes(window.location.origin);
        const isNotFromList = !referrer.includes('/posts') || referrer.includes('/posts/');
        if (isInternalNavigation && isNotFromList) {
            window.restoreLocalAudioState();
        }
    }
}
// Función global para pausar todo el audio
function pauseAllAudio() {
    // Pausar el reproductor global de app.js
    if (currentAudio && !currentAudio.paused) {
        saveAudioState(); // Guardar estado antes de pausar
        currentAudio.pause();
        updatePlayButton(currentTrackId, false);
        currentTrackId = null;
    }
    // También pausar cualquier reproductor local en show.blade.php
    if (typeof window.pauseAudio === 'function') {
        window.pauseAudio();
    }
    // Guardar estado del reproductor local si existe
    if (typeof window.saveLocalAudioState === 'function') {
        window.saveLocalAudioState();
    }
}
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
            // Error de reproducción manejado silenciosamente
        }).then(() => {
            // Guardar estado cuando empiece a reproducir
            if (source !== 'restore') {
                saveAudioState();
            }
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
// Función global para reducir el tiempo de búsqueda con debounce más agresivo
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
        itunesShowError('Error al buscar');
    }
}
// Función para seleccionar track de iTunes - VERSION DINAMICA
function itunesSelectTrack(track) {
    itunesSelectedTrack = track;
    // Actualizar campos del formulario de forma más eficiente
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
    // Actualizar campos con eventos personalizados para mejor reactividad
    Object.entries(fields).forEach(([name, value]) => {
        const input = document.querySelector(`[name="${name}"]`);
        if (input) {
            input.value = value;
            // Disparar evento personalizado para notificar el cambio
            input.dispatchEvent(new Event('valueChanged'));
        }
    });
    // Limpiar búsqueda
    itunesClearSearch();
    // Disparar evento personalizado inmediatamente
    document.dispatchEvent(new CustomEvent('itunes:trackSelected', { detail: { track: track } }));
    showNotification(`${track.trackName} seleccionada`, 'success');
    // Actualizar botón submit de forma inmediata
    if (typeof updateSubmitButton === 'function') {
        updateSubmitButton();
    }
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
// Función para limpiar selección - VERSION DINAMICA
function itunesClearSelection() {
    itunesSelectedTrack = null;
    const fieldNames = [
        'itunes_track_id', 'itunes_track_name', 'itunes_artist_name',
        'itunes_collection_name', 'itunes_artwork_url', 'itunes_preview_url',
        'itunes_track_view_url', 'itunes_track_time_millis', 'itunes_country',
        'itunes_primary_genre_name'
    ];
    // Limpiar campos con eventos personalizados
    fieldNames.forEach(name => {
        const input = document.querySelector(`[name="${name}"]`);
        if (input) {
            input.value = '';
            // Disparar evento personalizado para notificar el cambio
            input.dispatchEvent(new Event('valueChanged'));
        }
    });
    // También limpiar el music_source si era iTunes
    const musicSourceInput = document.querySelector('[name="music_source"]');
    if (musicSourceInput && musicSourceInput.value === 'itunes') {
        musicSourceInput.value = '';
        musicSourceInput.dispatchEvent(new Event('valueChanged'));
    }
    // Disparar evento personalizado inmediatamente
    document.dispatchEvent(new CustomEvent('itunes:trackCleared'));
    // Actualizar botón submit de forma inmediata
    if (typeof updateSubmitButton === 'function') {
        updateSubmitButton();
    }
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
    // Limpiar selección de iTunes
    if (itunesSelectedTrack) {
        itunesClearSelection();
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

// Exponer funciones globalmente para que los componentes puedan usarlas
window.showNotification = showNotification;

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function () {
    // Event listeners para tabs
    document.getElementById('tab-imagen')?.addEventListener('click', () => switchTab('imagen'));
    document.getElementById('tab-musica')?.addEventListener('click', () => switchTab('musica'));
    document.getElementById('tab-texto')?.addEventListener('click', () => switchTab('texto'));
    document.getElementById('tab-archivo')?.addEventListener('click', () => switchTab('archivo'));

    // Event listener para el campo de texto
    const textoInput = document.getElementById('texto');
    if (textoInput) {
        textoInput.addEventListener('input', updateSubmitButton);
    }

    // Exponer handleArchivoUpload globalmente para que los componentes puedan usarlo
    window.handleArchivoUpload = handleArchivoUpload;

    // Función para manejar la subida de archivos
    function handleArchivoUpload(file) {
        // Validar tamaño (10MB máximo)
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            showNotification('El archivo es demasiado grande. Máximo 10MB', 'error');
            return;
        }

        // Validar tipo de archivo
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain'
        ];

        if (!allowedTypes.includes(file.type)) {
            showNotification('Tipo de archivo no permitido. Use PDF, DOC, DOCX, XLS, XLSX o TXT', 'error');
            return;
        }

        // Mostrar preview
        const archivoNombre = document.getElementById('archivo-nombre');
        const archivoTamano = document.getElementById('archivo-tamano');
        const archivoPreview = document.getElementById('archivo-preview');
        const archivoDropzone = document.getElementById('file-dropzone');

        if (archivoNombre && archivoTamano && archivoPreview) {
            archivoNombre.textContent = file.name;
            archivoTamano.textContent = formatFileSize(file.size);
            archivoPreview.classList.remove('hidden');
            if (archivoDropzone) {
                archivoDropzone.classList.add('hidden');
            }
        }

        // Subir archivo al servidor
        const formData = new FormData();
        formData.append('archivo', file);

        // Mostrar indicador de carga
        const loadingNotification = showNotification('Subiendo archivo...', 'info');

        // Deshabilitar el botón de envío mientras se sube
        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Subiendo...';
        }

        fetch('/archivos', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Error al subir el archivo');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.archivo) {
                    // Guardar nombre del archivo en el servidor en el campo hidden
                    const archivoHidden = document.querySelector('[name="archivo"]');
                    const archivoNombreOriginalHidden = document.querySelector('[name="archivo_nombre_original"]');

                    if (archivoHidden) {
                        archivoHidden.value = data.archivo;
                    }

                    // Guardar el nombre original del archivo
                    if (archivoNombreOriginalHidden && data.nombre_original) {
                        archivoNombreOriginalHidden.value = data.nombre_original;
                    }

                    updateSubmitButton();
                    showNotification('Archivo subido correctamente', 'success');
                } else {
                    throw new Error('Respuesta inválida del servidor');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(error.message || 'Error al subir el archivo', 'error');
                // Limpiar preview en caso de error
                if (archivoPreview) archivoPreview.classList.add('hidden');
                if (archivoDropzone) archivoDropzone.classList.remove('hidden');
                const archivoHidden = document.querySelector('[name="archivo"]');
                if (archivoHidden) archivoHidden.value = '';
            })
            .finally(() => {
                // Rehabilitar el botón de envío
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Publicar';
                }
            });
    }

    // Función para formatear el tamaño del archivo
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Exponer formatFileSize globalmente para que los componentes puedan usarlo
    window.formatFileSize = formatFileSize;

    // Event listener para búsqueda de iTunes con debounce
    const itunesSearch = document.getElementById('itunes-search');
    if (itunesSearch) {
        // Buscar con retardo al escribir (más rápido)
        itunesSearch.addEventListener('input', function (e) {
            clearTimeout(itunesSearchDebounce);
            itunesSearchDebounce = setTimeout(() => {
                searchiTunes(e.target.value.trim());
            }, 300); // Reducido de 500ms a 300ms para respuesta más rápida
        });
        // Mostrar sugerencias al enfocar si el campo está vacío
        itunesSearch.addEventListener('focus', function (e) {
            if (!e.target.value.trim()) {
                itunesShowSuggestions();
            }
        });
    }
    // Event listeners para campos de formulario - VERSION SIMPLE
    const tituloInput = document.getElementById('titulo');
    const descripcionInput = document.getElementById('descripcion');
    // Listeners básicos sin efectos
    if (tituloInput) {
        tituloInput.addEventListener('input', updateSubmitButton);
        tituloInput.addEventListener('change', updateSubmitButton);
    }
    if (descripcionInput) {
        descripcionInput.addEventListener('input', updateSubmitButton);
        descripcionInput.addEventListener('change', updateSubmitButton);
    }
    // Inicializar con imagen por defecto - con verificación
    setTimeout(() => {
        switchTab('imagen');
    }, 100);
    // Restaurar estado del audio al cargar la página
    setTimeout(() => {
        restoreAudioState();
    }, 300); // Reducido de 1000ms a 300ms para respuesta más rápida
    // Guardar estado del audio periódicamente mientras se reproduce
    setInterval(() => {
        if (currentAudio && !currentAudio.paused && currentTrackId) {
            saveAudioState();
        }
    }, 1500); // Reducido de 2000ms a 1500ms para mejor sincronización
});
// DROPZONE PARA CREAR POSTS - NUEVA INTERFAZ SIMPLIFICADA
if (document.getElementById('dropzone')) {
    let dropzone = new Dropzone('#dropzone', {
        url: '/imagenes',
        dictDefaultMessage: 'Sube tu imagen aquí',
        acceptedFiles: '.jpg,.jpeg,.png',
        addRemoveLinks: false, // Los controles están en la nueva UI
        maxFiles: 1,
        maxFilesize: 20,
        uploadMultiple: false,
        paramName: 'imagen',
        autoProcessQueue: true,
        createImageThumbnails: false, // No necesitamos thumbnails de Dropzone
        previewTemplate: '<div style="display:none;"></div>', // Ocultar preview de Dropzone
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function () {
            // Exponer instancia globalmente para uso desde otros scripts
            window.dropzoneInstance = this;
            // Si ya hay una imagen cargada, configurar estado
            const imagenInput = document.querySelector('[name="imagen"]');
            if (imagenInput && imagenInput.value.trim()) {
                const mockFile = {
                    name: imagenInput.value,
                    size: 1234,
                    status: Dropzone.SUCCESS
                };
                this.files.push(mockFile);
                this.emit('complete', mockFile);
                updateSubmitButton();
            }
        },
        // Interceptar cuando se añade un archivo
        addedfile: function (file) {
            // No hacer nada aquí, la UI personalizada maneja el preview
        },
        // Cuando se procesa un archivo
        processing: function (file) {
            if (typeof showNotification === 'function') {
                showNotification('Procesando imagen...', 'info');
            }
        },
        // Éxito en la subida
        success: function (file, response) {
            // Actualizar campo hidden
            const imagenInput = document.querySelector('[name="imagen"]');
            if (imagenInput) {
                imagenInput.value = response.imagen;
            }
            // Actualizar botón submit
            if (typeof updateSubmitButton === 'function') {
                updateSubmitButton();
            }
            // Mostrar notificación
            if (typeof showNotification === 'function') {
                showNotification(response.message || 'Imagen subida correctamente', 'success');
            }
        },
        // Error en la subida
        error: function (file, errorMessage) {
            if (typeof showNotification === 'function') {
                const message = typeof errorMessage === 'string' ? errorMessage : 'Error al subir imagen';
                showNotification(message, 'error');
            }
            // Limpiar archivo fallido
            this.removeFile(file);
        },
        // Archivo removido
        removedfile: function (file) {
            // Limpiar campo hidden
            const imagenInput = document.querySelector('[name="imagen"]');
            if (imagenInput) {
                imagenInput.value = '';
            }
            // Actualizar botón submit
            if (typeof updateSubmitButton === 'function') {
                updateSubmitButton();
            }
        }
    });
}
// DROPZONE PARA REGISTRO DE USUARIO
if (document.getElementById('dropzone-register')) {
    let dropzoneRegister = new Dropzone('#dropzone-register', {
        url: '/imagenes-perfil',
        dictDefaultMessage: 'Arrastra aquí tu imagen de perfil o haz clic',
        acceptedFiles: '.jpg,.jpeg,.png',
        addRemoveLinks: true,
        dictRemoveFile: 'Eliminar',
        maxFiles: 1,
        maxFilesize: 20,
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
    });
}
// Exponer funciones globales para compatibilidad con componentes
window.selectTrack = function (track) {
    // Solo iTunes ahora
    itunesSelectTrack(track);
};
window.togglePreview = function (previewUrl, trackId) {
    // Solo iTunes ahora
    itunesTogglePreview(previewUrl, trackId);
};
window.pauseAllAudio = pauseAllAudio;
window.restoreAudioState = restoreAudioState;
window.saveAudioState = saveAudioState;
window.showNotification = showNotification;
window.searchiTunes = searchiTunes;
// Función global para reproducir preview de música (solo iTunes)
window.toggleMusicPreview = function (previewUrl, trackId, source) {
    itunesTogglePreview(previewUrl, trackId);
};

//Titulo de canción largo
document.addEventListener("DOMContentLoaded", function () {
    const wrappers = document.querySelectorAll(".scrollable-title-wrapper");

    let waiting = new Set();

    wrappers.forEach((wrapper, index) => {
        const original = wrapper.querySelector(".track-title:not(.clone)");

        function checkScroll() {
            const clone = wrapper.querySelector(".clone");

            if (original.scrollWidth <= wrapper.parentElement.clientWidth) {
                wrapper.classList.add("no-scroll");
                wrapper.classList.remove("scroll");

                if (clone) clone.style.display = "none";

                // si no hace falta scroll, asegurarse que no quede en sincronización
                waiting.delete(index);
                wrapper.style.animationPlayState = "running"; // evitar pausa innecesaria
            } else {
                wrapper.classList.add("scroll");
                wrapper.classList.remove("no-scroll");

                if (clone) {
                    clone.style.display = "inline-block";
                } else {
                    const newClone = original.cloneNode(true);
                    newClone.classList.add("clone");
                    wrapper.appendChild(newClone);
                }
            }
        }

        checkScroll();

        const resizeObserver = new ResizeObserver(checkScroll);
        resizeObserver.observe(wrapper.parentElement);

        wrapper.addEventListener("animationiteration", () => {
            // ⚡ solo sincronizar si este realmente necesita scroll
            if (!wrapper.classList.contains("scroll")) return;

            waiting.add(index);

            // si todos los que sí scrolleaban llegaron al 0%
            const scrollingWrappers = Array.from(wrappers)
                .map((w, i) => w.classList.contains("scroll") ? i : null)
                .filter(i => i !== null);

            if (scrollingWrappers.every(i => waiting.has(i))) {
                waiting.clear();
                wrappers.forEach(w => {
                    if (w.classList.contains("scroll")) {
                        w.style.animationPlayState = "running";
                    }
                });
            } else {
                wrapper.style.animationPlayState = "paused";
            }
        });
    });
});
