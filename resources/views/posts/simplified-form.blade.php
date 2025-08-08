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
        <input 
            type="text" 
            id="titulo" 
            name="titulo"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all {{ $errors->has('titulo') ? 'border-red-500' : '' }}"
            placeholder="¿Qué quieres compartir?"
            value="{{ old('titulo') }}"
        >
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
        <textarea 
            id="descripcion" 
            name="descripcion" 
            rows="3"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none {{ $errors->has('descripcion') ? 'border-red-500' : '' }}"
            placeholder="Cuéntanos más sobre tu publicación..."
        >{{ old('descripcion') }}</textarea>
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
        <button 
            type="submit" 
            id="btn-submit" 
            disabled
            class="flex-1 bg-blue-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-600 disabled:hover:bg-gray-300 flex items-center justify-center gap-2"
        >
            <i class="fas fa-paper-plane"></i>
            <span id="submit-text">Publicar</span>
        </button>
        
        <a 
            href="{{ route('home') }}" 
            class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center"
        >
            Cancelar
        </a>
    </div>

    <!-- Indicador de estado -->
    <div id="submit-status" class="mt-3 text-center text-sm text-gray-500 hidden">
        <i class="fas fa-info-circle mr-1"></i>
        <span id="status-message">Selecciona una imagen o música para publicar</span>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('main-form');
    const submitBtn = document.getElementById('btn-submit');
    const submitText = document.getElementById('submit-text');
    const statusIndicator = document.getElementById('submit-status');
    const statusMessage = document.getElementById('status-message');
    
    // Función para actualizar el estado del botón de envío
    window.updateSubmitButton = function() {
        const currentType = document.getElementById('post-tipo').value;
        let canSubmit = false;
        let message = '';
        
        if (currentType === 'imagen') {
            const imagenInput = document.querySelector('input[name="imagen"]');
            canSubmit = imagenInput && imagenInput.value.trim() !== '';
            message = canSubmit ? 'Listo para publicar' : 'Selecciona una imagen para continuar';
        } else if (currentType === 'musica') {
            const trackIdInput = document.querySelector('input[name="itunes_track_id"]');
            canSubmit = trackIdInput && trackIdInput.value.trim() !== '';
            message = canSubmit ? 'Listo para publicar' : 'Selecciona una canción para continuar';
        }
        
        // Actualizar botón
        submitBtn.disabled = !canSubmit;
        
        // Actualizar indicador de estado
        if (message) {
            statusMessage.textContent = message;
            statusIndicator.classList.remove('hidden');
            
            if (canSubmit) {
                statusIndicator.className = 'mt-3 text-center text-sm text-green-600';
                statusMessage.innerHTML = '<i class="fas fa-check-circle mr-1"></i>' + message;
            } else {
                statusIndicator.className = 'mt-3 text-center text-sm text-gray-500';
                statusMessage.innerHTML = '<i class="fas fa-info-circle mr-1"></i>' + message;
            }
        }
    };
    
    // Función global para cambiar entre tabs (expuesta para uso desde Alpine.js)
    window.switchTab = function(type) {
        const currentPostType = type;
        
        // Actualizar campo hidden del tipo de post
        document.getElementById('post-tipo').value = type;
        
        // Actualizar labels según el tipo
        const tituloLabel = document.getElementById('titulo-label');
        const tituloOptional = document.getElementById('titulo-optional');
        const descripcionLabel = document.getElementById('descripcion-label');
        const descripcionOptional = document.getElementById('descripcion-optional');
        
        if (type === 'imagen') {
            tituloLabel.textContent = 'Título';
            descripcionLabel.textContent = 'Descripción';
            tituloOptional.classList.add('hidden');
            descripcionOptional.classList.add('hidden');
            
            // Mostrar campos de imagen, ocultar música
            document.getElementById('imagen-fields').classList.remove('hidden');
            document.getElementById('musica-fields').classList.add('hidden');
        } else if (type === 'musica') {
            tituloLabel.textContent = 'Título';
            descripcionLabel.textContent = 'Descripción';
            tituloOptional.classList.remove('hidden');
            descripcionOptional.classList.remove('hidden');
            
            // Mostrar campos de música, ocultar imagen
            document.getElementById('imagen-fields').classList.add('hidden');
            document.getElementById('musica-fields').classList.remove('hidden');
        }
        
        // Mostrar/ocultar paneles de contenido
        document.querySelectorAll('.content-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        document.getElementById(`content-${type}`).classList.remove('hidden');
        
        // Actualizar estado del botón submit
        updateSubmitButton();
    };
    
    // Manejo del envío del formulario
    form.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('btn-submit');
        
        if (submitBtn.disabled) {
            e.preventDefault();
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
    
    // Inicializar estado
    updateSubmitButton();
});
</script>
