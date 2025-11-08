<!-- Panel de subida de archivos -->
<div class="text-center py-8">
    <!-- Ícono y mensaje informativo -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-3">Comparte archivos</h3>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">Sube documentos PDF, Word, Excel u otros archivos para compartir
        </p>
    </div>

    <!-- Zona de arrastrar y soltar archivo -->
    <div id="file-dropzone"
        class="border-2 border-dashed border-gray-300 rounded-2xl p-12 hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer bg-gray-50">
        <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-4"></i>
        <p class="text-gray-700 font-semibold mb-2">Arrastra un archivo aquí</p>
        <p class="text-gray-500 mb-1">o haz clic para seleccionar</p>
        <div class="mt-6 space-y-2">
            <div class="flex items-center justify-center gap-2 text-xs text-gray-500">
                <i class="fas fa-file-pdf text-red-500"></i>
                <i class="fas fa-file-word text-blue-500"></i>
                <i class="fas fa-file-excel text-green-500"></i>
                <i class="fas fa-file-alt text-gray-400"></i>
            </div>
            <p class="text-xs text-gray-400">Formatos: PDF, DOC, DOCX, XLS, XLSX, TXT</p>
            <p class="text-xs text-gray-400">Tamaño máximo: 10MB</p>
        </div>
    </div>

    <!-- Input oculto para archivo -->
    <input type="file" id="archivo-input" name="archivo_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt" class="hidden">

    <!-- Vista previa del archivo seleccionado -->
    <div id="archivo-preview" class="hidden mt-8 p-6 bg-white border-2 border-blue-200 rounded-2xl shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-2xl text-white"></i>
                </div>
                <div class="text-left">
                    <p id="archivo-nombre" class="font-semibold text-gray-900 mb-1"></p>
                    <p id="archivo-tamano" class="text-sm text-gray-500"></p>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="flex items-center gap-1 text-xs text-green-600">
                            <i class="fas fa-check-circle"></i>
                            <span>Listo para publicar</span>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" id="remove-archivo"
                class="text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors">
                <i class="fas fa-times-circle text-2xl"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // Este script se ejecutará cuando el componente se cargue
    document.addEventListener('DOMContentLoaded', function () {
        // Event listeners para subir archivos
        const archivoDropzone = document.getElementById('file-dropzone');
        const archivoInput = document.getElementById('archivo-input');
        const archivoPreview = document.getElementById('archivo-preview');
        const removeArchivo = document.getElementById('remove-archivo');

        if (archivoDropzone && archivoInput) {
            // Click en la zona de drop
            archivoDropzone.addEventListener('click', () => {
                archivoInput.click();
            });

            // Cuando se selecciona un archivo
            archivoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    handleArchivoUpload(file);
                }
            });

            // Drag and drop
            archivoDropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                archivoDropzone.classList.add('border-blue-500', 'bg-blue-50');
            });

            archivoDropzone.addEventListener('dragleave', () => {
                archivoDropzone.classList.remove('border-blue-500', 'bg-blue-50');
            });

            archivoDropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                archivoDropzone.classList.remove('border-blue-500', 'bg-blue-50');
                const file = e.dataTransfer.files[0];
                if (file) {
                    handleArchivoUpload(file);
                }
            });
        }

        // Remover archivo
        if (removeArchivo) {
            removeArchivo.addEventListener('click', () => {
                const archivoInput = document.getElementById('archivo-input');
                const archivoPreview = document.getElementById('archivo-preview');
                const archivoDropzone = document.getElementById('file-dropzone');
                const archivoHidden = document.querySelector('[name="archivo"]');

                if (archivoInput) archivoInput.value = '';
                if (archivoPreview) archivoPreview.classList.add('hidden');
                if (archivoDropzone) archivoDropzone.classList.remove('hidden');
                if (archivoHidden) {
                    archivoHidden.value = '';
                    if (typeof updateSubmitButton === 'function') {
                        updateSubmitButton();
                    }
                }
            });
        }
    });
</script>