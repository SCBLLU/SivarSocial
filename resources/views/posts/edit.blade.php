@extends('layouts.app')

@section('titulo')
    <div class="flex items-center justify-center relative w-full">
        <a href="{{ route('posts.show', ['user' => $post->user, 'post' => $post]) }}"
            class="absolute left-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h1 class="text-white text-2xl font-bold mx-auto">Editar Publicación</h1>
    </div>
@endsection

@section('contenido')
    <div class="container mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        
        <!-- Mostrar mensajes de éxito o error -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Tarjeta principal del formulario -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header de la tarjeta -->
            <div class="bg-gray-800 px-6 py-4">
                <h2 class="text-white text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Editar Publicación
                </h2>
                <p class="text-gray-300 text-sm mt-1">
                    Modifica el título y descripción de tu publicación
                </p>
            </div>

            <!-- Contenido de la tarjeta -->
            <div class="p-6">
                <!-- Mostrar preview del post -->
                <div class="mb-6 p-4 bg-gray-50 rounded-xl">
                    <h3 class="text-gray-700 font-semibold mb-3 flex items-center gap-2">
                        <i class="fas fa-eye text-gray-500"></i>
                        Vista previa actual
                    </h3>
                    
                    @if($post->tipo === 'imagen')
                        <div class="flex gap-4">
                            <img src="{{ asset('uploads/' . $post->imagen) }}" 
                                 alt="Imagen del post" 
                                 class="w-20 h-20 object-cover rounded-lg shadow-sm">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $post->titulo ?: 'Sin título' }}</p>
                                <p class="text-gray-600 text-sm mt-1">{{ $post->descripcion ?: 'Sin descripción' }}</p>
                                <span class="inline-block mt-2 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                    <i class="fas fa-image mr-1"></i>
                                    Post de imagen
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="flex gap-4">
                            @if($post->itunes_artwork_url)
                                <img src="{{ $post->itunes_artwork_url }}" 
                                     alt="Portada de la canción" 
                                     class="w-20 h-20 object-cover rounded-lg shadow-sm">
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $post->itunes_track_name }}</p>
                                <p class="text-gray-600 text-sm mt-1">{{ $post->itunes_artist_name }}</p>
                                <p class="text-gray-600 text-sm mt-1">
                                    {{ $post->titulo ?: 'Sin título' }} — {{ $post->descripcion ?: 'Sin descripción' }}
                                </p>
                                <span class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    <i class="fas fa-music mr-1"></i>
                                    música
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Formulario de edición -->
                <form action="{{ route('posts.update', $post) }}" method="POST" id="edit-form">
                    @csrf
                    @method('PUT')
                    
                    <!-- Campo de título -->
                    <div class="mb-6">
                        <label for="titulo" class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-heading text-gray-500 mr-2"></i>
                            Título
                            @if($post->tipo === 'musica')
                                <span class="text-sm text-gray-500 font-normal">(Opcional)</span>
                            @endif
                        </label>
                        <input type="text" id="titulo" name="titulo"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all {{ $errors->has('titulo') ? 'border-red-500' : '' }}"
                            placeholder="@if($post->tipo === 'imagen')Escribe un título para tu publicación...@else Título personalizado (opcional)@endif"
                            value="{{ old('titulo', $post->titulo ?? '') }}">
                        @error('titulo')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        @if($post->tipo === 'imagen')
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                El título es obligatorio para posts de imagen
                            </p>
                        @endif
                    </div>
                    
                    <!-- Campo de descripción -->
                    <div class="mb-6">
                        <label for="descripcion" class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-align-left text-gray-500 mr-2"></i>
                            Descripción
                            <span class="text-sm text-gray-500 font-normal">(Opcional)</span>
                        </label>
                        <textarea id="descripcion" name="descripcion" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none {{ $errors->has('descripcion') ? 'border-red-500' : '' }}"
                            placeholder="Describe tu publicación...">{{ old('descripcion', $post->descripcion ?? '') }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-3">
                        <a href="{{ route('posts.show', ['user' => $post->user, 'post' => $post]) }}"
                            class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 hover:bg-gray-200 flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            <span>Cancelar</span>
                        </a>
                        <button type="submit" id="btn-submit"
                            class="flex-1 bg-blue-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 hover:bg-blue-700 flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            <span id="submit-text">Guardar Cambios</span>
                        </button>
                    </div>

                    @if($post->tipo === 'imagen' && !$post->titulo)
                        <div class="mt-3 text-center">
                            <p class="text-amber-600 text-sm">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Para posts de imagen, debes agregar un título antes de guardar
                            </p>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 text-center">
            <p class="text-gray-500 text-sm">
                <i class="fas fa-info-circle mr-1"></i>
                @if($post->tipo === 'imagen')
                    No puedes cambiar la imagen de la publicación
                @else
                    No puedes cambiar la canción de la publicación
                @endif
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('edit-form');
            const submitBtn = document.getElementById('btn-submit');
            const submitText = document.getElementById('submit-text');
            const tituloInput = document.getElementById('titulo');
            const descripcionInput = document.getElementById('descripcion');
            const postType = '{{ $post->tipo }}';

            // Función para actualizar el estado del botón
            function updateSubmitButton() {
                let canSubmit = false;

                if (postType === 'imagen') {
                    // Para posts de imagen: título es obligatorio
                    const hasTitle = tituloInput && tituloInput.value.trim() !== '';
                    canSubmit = hasTitle;
                } else if (postType === 'musica') {
                    // Para posts de música: siempre se puede actualizar (campos opcionales)
                    canSubmit = true;
                }

                // Actualizar estado del botón
                updateButtonState(canSubmit);
            }

            // Función para actualizar el estado visual del botón
            function updateButtonState(canSubmit) {
                if (!submitBtn) return;

                submitBtn.disabled = !canSubmit;
                
                // Actualizar clases del botón
                submitBtn.classList.remove('bg-gray-300', 'cursor-not-allowed', 'bg-blue-600', 'hover:bg-blue-700');
                
                if (canSubmit) {
                    submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                } else {
                    submitBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                }
            }

            // Función para validar campo individual
            function validateField(field) {
                if (!field) return;
                
                const isEmpty = !field.value.trim();
                const isDescripcionField = field.name === 'descripcion';
                const isRequired = postType === 'imagen' && !isDescripcionField;

                // Actualizar border del campo
                field.classList.remove('border-red-500', 'border-green-500', 'border-gray-300');
                
                if (isRequired && !isEmpty) {
                    field.classList.add('border-green-500');
                } else if (!isRequired && !isEmpty) {
                    field.classList.add('border-green-500');
                } else if (isRequired && isEmpty) {
                    field.classList.add('border-red-500');
                } else {
                    field.classList.add('border-gray-300');
                }
            }

            // Event listeners para validación en tiempo real
            if (tituloInput) {
                tituloInput.addEventListener('input', function() {
                    validateField(this);
                    updateSubmitButton();
                });
                
                tituloInput.addEventListener('blur', function() {
                    validateField(this);
                    updateSubmitButton();
                });
            }

            if (descripcionInput) {
                descripcionInput.addEventListener('input', function() {
                    validateField(this);
                    updateSubmitButton();
                });
                
                descripcionInput.addEventListener('blur', function() {
                    validateField(this);
                    updateSubmitButton();
                });
            }

            // Manejo del envío del formulario
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Validación final antes del envío
                    if (postType === 'imagen') {
                        const hasTitle = tituloInput && tituloInput.value.trim() !== '';
                        if (!hasTitle) {
                            e.preventDefault();
                            alert('El título es obligatorio para posts de imagen');
                            if (tituloInput) tituloInput.focus();
                            return false;
                        }
                    }
                    
                    // Mostrar estado de carga
                    if (submitBtn && submitText) {
                        submitBtn.disabled = true;
                        submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...';
                    }
                    
                    return true;
                });
            }

            // Inicialización
            // Validar campos iniciales
            if (tituloInput) validateField(tituloInput);
            if (descripcionInput) validateField(descripcionInput);
            
            // Actualizar estado inicial del botón
            updateSubmitButton();
        });
    </script>

@endsection