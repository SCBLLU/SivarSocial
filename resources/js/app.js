// importo dropzone para manejo de imagenes
import Dropzone from "dropzone";

// desactivo autodiscover para evitar conflictos
Dropzone.autoDiscover = false;

// dropzone para crear posts (solo si existe el elemento)
if(document.getElementById('dropzone')) {
    // inicializo dropzone en el formulario de posts
    let dropzone = new Dropzone('#dropzone', {
        url: '/imagenes', // ruta para subir imagenes de posts
        dictDefaultMessage: 'Sube tu post aquí', // mensaje por defecto
        acceptedFiles: '.jpg,.jpeg,.png,.gif', // tipos de archivos permitidos
        addRemoveLinks: true, // permite eliminar archivos
        dictRemoveFile: 'Eliminar archivo', // texto del boton eliminar
        maxFiles: 1, // solo una imagen por post
        maxFilesize: 2, // tamaño maximo en mb
        uploadMultiple: false, // no permite multiples archivos
        paramName: 'imagen', // nombre del campo para el backend
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // token csrf
        },
        init: function () {
            // si ya hay una imagen (por error de validacion), la muestro
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
                // habilito el boton de crear si hay imagen
                if(document.getElementById('btn-submit')) {
                    document.getElementById('btn-submit').disabled = false;
                }
            }
        }
    });

    // cuando la imagen se sube correctamente
    dropzone.on("success", function (file, response) {
        document.querySelector('[name="imagen"]').value = response.imagen; // guardo el nombre en el input oculto
        if(document.getElementById('btn-submit')) {
            document.getElementById('btn-submit').disabled = false;
        }
    });

    // cuando se elimina la imagen
    dropzone.on("removedfile", function (file) {
        document.querySelector('[name="imagen"]').value = ""; // limpio el input
        if(document.getElementById('btn-submit')) {
            document.getElementById('btn-submit').disabled = true;
        }
    });
}

// dropzone para registro de usuario (solo si existe el elemento)
if(document.getElementById('dropzone-register')) {
    // inicializo dropzone en el formulario de registro
    let dropzoneRegister = new Dropzone('#dropzone-register', {
        url: '/imagenes', // ruta para subir imagen de perfil
        dictDefaultMessage: 'Arrastra aquí tu imagen de perfil o haz clic', // mensaje por defecto
        acceptedFiles: '.jpg,.jpeg,.png,.gif', // tipos de archivos permitidos
        addRemoveLinks: true, // permite eliminar archivos
        dictRemoveFile: 'Eliminar', // texto del boton eliminar
        maxFiles: 1, // solo una imagen de perfil
        maxFilesize: 2, // tamaño maximo en mb
        uploadMultiple: false, // no permite multiples archivos
        paramName: 'imagen', // nombre del campo para el backend
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // token csrf
        },
        init: function () {
            // si ya hay una imagen (por error de validacion), la muestro
            this.on('maxfilesexceeded', function(file) {
                this.removeAllFiles();
                this.addFile(file);
            });
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

    // cuando la imagen de perfil se sube correctamente
    dropzoneRegister.on("success", function (file, response) {
        // guardo el nombre en el input oculto
        document.querySelector('[name="imagen"]').value = response.imagen;
    });

    // cuando se elimina la imagen de perfil
    dropzoneRegister.on("removedfile", function (file) {
        document.querySelector('[name="imagen"]').value = "";
    });

    // si hay error al subir la imagen
    dropzoneRegister.on("error", function(file, message) {
        console.error('Error al subir imagen:', message);
    });
}