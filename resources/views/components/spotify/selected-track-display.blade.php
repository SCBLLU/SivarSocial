<!-- Componente para mostrar la canción seleccionada -->
<div class="selected-track-card rounded-2xl p-6 border-2 shadow-2xl relative overflow-hidden">
    <!-- Fondo dinámico con color dominante -->
    <div class="absolute inset-0" id="selected-track-bg"></div>

    <!-- Contenido -->
    <div class="relative z-10">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-white font-bold text-lg flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
                Canción seleccionada
            </h4>

            <button onclick="clearSelectedTrack()"
                class="text-white/70 hover:text-white transition-colors p-1 rounded-full hover:bg-white/10">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Información de la canción -->
        <div class="flex items-center gap-4 mb-4">
            <div class="relative">
                <img id="selected-track-image" src="" alt="Portada del álbum"
                    class="w-20 h-20 rounded-xl object-cover shadow-lg">

                <!-- Overlay de música -->
                <div
                    class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <h5 class="text-white font-bold text-xl truncate" id="selected-track-name"></h5>
                <p class="text-white/80 text-lg truncate" id="selected-track-artist"></p>
                <p class="text-white/60 text-sm truncate" id="selected-track-album"></p>
                <p class="text-white/50 text-xs mt-1" id="selected-track-duration"></p>
            </div>
        </div>

        <!-- Controles de reproducción -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Botón de preview -->
                <button id="selected-track-play"
                    class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white play-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                            clip-rule="evenodd" />
                    </svg>
                    <svg class="w-6 h-6 text-white pause-icon hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Información adicional -->
                <div class="text-white/60 text-sm">
                    <p>Vista previa de 30s</p>
                </div>
            </div>

            <!-- Enlace a Spotify -->
            <a id="selected-track-spotify" href="#" target="_blank"
                class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm font-medium transition-all backdrop-blur-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.84-.179-.84-.66 0-.42.179-.78.54-.899 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.242 1.14zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.48.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.42 1.56-.299.421-1.02.599-1.559.3z" />
                </svg>
                Ver en Spotify
            </a>
        </div>

        <!-- Barra de progreso (opcional) -->
        <div class="mt-4 hidden" id="progress-container">
            <div class="w-full bg-white/20 rounded-full h-2">
                <div class="bg-white h-2 rounded-full transition-all duration-100" style="width: 0%" id="progress-bar">
                </div>
            </div>
            <div class="flex justify-between text-white/60 text-xs mt-1">
                <span id="current-time">0:00</span>
                <span id="total-time">0:00</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos específicos para la canción seleccionada */
    .selected-track-card {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%);
        border-color: #10b981;
        animation: selectedTrackGlow 3s ease-in-out infinite alternate;
    }

    @keyframes selectedTrackGlow {
        0% {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
        }

        100% {
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.6);
        }
    }

    .selected-track-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .selected-track-card {
            padding: 1rem;
        }

        .selected-track-card h5 {
            font-size: 1.125rem;
        }

        .selected-track-card p {
            font-size: 0.875rem;
        }
    }
</style>

<script>
    // Script para manejar la canción seleccionada
    function displaySelectedTrack(track) {
        const container = document.getElementById('selected-track');
        if (!container) return;

        // Crear el HTML de la canción seleccionada
        const selectedTrackHTML = `
        <!-- Canción seleccionada con el template anterior -->
    `;

        container.innerHTML = selectedTrackHTML;

        // Rellenar la información
        document.getElementById('selected-track-image').src = track.image || '/img/usuario.svg';
        document.getElementById('selected-track-name').textContent = track.name;
        document.getElementById('selected-track-artist').textContent = track.artist;
        document.getElementById('selected-track-album').textContent = track.album;
        document.getElementById('selected-track-spotify').href = track.external_url || '#';

        if (track.duration_ms) {
            document.getElementById('selected-track-duration').textContent = formatDuration(track.duration_ms);
        }

        // Configurar el botón de reproducción
        const playButton = document.getElementById('selected-track-play');
        if (playButton && track.preview_url) {
            playButton.onclick = () => togglePreview(track.preview_url, playButton);
        } else if (playButton) {
            playButton.classList.add('opacity-50', 'cursor-not-allowed');
        }

        // Aplicar color dominante si está disponible
        if (track.dominant_color) {
            applyDominantColor(track.dominant_color);
        }

        // Animar la entrada
        container.classList.add('animate-fade-in');
    }

    function applyDominantColor(color) {
        const bgElement = document.getElementById('selected-track-bg');
        if (bgElement) {
            const lighterColor = adjustBrightness(color, 20);
            bgElement.style.background = `linear-gradient(135deg, ${color} 0%, ${lighterColor} 100%)`;
        }
    }

    function adjustBrightness(hex, percent) {
        // Función para ajustar brillo del color
        const num = parseInt(hex.replace("#", ""), 16),
            amt = Math.round(2.55 * percent),
            R = (num >> 16) + amt,
            G = (num >> 8 & 0x00FF) + amt,
            B = (num & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
    }

    // Limpiar selección
    window.clearSelectedTrack = function () {
        const container = document.getElementById('selected-track');
        if (container) {
            container.innerHTML = '';
        }

        // Limpiar campos del formulario
        const fields = [
            'spotify_track_id', 'spotify_track_name', 'spotify_artist_name',
            'spotify_album_name', 'spotify_album_image', 'spotify_preview_url',
            'spotify_external_url', 'dominant_color'
        ];

        fields.forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) input.value = '';
        });

        selectedTrack = null;
        updateSubmitButton();
        showNotification('Selección eliminada', 'info');
    };
</script>