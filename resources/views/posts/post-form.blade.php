<!-- Componente del formulario de creación de posts -->
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="main-form">
    @csrf
    <input type="hidden" name="tipo" id="post-tipo" value="imagen">

    <!-- Campo de título -->
    <div class="mb-6">
        <label for="titulo" class="mb-2 block uppercase text-gray-500 font-bold text-sm">
            <span id="titulo-label">Título</span>
            <span id="titulo-optional" class="text-xs text-gray-400 normal-case hidden">(Opcional para música)</span>
        </label>
        <input type="text" id="titulo" name="titulo"
            class="border-2 p-4 w-full rounded-xl text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all {{ $errors->has('titulo') ? 'border-red-500' : 'border-gray-300' }}"
            placeholder="Título de la publicación" value="{{ old('titulo') }}">
        @error('titulo')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Campo de descripción -->
    <div class="mb-6">
        <label for="descripcion" class="mb-2 block uppercase text-gray-500 font-bold text-sm">
            <span id="descripcion-label">Descripción</span>
            <span id="descripcion-optional" class="text-xs text-gray-400 normal-case hidden">(Opcional para
                música)</span>
        </label>
        <textarea id="descripcion" name="descripcion" rows="4"
            class="border-2 p-4 w-full rounded-xl text-black focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none {{ $errors->has('descripcion') ? 'border-red-500' : 'border-gray-300' }}"
            placeholder="Describe tu publicación...">{{ old('descripcion') }}</textarea>
        @error('descripcion')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Campos ocultos para imagen -->
    <div id="imagen-fields">
        <input name="imagen" type="hidden" value="{{ old('imagen') }}">
        @error('imagen')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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
        
        @error('itunes_track_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
        @error('music')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Botón de envío -->
    <button type="submit" id="btn-submit"
        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all cursor-pointer uppercase font-bold w-full p-4 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
        disabled>
        <span class="flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            <span id="btn-text">Crear publicación</span>
        </span>
    </button>
</form>

@push('styles')
    <style>
        /* Estilos para el formulario */
        .form-field {
            transition: all 0.3s ease;
        }

        .form-field:focus-within label {
            color: #3b82f6;
            transform: translateY(-2px);
        }

        /* Estilos para inputs con error */
        .border-red-500 {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
        }

        /* Animación del botón submit */
        #btn-submit {
            position: relative;
            overflow: hidden;
        }

        #btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        #btn-submit:hover::before {
            left: 100%;
        }

        #btn-submit:disabled::before {
            display: none;
        }

        /* Estados del botón */
        #btn-submit.loading {
            pointer-events: none;
        }

        #btn-submit.loading span {
            opacity: 0.7;
        }

        #btn-submit.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin-top: -10px;
            margin-left: -10px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
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

        /* Contador de caracteres para textarea */
        .char-counter {
            font-size: 0.75rem;
            color: #9ca3af;
            text-align: right;
            margin-top: 0.25rem;
        }

        /* Animación para campos con error */
        .error-shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Script específico para el formulario
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('main-form');
            const submitBtn = document.getElementById('btn-submit');
            const btnText = document.getElementById('btn-text');

            // Validación en tiempo real
            const tituloInput = document.getElementById('titulo');
            const descripcionInput = document.getElementById('descripcion');

            if (tituloInput) {
                tituloInput.addEventListener('input', function () {
                    // Eliminar clase de error si existe
                    this.classList.remove('border-red-500', 'error-shake');

                    // Validar longitud
                    if (this.value.length > 100) {
                        this.classList.add('border-red-500');
                    }
                });
            }

            if (descripcionInput) {
                descripcionInput.addEventListener('input', function () {
                    // Eliminar clase de error si existe
                    this.classList.remove('border-red-500', 'error-shake');

                    // Agregar contador de caracteres si no existe
                    let counter = this.parentElement.querySelector('.char-counter');
                    if (!counter) {
                        counter = document.createElement('div');
                        counter.className = 'char-counter';
                        this.parentElement.appendChild(counter);
                    }

                    const maxLength = 500;
                    const currentLength = this.value.length;
                    counter.textContent = `${currentLength}/${maxLength} caracteres`;

                    if (currentLength > maxLength) {
                        this.classList.add('border-red-500');
                        counter.style.color = '#ef4444';
                    } else {
                        counter.style.color = '#9ca3af';
                    }
                });
            }

            // Manejo del envío del formulario
            if (form) {
                form.addEventListener('submit', function (e) {
                    // Agregar estado de carga
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    btnText.textContent = 'Creando...';

                    // Validación adicional antes del envío
                    let hasErrors = false;
                    const tipoPost = document.getElementById('post-tipo').value;

                    // Solo validar título y descripción si es tipo imagen
                    if (tipoPost === 'imagen') {
                        if (tituloInput && tituloInput.value.trim() === '') {
                            tituloInput.classList.add('border-red-500', 'error-shake');
                            hasErrors = true;
                        }

                        if (descripcionInput && descripcionInput.value.trim() === '') {
                            descripcionInput.classList.add('border-red-500', 'error-shake');
                            hasErrors = true;
                        }
                    }

                    if (hasErrors) {
                        e.preventDefault();

                        // Restaurar botón
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = true; // Mantener deshabilitado hasta que se corrijan los errores
                        btnText.textContent = 'Crear publicación';

                        // Mostrar notificación de error
                        if (window.showNotification) {
                            showNotification('Por favor, completa todos los campos requeridos', 'error');
                        }
                    }
                });
            }
        });
    </script>
@endpush