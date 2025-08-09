<!-- Formulario simplificado para crear posts -->
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="main-form">
    @csrf
    <input type="hidden" name="tipo" id="post-tipo" value="imagen">

    <!-- Campo de título -->
    <div class="mb-4">
        <label for="titulo" class="block text-gray-700 font-medium mb-2">
            <span id="titulo-label">Título</span>
            <span id="titulo-optional" class="text-sm text-gray-500 font-normal hidden">(Opcional)</span>
        </label>
        <input type="text" id="titulo" name="titulo"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all {{ $errors->has('titulo') ? 'border-red-500' : '' }}"
            placeholder="Escribe un título para tu publicación..." value="{{ old('titulo') }}">
        @error('titulo')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Campo de descripción -->
    <div class="mb-6">
        <label for="descripcion" class="block text-gray-700 font-medium mb-2">
            <span id="descripcion-label">Descripción</span>
            <span id="descripcion-optional" class="text-sm text-gray-500 font-normal hidden">(Opcional)</span>
        </label>
        <textarea id="descripcion" name="descripcion" rows="3"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none {{ $errors->has('descripcion') ? 'border-red-500' : '' }}"
            placeholder="Cuéntanos más sobre tu publicación...">{{ old('descripcion') }}</textarea>
        @error('descripcion')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Campos ocultos para imagen -->
    <div id="imagen-fields">
        <input name="imagen" type="hidden" value="{{ old('imagen') }}">
        @error('imagen')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Campos ocultos para música -->
    <div id="musica-fields" class="hidden">
        <!-- Campo para especificar la fuente de música -->
        <input name="music_source" type="hidden" value="itunes">

        <!-- Campos iTunes -->
        <input name="itunes_track_id" type="hidden" value="{{ old('itunes_track_id') }}">
        <input name="itunes_track_name" type="hidden" value="{{ old('itunes_track_name') }}">
        <input name="itunes_artist_name" type="hidden" value="{{ old('itunes_artist_name') }}">
        <input name="itunes_collection_name" type="hidden" value="{{ old('itunes_collection_name') }}">
        <input name="itunes_artwork_url" type="hidden" value="{{ old('itunes_artwork_url') }}">
        <input name="itunes_preview_url" type="hidden" value="{{ old('itunes_preview_url') }}">
        <input name="itunes_track_view_url" type="hidden" value="{{ old('itunes_track_view_url') }}">
        <input name="itunes_track_time_millis" type="hidden" value="{{ old('itunes_track_time_millis') }}">
        <input name="itunes_country" type="hidden" value="{{ old('itunes_country') }}">
        <input name="itunes_primary_genre_name" type="hidden" value="{{ old('itunes_primary_genre_name') }}">
    </div>

    <!-- Botón de envío -->
    <div class="flex gap-3">
        <button type="submit" id="btn-submit" disabled
            class="flex-1 bg-blue-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-600 disabled:hover:bg-gray-300 flex items-center justify-center gap-2">
            <i class="fas fa-paper-plane"></i>
            <span id="submit-text">Publicar</span>
        </button>

        <a href="{{ route('home') }}"
            class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center">
            Cancelar
        </a>
    </div>

    <!-- Indicador de estado -->
    <div id="submit-status" class="mt-3 text-center text-sm text-gray-500 hidden">
        <i class="fas fa-info-circle mr-1"></i>
        <span id="status-message">Completa los campos requeridos para publicar</span>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded, initializing form components...');

        const form = document.getElementById('main-form');
        const submitBtn = document.getElementById('btn-submit');
        const submitText = document.getElementById('submit-text');
        const statusIndicator = document.getElementById('submit-status');
        const statusMessage = document.getElementById('status-message');

        console.log('Form elements found:', {
            form: !!form,
            submitBtn: !!submitBtn,
            submitText: !!submitText,
            statusIndicator: !!statusIndicator,
            statusMessage: !!statusMessage
        });

        // Función global para cambiar entre tabs - VERSION SIMPLE
        window.switchTab = function (type) {
            console.log('switchTab called with type:', type);
            // Actualizar campo hidden del tipo de post
            document.getElementById('post-tipo').value = type;

            // Actualizar labels según el tipo
            const tituloLabel = document.getElementById('titulo-label');
            const tituloOptional = document.getElementById('titulo-optional');
            const descripcionLabel = document.getElementById('descripcion-label');
            const descripcionOptional = document.getElementById('descripcion-optional');
            const tituloInput = document.getElementById('titulo');
            const descripcionInput = document.getElementById('descripcion');

            if (type === 'imagen') {
                tituloLabel.textContent = 'Título *';
                descripcionLabel.textContent = 'Descripción *';
                tituloOptional.classList.add('hidden');
                descripcionOptional.classList.add('hidden');

                // Actualizar placeholders para imagen
                tituloInput.placeholder = 'Escribe un título para tu imagen...';
                descripcionInput.placeholder = 'Describe tu imagen (obligatorio)...';
                tituloInput.setAttribute('required', 'true');
                descripcionInput.setAttribute('required', 'true');

                // Mostrar campos de imagen, ocultar música
                document.getElementById('imagen-fields').classList.remove('hidden');
                document.getElementById('musica-fields').classList.add('hidden');
            } else if (type === 'musica') {
                tituloLabel.textContent = 'Título';
                descripcionLabel.textContent = 'Descripción';
                tituloOptional.classList.remove('hidden');
                descripcionOptional.classList.remove('hidden');

                // Actualizar placeholders para música
                tituloInput.placeholder = 'Título personalizado (opcional)...';
                descripcionInput.placeholder = 'Comparte tus pensamientos sobre esta canción (opcional)...';
                tituloInput.removeAttribute('required');
                descripcionInput.removeAttribute('required');

                // Mostrar campos de música, ocultar imagen
                document.getElementById('imagen-fields').classList.add('hidden');
                document.getElementById('musica-fields').classList.remove('hidden');
            }

            // Mostrar/ocultar paneles de contenido sin animaciones
            document.querySelectorAll('.content-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            document.getElementById(`content-${type}`).classList.remove('hidden');

            console.log('switchTab completed, calling updateSubmitButton');
            // Solo actualizar estado del botón, no validaciones visuales
            updateSubmitButton();
        };

        // Función para actualizar el estado del botón de envío - VERSION DINAMICA
        window.updateSubmitButton = function () {
            console.log('window.updateSubmitButton called');
            const currentType = document.getElementById('post-tipo').value;
            let canSubmit = false;
            let message = '';
            let progress = 0;
            let requiredFields = [];
            let completedFields = [];

            if (currentType === 'imagen') {
                console.log('Processing imagen type');
                // Para imágenes: TODOS los campos son obligatorios
                const imagenInput = document.querySelector('input[name="imagen"]');
                const tituloInput = document.querySelector('input[name="titulo"]');
                const descripcionInput = document.querySelector('textarea[name="descripcion"]');

                const hasImage = imagenInput && imagenInput.value.trim() !== '';
                const hasTitle = tituloInput && tituloInput.value.trim() !== '';
                const hasDescription = descripcionInput && descripcionInput.value.trim() !== '';

                console.log('Image validation - hasImage:', hasImage, 'hasTitle:', hasTitle, 'hasDescription:', hasDescription);

                requiredFields = ['imagen', 'título', 'descripción'];
                if (hasImage) completedFields.push('imagen');
                if (hasTitle) completedFields.push('título');
                if (hasDescription) completedFields.push('descripción');

                progress = (completedFields.length / requiredFields.length) * 100;
                canSubmit = hasImage && hasTitle && hasDescription;

                console.log('Completed fields:', completedFields, 'Progress calculated:', progress);

                // Mensajes dinámicos según progreso
                if (completedFields.length === 0) {
                    message = 'Selecciona una imagen, agrega título y descripción';
                } else if (completedFields.length === 1) {
                    const missing = requiredFields.filter(field => !completedFields.includes(field));
                    message = `Faltan: ${missing.join(' y ')}`;
                } else if (completedFields.length === 2) {
                    const missing = requiredFields.filter(field => !completedFields.includes(field));
                    message = `Solo falta: ${missing[0]}`;
                } else {
                    message = 'Listo para publicar';
                }

            } else if (currentType === 'musica') {
                console.log('Processing musica type');
                // Para música: solo la canción es obligatoria
                const trackIdInput = document.querySelector('input[name="itunes_track_id"]');
                const trackNameInput = document.querySelector('input[name="itunes_track_name"]');
                const artistNameInput = document.querySelector('input[name="itunes_artist_name"]');

                const hasTrackId = trackIdInput && trackIdInput.value.trim() !== '';
                const hasTrackName = trackNameInput && trackNameInput.value.trim() !== '';
                const hasArtistName = artistNameInput && artistNameInput.value.trim() !== '';

                canSubmit = hasTrackId && hasTrackName && hasArtistName;
                progress = canSubmit ? 100 : 0;
                message = canSubmit ? 'Listo para publicar' : 'Selecciona una canción para continuar';
            } else {
                console.log('Unknown type:', currentType);
                message = 'Selecciona el tipo de publicación para comenzar';
                progress = 0;
            }

            console.log('Final values - canSubmit:', canSubmit, 'message:', message, 'progress:', progress);

            // Actualizar botón con animaciones suaves
            updateButtonState(canSubmit, message, progress);
        };

        // Función para actualizar el estado del botón - VERSION MINIMALISTA
        function updateButtonState(canSubmit, message, progress) {
            console.log('updateButtonState called - canSubmit:', canSubmit, 'progress:', progress);

            // Actualizar botón de forma simple
            submitBtn.disabled = !canSubmit;

            // Solo cambio básico de color
            if (canSubmit) {
                submitBtn.classList.remove('bg-gray-300');
                submitBtn.classList.add('bg-blue-500', 'hover:bg-blue-600');
            } else {
                submitBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                submitBtn.classList.add('bg-gray-300');
            }

            // Siempre mostrar el indicador de estado
            statusIndicator.classList.remove('hidden');

            // Actualizar mensaje simple
            if (message) {
                statusMessage.textContent = message;

                if (canSubmit) {
                    statusIndicator.className = 'mt-3 text-center text-sm text-green-600';
                    statusMessage.innerHTML = `<i class="fas fa-check-circle mr-1"></i>${message}`;
                } else {
                    statusIndicator.className = 'mt-3 text-center text-sm text-gray-500';
                    statusMessage.innerHTML = `<i class="fas fa-info-circle mr-1"></i>${message}`;
                }
            }

            // Mostrar barra de progreso para imágenes siempre
            const currentType = document.getElementById('post-tipo').value;
            console.log('Current type:', currentType);
            if (currentType === 'imagen') {
                console.log('Calling addProgressBar with progress:', progress);
                addProgressBar(progress);
            } else {
                console.log('Removing progress bar (not imagen type)');
                removeProgressBar();
            }
        }

        // Función simple para la barra de progreso
        function addProgressBar(progress) {
            console.log('Adding progress bar with progress:', progress);
            let progressBar = document.getElementById('progress-bar');
            if (!progressBar) {
                console.log('Creating new progress bar');
                progressBar = document.createElement('div');
                progressBar.id = 'progress-bar';
                progressBar.className = 'mt-2 w-full bg-gray-200 rounded-full h-2';
                progressBar.innerHTML = '<div id="progress-fill" class="bg-blue-500 h-2 rounded-full transition-all duration-300"></div>';

                console.log('StatusIndicator exists:', !!statusIndicator);
                if (statusIndicator) {
                    statusIndicator.appendChild(progressBar);
                    console.log('Progress bar added to statusIndicator');
                } else {
                    console.error('StatusIndicator not found!');
                }
            } else {
                console.log('Progress bar already exists');
            }

            const progressFill = document.getElementById('progress-fill');
            if (progressFill) {
                progressFill.style.width = `${progress}%`;
                console.log('Progress fill width set to:', progress + '%');
            } else {
                console.error('Progress fill not found!');
            }
        }

        // Función para remover barra de progreso
        function removeProgressBar() {
            const progressBar = document.getElementById('progress-bar');
            if (progressBar) {
                progressBar.remove();
            }
        }

        // Manejo del envío del formulario
        form.addEventListener('submit', function (e) {
            const submitBtn = document.getElementById('btn-submit');

            if (submitBtn.disabled) {
                e.preventDefault();
                return;
            }

            // Validación adicional antes del envío
            const currentType = document.getElementById('post-tipo').value;
            let shouldPrevent = false;
            let errorMessage = '';

            if (currentType === 'imagen') {
                const imagenInput = document.querySelector('input[name="imagen"]');
                const tituloInput = document.querySelector('input[name="titulo"]');
                const descripcionInput = document.querySelector('textarea[name="descripcion"]');

                const hasImage = imagenInput && imagenInput.value.trim() !== '';
                const hasTitle = tituloInput && tituloInput.value.trim() !== '';
                const hasDescription = descripcionInput && descripcionInput.value.trim() !== '';

                if (!hasImage || !hasTitle || !hasDescription) {
                    shouldPrevent = true;
                    if (!hasImage) errorMessage = 'Debes seleccionar una imagen';
                    else if (!hasTitle) errorMessage = 'Debes agregar un título';
                    else if (!hasDescription) errorMessage = 'Debes agregar una descripción';
                }
            } else if (currentType === 'musica') {
                const trackIdInput = document.querySelector('input[name="itunes_track_id"]');

                if (!trackIdInput || !trackIdInput.value.trim()) {
                    shouldPrevent = true;
                    errorMessage = 'Debes seleccionar una canción';
                }
            }

            if (shouldPrevent) {
                e.preventDefault();
                // Mostrar mensaje de error
                statusMessage.textContent = errorMessage;
                statusIndicator.className = 'mt-3 text-center text-sm text-red-600';
                statusMessage.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>' + errorMessage;
                statusIndicator.classList.remove('hidden');

                // Actualizar el estado del botón
                updateSubmitButton();
                return;
            }

            // Deshabilitar botón y mostrar estado de carga
            submitBtn.disabled = true;
            submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Publicando...';

            // Re-habilitar después de 5 segundos como fallback
            setTimeout(() => {
                submitBtn.disabled = false;
                submitText.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Publicar';
            }, 5000);
        });

        // Agregar event listeners para validación en tiempo real más dinámicos
        const tituloInput = document.getElementById('titulo');
        const descripcionInput = document.getElementById('descripcion');

        // Función para agregar todos los listeners a un input - VERSION MINIMALISTA
        function addRealtimeListeners(input, callback) {
            if (!input) return;

            // Solo eventos básicos de escritura
            input.addEventListener('input', () => {
                callback(); // Actualizar botón
                validateField(input); // Validar solo después de escribir
            });
            input.addEventListener('change', () => {
                callback(); // Actualizar botón
                validateField(input); // Validar solo después del cambio
            });
        }

        // Aplicar listeners simples
        addRealtimeListeners(tituloInput, updateSubmitButton);
        addRealtimeListeners(descripcionInput, updateSubmitButton);

        // Función para validar campo individual - VERSION SIMPLE SIN ROJO
        function validateField(field) {
            if (!field) return;

            const currentType = document.getElementById('post-tipo').value;
            const isEmpty = !field.value.trim();
            const isRequired = currentType === 'imagen';

            // Solo mostrar verde cuando esté completo, sino mantener gris
            field.classList.remove('border-red-500', 'border-green-500', 'border-gray-300');

            if (isRequired && !isEmpty) {
                // Solo mostrar verde si tiene contenido
                field.classList.add('border-green-500');
            } else {
                // Mantener borde normal (gris) en todos los demás casos
                field.classList.add('border-gray-300');
            }
        }

        // Observer mejorado para cambios en campos ocultos de música
        const observerCallback = function (mutationsList) {
            let shouldUpdate = false;
            for (let mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                    shouldUpdate = true;
                    break;
                }
            }

            if (shouldUpdate) {
                // Actualización inmediata sin retraso
                updateSubmitButton();
            }
        };

        const observer = new MutationObserver(observerCallback);

        // Observar cambios en los campos ocultos de música con mejor configuración
        const musicFields = document.querySelectorAll('#musica-fields input[type="hidden"]');
        musicFields.forEach(field => {
            observer.observe(field, {
                attributes: true,
                attributeFilter: ['value'],
                attributeOldValue: true
            });

            // Listeners adicionales para mejor compatibilidad
            field.addEventListener('input', updateSubmitButton);
            field.addEventListener('change', updateSubmitButton);

            // Listener personalizado para cambios programáticos
            field.addEventListener('valueChanged', updateSubmitButton);
        });

        // Escuchar eventos personalizados de iTunes con respuesta inmediata
        document.addEventListener('itunes:trackSelected', () => {
            setTimeout(updateSubmitButton, 10); // Mínimo retraso para asegurar que los campos se actualicen
        });

        document.addEventListener('itunes:trackCleared', () => {
            setTimeout(updateSubmitButton, 10);
        });

        // Inicializar estado simple sin validación visual inicial
        console.log('Initializing form...');

        // Inicializar tab de imagen por defecto
        switchTab('imagen');

        // Luego actualizar estado del botón
        updateSubmitButton();

        // Asegurar que el status indicator esté visible desde el inicio
        statusIndicator.classList.remove('hidden');

        console.log('Form initialization complete');
    });
</script>