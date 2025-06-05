import Dropzone from "dropzone";

Dropzone.autoDiscover = false;

// Dropzone para posts (si existe)
if(document.getElementById('dropzone')) {
    let dropzone = new Dropzone('#dropzone', {
        url: '/imagenes',
        dictDefaultMessage: 'Sube tu post aquí',
        acceptedFiles: '.jpg,.jpeg,.png,.gif',
        addRemoveLinks: true,
        dictRemoveFile: 'Eliminar archivo',
        maxFiles: 1,
        maxFilesize: 2,
        uploadMultiple: false,
        paramName: 'imagen', // Importante: nombre del parámetro
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function () {
            // Mostrar imagen previa si existe (tras error de validación)
            const imagenInput = document.querySelector('[name="imagen"]');
            if(imagenInput && imagenInput.value.trim()) {
                const mockFile = { 
                    name: imagenInput.value, 
                    size: 1234 
                };
                this.emit('addedfile', mockFile);
                this.emit('thumbnail', mockFile, `/uploads/${mockFile.name}`);
                this.emit('complete', mockFile);
                mockFile.previewElement.classList.add('dz-success', 'dz-complete');
                if(document.getElementById('btn-submit')) {
                    document.getElementById('btn-submit').disabled = false;
                }
            }
        }
    });

    dropzone.on("success", function (file, response) {
        document.querySelector('[name="imagen"]').value = response.imagen;
        if(document.getElementById('btn-submit')) {
            document.getElementById('btn-submit').disabled = false;
        }
    });

    dropzone.on("removedfile", function (file) {
        document.querySelector('[name="imagen"]').value = "";
        if(document.getElementById('btn-submit')) {
            document.getElementById('btn-submit').disabled = true;
        }
    });
}

// Dropzone para registro (si existe)
if(document.getElementById('dropzone-register')) {
    let dropzoneRegister = new Dropzone('#dropzone-register', {
        url: '/imagenes',
        dictDefaultMessage: 'Arrastra aquí tu imagen de perfil o haz clic',
        acceptedFiles: '.jpg,.jpeg,.png,.gif',
        addRemoveLinks: true,
        dictRemoveFile: 'Eliminar',
        maxFiles: 1,
        maxFilesize: 2,
        uploadMultiple: false,
        paramName: 'imagen', // Importante: nombre del parámetro
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        init: function () {
            // Limitar a un solo archivo
            this.on('maxfilesexceeded', function(file) {
                this.removeAllFiles();
                this.addFile(file);
            });

            // Mostrar imagen previa si existe
            const imagenInput = document.querySelector('[name="imagen"]');
            if(imagenInput && imagenInput.value.trim()) {
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

    dropzoneRegister.on("success", function (file, response) {
        console.log('Imagen subida exitosamente:', response);
        document.querySelector('[name="imagen"]').value = response.imagen;
    });

    dropzoneRegister.on("removedfile", function (file) {
        console.log('Imagen eliminada');
        document.querySelector('[name="imagen"]').value = "";
    });

    dropzoneRegister.on("error", function(file, message) {
        console.error('Error al subir imagen:', message);
    });
}