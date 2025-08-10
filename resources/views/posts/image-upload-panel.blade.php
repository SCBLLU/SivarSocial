<!-- Panel de subida de imágenes simplificado -->
<div class="text-center">
    <!-- Dropzone para subir archivos -->
    <form id="dropzone" class="hidden">
        @csrf
    </form>
    <!-- Área de subida principal -->
    <div id="upload-area"
        class="border-2 border-dashed border-gray-300 rounded-xl p-8 transition-all hover:border-blue-400 hover:bg-blue-50/50 cursor-pointer">
        <div class="flex flex-col items-center gap-4">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-cloud-upload-alt text-blue-500 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Subir Foto</h3>
                <p class="text-gray-600 text-sm">
                    <span class="hidden md:inline">Arrastra una imagen aquí o </span>
                    <span class="text-blue-500 font-medium">haz clic para seleccionar</span>
                </p>
                <p class="text-gray-400 text-xs mt-1">JPG, PNG hasta 20MB • Se ajustará automáticamente a 1:1</p>
            </div>
        </div>
    </div>
    <!-- Opciones adicionales para móvil -->
    <div class="mobile-only-controls mt-4 gap-3 justify-center hidden">
        <button type="button" id="open-camera"
            class="flex-1 bg-blue-500 text-white px-4 py-3 rounded-xl font-medium flex items-center justify-center gap-2 hover:bg-blue-600 transition-colors">
            <i class="fas fa-camera"></i>
            Tomar Foto
        </button>
        <button type="button" id="open-gallery"
            class="flex-1 bg-gray-500 text-white px-4 py-3 rounded-xl font-medium flex items-center justify-center gap-2 hover:bg-gray-600 transition-colors">
            <i class="fas fa-images"></i>
            Galería
        </button>
    </div>
    <!-- Input oculto para archivos -->
    <input type="file" id="file-input" accept="image/*" class="hidden">
    <!-- Preview de imagen -->
    <div id="image-preview" class="hidden mt-6">
        <div class="relative inline-block">
            <img id="preview-img" class="w-64 h-64 object-cover rounded-xl shadow-lg mx-auto" alt="Preview">
            <button type="button" id="remove-image"
                class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <p class="text-sm text-gray-600 mt-2">Imagen seleccionada</p>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('file-input');
        const openCamera = document.getElementById('open-camera');
        const openGallery = document.getElementById('open-gallery');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        const removeImage = document.getElementById('remove-image');
        const cameraOverlay = document.getElementById('camera-overlay');
        const closeCamera = document.getElementById('close-camera');
        const switchCamera = document.getElementById('switch-camera');
        const capturePhoto = document.getElementById('capture-photo');
        const cameraPreview = document.getElementById('camera-preview');
        const photoCanvas = document.getElementById('photo-canvas');
        const mobileControls = document.querySelector('.mobile-only-controls');
        let currentStream = null;
        let currentFacingMode = 'environment'; // 'user' para frontal, 'environment' para trasera
        // Detectar si es móvil
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
            (window.innerWidth <= 768);
        // Mostrar controles móviles solo en móvil
        if (isMobile && mobileControls) {
            mobileControls.classList.remove('hidden');
            mobileControls.classList.add('flex');
        } else {
        }
        // Asegurar que el overlay de cámara esté oculto en desktop
        if (!isMobile && cameraOverlay) {
            cameraOverlay.style.display = 'none';
            cameraOverlay.style.visibility = 'hidden';
            cameraOverlay.style.opacity = '0';
            cameraOverlay.style.zIndex = '-1';
        }
        // Click en área de subida
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });
        // Drag & Drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-blue-400', 'bg-blue-50');
        });
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });
        // Selección de archivo
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });
        // Botón galería
        if (openGallery) {
            openGallery.addEventListener('click', () => {
                fileInput.click();
            });
        }
        // Botón cámara
        if (openCamera && isMobile) {
            openCamera.addEventListener('click', () => {
                openCameraModal();
            });
        } else if (openCamera && !isMobile) {
            // En desktop, el botón de cámara actúa como botón de galería
            openCamera.style.display = 'none';
        }
        // Cerrar cámara
        if (closeCamera) {
            closeCamera.addEventListener('click', () => {
                closeCameraModal();
            });
        }
        // Cambiar cámara
        if (switchCamera) {
            switchCamera.addEventListener('click', () => {
                switchCameraFacing();
            });
        }
        // Capturar foto
        if (capturePhoto) {
            capturePhoto.addEventListener('click', () => {
                capturePhotoFromCamera();
            });
        }
        // Remover imagen
        if (removeImage) {
            removeImage.addEventListener('click', () => {
                clearImage();
            });
        }
        // Función para abrir cámara
        async function openCameraModal() {
            // Solo permitir cámara en móvil
            if (!isMobile) {
                fileInput.click(); // En desktop, abrir selector de archivos
                return;
            }
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Tu navegador no soporta acceso a la cámara');
                return;
            }
            try {
                cameraOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                // En móvil, usar fullscreen
                if (cameraOverlay.requestFullscreen) {
                    cameraOverlay.requestFullscreen().catch(err => {
                    });
                }
                await startCamera();
            } catch (error) {
                let errorMessage = 'No se pudo acceder a la cámara. ';
                if (error.name === 'NotAllowedError') {
                    errorMessage += 'Permisos denegados. Verifica la configuración de tu navegador.';
                } else if (error.name === 'NotFoundError') {
                    errorMessage += 'No se encontró cámara en el dispositivo.';
                } else if (error.name === 'NotSupportedError') {
                    errorMessage += 'Tu navegador no soporta esta función.';
                } else {
                    errorMessage += 'Error desconocido.';
                }
                alert(errorMessage);
                closeCameraModal();
            }
        }
        // Función para cerrar cámara
        function closeCameraModal() {
            cameraOverlay.classList.add('hidden');
            document.body.style.overflow = '';
            // Salir de fullscreen si está activo
            if (document.fullscreenElement) {
                document.exitFullscreen().catch(err => {
                });
            }
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }
        }
        // Iniciar cámara
        async function startCamera() {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }
            // Configuraciones optimizadas para diferentes dispositivos
            const baseConstraints = {
                video: {
                    facingMode: currentFacingMode,
                    width: { ideal: 1080, max: 1920 },
                    height: { ideal: 1080, max: 1920 }
                }
            };
            try {
                // Intentar con configuración ideal primero
                currentStream = await navigator.mediaDevices.getUserMedia(baseConstraints);
            } catch (error) {
                // Fallback a configuración básica
                try {
                    const fallbackConstraints = {
                        video: {
                            facingMode: currentFacingMode
                        }
                    };
                    currentStream = await navigator.mediaDevices.getUserMedia(fallbackConstraints);
                } catch (fallbackError) {
                    throw fallbackError;
                }
            }
            cameraPreview.srcObject = currentStream;
            // Esperar a que el video cargue para ajustar dimensiones
            cameraPreview.addEventListener('loadedmetadata', () => {
            });
        }
        // Cambiar cámara frontal/trasera
        async function switchCameraFacing() {
            currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
            await startCamera();
        }
        // Capturar foto desde cámara
        function capturePhotoFromCamera() {
            const context = photoCanvas.getContext('2d');
            // Configurar canvas como cuadrado 1:1
            photoCanvas.width = 1080;
            photoCanvas.height = 1080;
            // Calcular dimensiones para hacer la imagen cuadrada
            const videoWidth = cameraPreview.videoWidth;
            const videoHeight = cameraPreview.videoHeight;
            if (videoWidth === 0 || videoHeight === 0) {
                alert('Error: No se pudo capturar la imagen. Inténtalo de nuevo.');
                return;
            }
            // Calcular recorte centrado para hacer cuadrado
            const size = Math.min(videoWidth, videoHeight);
            const x = (videoWidth - size) / 2;
            const y = (videoHeight - size) / 2;
            // Dibujar imagen cuadrada en canvas
            context.drawImage(cameraPreview, x, y, size, size, 0, 0, 1080, 1080);
            // Convertir a blob y procesar
            photoCanvas.toBlob((blob) => {
                if (blob) {
                    const file = new File([blob], 'camera-photo.jpg', { type: 'image/jpeg' });
                    handleFile(file);
                    closeCameraModal();
                    if (typeof showNotification === 'function') {
                        showNotification('Foto capturada correctamente', 'success');
                    }
                } else {
                    alert('Error al procesar la foto. Inténtalo de nuevo.');
                }
            }, 'image/jpeg', 0.9);
        }
        // Procesar archivo
        function handleFile(file) {
            if (!file.type.startsWith('image/')) {
                alert('Por favor selecciona una imagen válida');
                return;
            }
            if (file.size > 20 * 1024 * 1024) { // 20MB
                alert('La imagen es demasiado grande. Máximo 20MB');
                return;
            }
            // Mostrar preview
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                uploadArea.classList.add('hidden');
                imagePreview.classList.remove('hidden');
                // Si es móvil, ocultar botones adicionales
                if (isMobile && mobileControls) {
                    mobileControls.classList.add('hidden');
                    mobileControls.classList.remove('flex');
                }
            };
            reader.readAsDataURL(file);
            // Subir archivo usando dropzone
            uploadFileToDropzone(file);
        }
        // Limpiar imagen
        function clearImage() {
            uploadArea.classList.remove('hidden');
            imagePreview.classList.add('hidden');
            fileInput.value = '';
            // Mostrar botones móvil si es necesario
            if (isMobile && mobileControls) {
                mobileControls.classList.remove('hidden');
                mobileControls.classList.add('flex');
            }
            // Limpiar dropzone
            if (window.dropzoneInstance) {
                window.dropzoneInstance.removeAllFiles();
            }
            // Limpiar campo hidden
            const imagenInput = document.querySelector('input[name="imagen"]');
            if (imagenInput) {
                imagenInput.value = '';
            }
            // Actualizar botón submit
            if (typeof updateSubmitButton === 'function') {
                updateSubmitButton();
            }
        }
        // Subir archivo a dropzone
        function uploadFileToDropzone(file) {
            if (window.dropzoneInstance) {
                window.dropzoneInstance.addFile(file);
            } else {
                // Si dropzone no está inicializado, subir manualmente
                const formData = new FormData();
                formData.append('imagen', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                fetch('/imagenes', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.imagen) {
                            const imagenInput = document.querySelector('input[name="imagen"]');
                            if (imagenInput) {
                                imagenInput.value = data.imagen;
                            }
                            if (typeof updateSubmitButton === 'function') {
                                updateSubmitButton();
                            }
                            if (typeof showNotification === 'function') {
                                showNotification('Imagen subida correctamente', 'success');
                            }
                        }
                    })
                    .catch(error => {
                        if (typeof showNotification === 'function') {
                            showNotification('Error al subir imagen', 'error');
                        }
                    });
            }
        }
    });
</script>