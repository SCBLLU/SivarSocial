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

        // Variables para almacenar estados originales
        let originalBodyStyle = '';
        let originalHtmlStyle = '';
        let originalViewport = '';

        // Detectar si es móvil
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
            (window.innerWidth <= 768);

        // Función para preparar modo fullscreen inmersivo
        function prepareFullscreenMode() {
            // Guardar estados originales
            originalBodyStyle = document.body.style.cssText;
            originalHtmlStyle = document.documentElement.style.cssText;

            // Obtener viewport original
            const viewportMeta = document.querySelector('meta[name="viewport"]');
            if (viewportMeta) {
                originalViewport = viewportMeta.content;
            }
        }

        // Función para forzar fullscreen en móvil
        function forceMobileFullscreen() {
            // Detectar iOS Safari
            const isIOSSafari = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

            // Configurar viewport para fullscreen inmersivo
            const viewportMeta = document.querySelector('meta[name="viewport"]');
            if (viewportMeta) {
                if (isIOSSafari) {
                    // Para iOS Safari, usar configuración especial
                    viewportMeta.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui, viewport-fit=cover';
                } else {
                    viewportMeta.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover';
                }
            }

            // Aplicar estilos para ocultar barras del navegador
            const htmlStyles = `
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                overflow: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                ${isIOSSafari ? '-webkit-transform: translate3d(0,0,0) !important;' : ''}
            `;

            const bodyStyles = `
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                overscroll-behavior: none !important;
                ${isIOSSafari ? '-webkit-transform: translate3d(0,0,0) !important;' : ''}
                ${isIOSSafari ? '-webkit-backface-visibility: hidden !important;' : ''}
            `;

            document.documentElement.style.cssText = htmlStyles;
            document.body.style.cssText = bodyStyles;

            // Ocultar menú móvil si existe
            const mobileMenu = document.querySelector('.header2, .header');
            if (mobileMenu) {
                mobileMenu.style.display = 'none';
            }

            // Para iOS Safari, múltiples intentos de scroll hacia arriba
            if (isIOSSafari) {
                window.scrollTo(0, 0);
                setTimeout(() => window.scrollTo(0, 0), 50);
                setTimeout(() => window.scrollTo(0, 0), 100);
                setTimeout(() => window.scrollTo(0, 0), 200);
            } else {
                window.scrollTo(0, 0);
            }

            // Intentar bloquear orientación en landscape si es posible
            if (screen && screen.orientation && screen.orientation.lock) {
                screen.orientation.lock('portrait').catch(() => {
                    // Ignorar error si no se puede bloquear
                });
            }
        }

        // Función para restaurar modo normal
        function restoreNormalMode() {
            // Restaurar viewport original
            const viewportMeta = document.querySelector('meta[name="viewport"]');
            if (viewportMeta && originalViewport) {
                viewportMeta.content = originalViewport;
            }

            // Restaurar estilos originales
            document.documentElement.style.cssText = originalHtmlStyle;
            document.body.style.cssText = originalBodyStyle;

            // Remover clase de cámara activa
            removeCameraActiveClass();

            // Mostrar menú móvil de nuevo
            const mobileMenu = document.querySelector('.header2, .header');
            if (mobileMenu) {
                mobileMenu.style.display = '';
            }

            // Desbloquear orientación
            if (screen && screen.orientation && screen.orientation.unlock) {
                screen.orientation.unlock().catch(() => {
                    // Ignorar error si no se puede desbloquear
                });
            }
        }

        // Funciones para manejar el estado de cámara activa
        function addCameraActiveClass() {
            document.body.classList.add('camera-active');
            document.documentElement.classList.add('camera-active');
        }

        function removeCameraActiveClass() {
            document.body.classList.remove('camera-active');
            document.documentElement.classList.remove('camera-active');
        }

        // Funciones para mostrar/ocultar loader durante solicitud de permisos
        function showCameraLoader() {
            // Crear loader si no existe
            let loader = document.getElementById('camera-loader');
            if (!loader) {
                loader = document.createElement('div');
                loader.id = 'camera-loader';
                loader.innerHTML = `
                    <div style="
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.9);
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        z-index: 10000;
                        color: white;
                        font-family: system-ui, -apple-system, sans-serif;
                    ">
                        <div style="
                            width: 60px;
                            height: 60px;
                            border: 3px solid #333;
                            border-top: 3px solid #fff;
                            border-radius: 50%;
                            animation: spin 1s linear infinite;
                            margin-bottom: 20px;
                        "></div>
                        <p style="font-size: 16px; text-align: center; margin: 0;">
                            Solicitando permisos de cámara...
                        </p>
                        <p style="font-size: 14px; text-align: center; margin: 10px 0 0 0; opacity: 0.7;">
                            Por favor, permite el acceso cuando se te solicite
                        </p>
                    </div>
                    <style>
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                    </style>
                `;
                document.body.appendChild(loader);
            }
        }

        function hideCameraLoader() {
            const loader = document.getElementById('camera-loader');
            if (loader) {
                loader.remove();
            }
        }
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

        // Event listeners para manejar cambios de estado

        // Listener para cambios en fullscreen
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement && cameraOverlay && !cameraOverlay.classList.contains('hidden')) {
                // Si se sale de fullscreen pero la cámara sigue activa, intentar mantener el fullscreen
                setTimeout(() => {
                    if (isMobile && !cameraOverlay.classList.contains('hidden')) {
                        forceMobileFullscreen();
                    }
                }, 100);
            }
        });

        // Listener para cambios de visibilidad de la página
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && currentStream) {
                // Si la página se oculta mientras la cámara está activa, mantener el stream
                console.log('Page hidden, keeping camera stream');
            } else if (!document.hidden && !cameraOverlay.classList.contains('hidden')) {
                // Si la página vuelve a ser visible con la cámara activa, restaurar fullscreen
                setTimeout(() => {
                    if (isMobile) {
                        forceMobileFullscreen();
                        addCameraActiveClass();
                    }
                }, 100);
            }
        });

        // Listener para cambios de orientación
        if (screen && screen.orientation) {
            screen.orientation.addEventListener('change', () => {
                if (!cameraOverlay.classList.contains('hidden') && isMobile) {
                    // Reajustar fullscreen después de cambio de orientación
                    setTimeout(() => {
                        forceMobileFullscreen();
                        addCameraActiveClass();
                    }, 200);
                }
            });
        }

        // Prevenir zoom accidental mientras la cámara está activa
        document.addEventListener('touchstart', (e) => {
            if (!cameraOverlay.classList.contains('hidden') && e.touches.length > 1) {
                e.preventDefault();
            }
        }, { passive: false });

        document.addEventListener('gesturestart', (e) => {
            if (!cameraOverlay.classList.contains('hidden')) {
                e.preventDefault();
            }
        });
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
                // Preparar UI para fullscreen inmersivo
                prepareFullscreenMode();

                cameraOverlay.classList.remove('hidden');

                // Forzar ocultamiento de barras de navegación móvil
                forceMobileFullscreen();

                // Intentar fullscreen nativo
                if (cameraOverlay.requestFullscreen) {
                    cameraOverlay.requestFullscreen().catch(err => {
                        console.log('Fullscreen fallback:', err);
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
            // Ocultar loader si está visible
            hideCameraLoader();

            cameraOverlay.classList.add('hidden');

            // Restaurar configuración normal
            restoreNormalMode();

            // Salir de fullscreen si está activo
            if (document.fullscreenElement) {
                document.exitFullscreen().catch(err => {
                    console.log('Exit fullscreen error:', err);
                });
            }

            // Detener stream de cámara
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }

            // Limpiar srcObject del video
            if (cameraPreview) {
                cameraPreview.srcObject = null;
            }

            // Pequeña pausa para asegurar que todo se restaure correctamente
            setTimeout(() => {
                // Forzar un reflow para asegurar que los estilos se apliquen
                document.body.offsetHeight;

                // Scroll hacia arriba para asegurar posición correcta
                window.scrollTo(0, 0);
            }, 100);
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
                // Mostrar indicador de carga mientras se solicitan permisos
                showCameraLoader();

                // Intentar con configuración ideal primero
                currentStream = await navigator.mediaDevices.getUserMedia(baseConstraints);

                // Una vez que tenemos acceso, aplicar fullscreen inmediatamente
                if (isMobile) {
                    // Pequeña pausa para asegurar que el stream esté listo
                    setTimeout(() => {
                        forceMobileFullscreen();
                        addCameraActiveClass();
                    }, 100);
                }

            } catch (error) {
                // Fallback a configuración básica
                try {
                    const fallbackConstraints = {
                        video: {
                            facingMode: currentFacingMode
                        }
                    };
                    currentStream = await navigator.mediaDevices.getUserMedia(fallbackConstraints);

                    if (isMobile) {
                        setTimeout(() => {
                            forceMobileFullscreen();
                            addCameraActiveClass();
                        }, 100);
                    }

                } catch (fallbackError) {
                    throw fallbackError;
                }
            } finally {
                hideCameraLoader();
            }

            if (currentStream) {
                cameraPreview.srcObject = currentStream;

                // Esperar a que el video cargue para ajustar dimensiones
                cameraPreview.addEventListener('loadedmetadata', () => {
                    // Asegurar que el fullscreen se mantenga después de cargar el video
                    if (isMobile) {
                        setTimeout(() => {
                            forceMobileFullscreen();
                            addCameraActiveClass();
                        }, 200);
                    }
                });
            }
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