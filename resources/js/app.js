import Dropzone from "dropzone";

Dropzone.autoDiscover = false;

let dropzone = new Dropzone('#dropzone', {
    dictDefaultMessage: 'Sube tu post aquí',
    acceptedFiles: '.jpg, .jpeg, .png, .gif',
    addRemoveLinks: true,
    dictRemoveFile: 'Eliminar archivo',
    maxFiles: 1,
    mazFilesize: 2, 
    uploadMultiple: false,
});