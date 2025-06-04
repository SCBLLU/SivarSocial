import Dropzone from "dropzone";

Dropzone.autoDiscover = false;

let dropzone = new Dropzone('#dropzone', {
    url: '/imagenes', // ruta que maneja ImagenController@store
    dictDefaultMessage: 'Sube tu post aquí',
    acceptedFiles: '.jpg, .jpeg, .png, .gif',
    addRemoveLinks: true,
    dictRemoveFile: 'Eliminar archivo',
    maxFiles: 1,
    maxFilesize: 2,
    uploadMultiple: false,
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    init: function () {
        // Mostrar imagen previa si existe (tras error de validación)
        const imagenInput = document.querySelector('[name="imagen"]');
        if(imagenInput && imagenInput.value.trim()) {
            const imagenPublicada = {};
            imagenPublicada.size = 1234; // tamaño ficticio
            imagenPublicada.name = imagenInput.value;
            this.options.addedfile.call(this, imagenPublicada);
            this.options.thumbnail.call(this, imagenPublicada, `/uploads/${imagenPublicada.name}`);
            imagenPublicada.previewElement.classList.add('dz-success', 'dz-complete');
            document.getElementById('btn-submit').disabled = false;
        }
        // Eventos normales de Dropzone
        this.on("success", function (file, response) {
            document.querySelector('[name="imagen"]').value = response.imagen;
            document.getElementById('btn-submit').disabled = false;
        });
        this.on("removedfile", function (file) {
            document.querySelector('[name="imagen"]').value = "";
            document.getElementById('btn-submit').disabled = true;
        });
    }
});