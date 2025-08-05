<div x-data="registerWizard()" x-cloak>
    <form id="registerForm" action="{{ url('/register') }}" method="POST" enctype="multipart/form-data"
        @submit="handleSubmit">
        @csrf

        {{-- Mensaje de éxito solo si viene de la sesión (registro exitoso) --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Wizard solo si no hay mensaje de éxito --}}
        @if (!session('success'))
            <div>
                <!-- Barra de progreso -->
                <div class="border-b-2 border-white py-4">
                    <div class="uppercase tracking-wide text-xs font-bold text-white mb-1 leading-tight"
                        x-text="`PASO: ${step} DE 3`"></div>
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <template x-if="step === 1">
                                <div class="text-lg font-bold text-white leading-tight">Tu Perfil</div>
                            </template>
                            <template x-if="step === 2">
                                <div class="text-lg font-bold text-white leading-tight">Tu Contraseña</div>
                            </template>
                            <template x-if="step === 3">
                                <div class="text-lg font-bold text-white leading-tight">Cuéntanos sobre ti</div>
                            </template>
                        </div>
                        <div class="flex items-center md:w-64">
                            <div class="w-full bg-white rounded-full mr-2 h-2">
                                <div class="rounded-full h-2 transition-all duration-300"
                                    :class="{
                                        'bg-green-400': step === 1,
                                        'bg-green-500': step === 2,
                                        'bg-green-600': step === 3
                                    }"
                                    :style="'width: ' + parseInt(step / 3 * 100) + '%'">
                                </div>
                            </div>
                            <div class="text-xs w-10 text-white" x-text="parseInt(step / 3 * 100) + '%'"></div>
                        </div>
                    </div>
                </div>
                <!-- PASO 1 -->
                <transition name="fade-slide" mode="out-in">
                    <div class="py-10 min-h-[350px]" x-show="step === 1" key="step1">
                        <div class="mb-5 text-center">
                            <div class="flex justify-center">
                                <div id="dropzone-register" class="dropzone w-36 h-28 border-4 border-blue-400 bg-transparent overflow-hidden p-0 rounded-2xl flex items-center justify-center">
                                    <div class="dz-message text-gray-500 flex flex-col items-center justify-center w-full h-full m-0">
                                        <i class="fas fa-cloud-upload-alt text-4xl mb-2"></i>
                                        <p class="text-xs">Sube tu imagen de perfil</p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="imagen" id="imagen" value="{{ old('imagen') }}">
                            @error('imagen')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="font-bold mb-1 text-white block">Nombre</label>
                            <template x-if="errors.name">
                                <div class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"
                                    x-text="errors.name"></div>
                            </template>
                            <input type="text" name="name"
                                class="w-full px-4 py-3 rounded-lg shadow-sm text-blue-900 font-medium bg-white border border-white"
                                placeholder="Tu nombre..." maxlength="10" required value="{{ old('name') }}">
                        </div>
                        <div class="mb-5">
                            <label class="font-bold mb-1 text-white block">Nombre de usuario</label>
                            <template x-if="errors.username">
                                <div class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"
                                    x-text="errors.username"></div>
                            </template>
                            <input type="text" name="username"
                                class="w-full px-4 py-3 rounded-lg shadow-sm text-blue-900 font-medium bg-white border border-white"
                                placeholder="Tu nombre de usuario..." maxlength="15" required
                                value="{{ old('username') }}">
                        </div>
                        <div class="mb-5">
                            <label class="font-bold mb-1 text-white block">Correo electrónico</label>
                            <template x-if="errors.email">
                                <div class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"
                                    x-text="errors.email"></div>
                            </template>
                            <input type="email" name="email"
                                class="w-full px-4 py-3 rounded-lg shadow-sm text-blue-900 font-medium bg-white border border-white"
                                placeholder="Tu correo electrónico..." maxlength="45" required
                                value="{{ old('email') }}">
                        </div>
                    </div>
                </transition>
                <!-- PASO 2 -->
                <transition name="fade-slide" mode="out-in">
                    <div class="py-10 min-h-[350px]" x-show="step === 2" key="step2">
                        <div class="mb-5">
                            <label class="font-bold mb-1 text-white block">Crea tu contraseña</label>
                            <div class="text-white mt-2 mb-4">
                                Por favor crea una contraseña segura incluyendo:
                                <ul class="list-disc text-sm ml-4 mt-2">
                                    <li>letras minúsculas</li>
                                    <li>números</li>
                                    <li>letras mayúsculas</li>
                                    <li>caracteres especiales</li>
                                </ul>
                            </div>
                            <template x-if="errors.password">
                                <div class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"
                                    x-text="errors.password"></div>
                            </template>
                            @error('password')
                                <div class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</div>
                            @enderror
                            <div class="relative">
                                <input :type="togglePassword ? 'text' : 'password'" @keyup="checkPasswordStrength()"
                                    x-model="form.password" name="password"
                                    class="w-full px-4 py-3 rounded-lg shadow-sm focus:outline-none focus:shadow-outline text-blue-900 font-medium bg-white border border-white"
                                    placeholder="Tu contraseña segura..." minlength="6" required>
                                <div class="absolute right-0 bottom-0 top-0 px-3 py-3 cursor-pointer"
                                    @click="togglePassword = !togglePassword">
                                    <svg :class="{ 'hidden': !togglePassword, 'block': togglePassword }"
                                        xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current text-blue-900"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 19c.946 0 1.81-.103 2.598-.281l-1.757-1.757C12.568 16.983 12.291 17 12 17c-5.351 0-7.424-3.846-7.926-5 .204-.47.674-1.381 1.508-2.297L4.184 8.305c-1.538 1.667-2.121 3.346-2.132 3.379-.069.205-.069.428 0 .633C2.073 12.383 4.367 19 12 19zM12 5c-1.837 0-3.346.396-4.604.981L3.707 2.293 2.293 3.707l18 18 1.414-1.414-3.319-3.319c2.614-1.951 3.547-4.615 3.561-4.657.069-.205.069-.428 0-.633C21.927 11.617 19.633 5 12 5zM16.972 15.558l-2.28-2.28C14.882 12.888 15 12.459 15 12c0-1.641-1.359-3-3-3-.459 0-.888.118-1.277.309L8.915 7.501C9.796 7.193 10.814 7 12 7c5.351 0,7.424 3.846,7.926 5C19.624 12.692,18.76 14.342,16.972 15.558z" />
                                    </svg>
                                    <svg :class="{ 'hidden': togglePassword, 'block': !togglePassword }"
                                        xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current text-blue-900"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12,9c-1.642,0-3,1.359-3,3c0,1.642,1.358,3,3,3c1.641,0,3-1.358,3-3C15,10.359,13.641,9,12,9z" />
                                        <path
                                            d="M12,5c-7.633,0-9.927,6.617-9.948,6.684L1.946,12l0.105,0.316C2.073,12.383,4.367,19,12,19s9.927-6.617,9.948-6.684 L22.054,12l-0.105-0.316C21.927,11.617,19.633,5,12,5z M12,17c-5.351,0-7.424-3.846-7.926-5C4.578,10.842,6.652,7,12,7 c5.351,0,7.424,3.846,7.926,5C19.422,13.158,17.348,17,12,17z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center mt-4 h-3">
                                <div class="w-2/3 flex justify-between h-2">
                                    <div :class="{ 'bg-red-400': passwordStrengthText == 'Demasiado débil' || passwordStrengthText ==
                                            'Puede ser más fuerte' || passwordStrengthText == 'Contraseña fuerte' }"
                                        class="h-2 rounded-full mr-1 w-1/3 bg-gray-300"></div>
                                    <div :class="{ 'bg-orange-400': passwordStrengthText == 'Puede ser más fuerte' ||
                                            passwordStrengthText == 'Contraseña fuerte' }"
                                        class="h-2 rounded-full mr-1 w-1/3 bg-gray-300"></div>
                                    <div :class="{ 'bg-green-400': passwordStrengthText == 'Contraseña fuerte' }"
                                        class="h-2 rounded-full w-1/3 bg-gray-300"></div>
                                </div>
                                <div x-text="passwordStrengthText"
                                    class="text-white font-medium text-sm ml-3 leading-none"></div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="font-bold mb-1 text-white block">Confirmar contraseña</label>
                            <template x-if="errors.password_confirmation">
                                <div class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"
                                    x-text="errors.password_confirmation"></div>
                            </template>
                            @error('password_confirmation')
                                <div class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</div>
                            @enderror
                            <input type="password" name="password_confirmation"
                                class="w-full px-4 py-3 rounded-lg shadow-sm text-blue-900 font-medium bg-white border border-white"
                                placeholder="Repite tu contraseña..." required>
                        </div>
                    </div>
                </transition>
                <!-- PASO 3 -->
                <transition name="fade-slide" mode="out-in">
                    <div class="py-10 min-h-[350px]" x-show="step === 3" key="step3">
                        <div class="mb-5">
                            <label class="font-bold mb-1 text-white block">Género</label>
                            <template x-if="errors.gender">
                                <div class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"
                                    x-text="errors.gender"></div>
                            </template>
                            <div class="flex">
                                <label
                                    class="flex justify-start items-center text-truncate rounded-lg bg-blue-700 pl-4 pr-6 py-3 shadow-sm mr-4 border border-white">
                                    <div class="text-white mr-3">
                                        <input type="radio" value="Male" name="gender"
                                            class="form-radio focus:outline-none focus:shadow-outline" required
                                            {{ old('gender') == 'Male' ? 'checked' : '' }} />
                                    </div>
                                    <div class="select-none text-white">Masculino</div>
                                </label>
                                <label
                                    class="flex justify-start items-center text-truncate rounded-lg bg-blue-700 pl-4 pr-6 py-3 shadow-sm border border-white">
                                    <div class="text-white mr-3">
                                        <input type="radio" value="Female" name="gender"
                                            class="form-radio focus:outline-none focus:shadow-outline" required
                                            {{ old('gender') == 'Female' ? 'checked' : '' }} />
                                    </div>
                                    <div class="select-none text-white">Femenino</div>
                                </label>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="font-bold mb-1 text-white block">Profesión</label>
                            <template x-if="errors.profession">
                                <div class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"
                                    x-text="errors.profession"></div>
                            </template>
                            <input type="text" name="profession"
                                class="w-full px-4 py-3 rounded-lg shadow-sm text-blue-900 font-medium bg-white border border-white"
                                placeholder="Ej. Desarrollador Web" maxlength="50" required
                                value="{{ old('profession') }}">
                        </div>
                    </div>
                </transition>
                <!-- Botones navegación -->
                <div class="flex justify-between mt-8 mb-5">
                    <button x-show="step > 1" type="button" @click="prevStep"
                        class="w-32 focus:outline-none py-2 px-5 rounded-lg shadow-sm text-center text-white bg-blue-700 hover:bg-blue-800 font-medium">Anterior</button>
                    <div class="flex-1"></div>
                    <button x-show="step < 3" type="button" @click="nextStep"
                        class="w-32 ml-auto focus:outline-none py-2 px-5 rounded-lg shadow-sm text-center text-white bg-green-600 hover:bg-green-700 font-medium">Siguiente</button>
                    <button type="submit" x-show="step === 3"
                        class="w-32 ml-auto focus:outline-none py-2 px-5 rounded-lg shadow-sm text-center text-white bg-green-600 hover:bg-green-700 font-medium">Completar</button>
                </div>
            </div>
        @endif
    </form>
</div>

<script>
    function registerWizard() {
        return {
            step: {{ old('step', 1) }},
            image: @json(session('temp_image') ? asset('storage/' . session('temp_image')) : 'https://placehold.co/300x300/e2e8f0/cccccc'),
            passwordStrengthText: '',
            togglePassword: false,
            form: {
                password: ''
            },
            errors: {},
            nextStep() {
                this.errors = {};
                if (this.step === 1) {
                    // Validación Paso 1
                    const name = this.getInputValue('name');
                    const username = this.getInputValue('username');
                    const email = this.getInputValue('email');
                    const imagen = this.getInputValue('imagen');
                    
                    if (!name || name.length > 10) {
                        this.errors.name = 'El nombre es obligatorio y máximo 10 caracteres';
                    }
                    if (!username || username.length > 15) {
                        this.errors.username = 'El usuario es obligatorio y máximo 15 caracteres';
                    }
                    if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email) || email.length > 45) {
                        this.errors.email = 'Correo electrónico inválido o muy largo';
                    }
                    if (!imagen) {
                        this.errors.imagen = 'Debes subir una imagen de perfil';
                        // Mostrar error en el dropzone también
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'text-red-500 text-sm mt-2';
                        errorDiv.textContent = 'Debes subir una imagen de perfil';
                        const dropzoneContainer = document.querySelector('#dropzone-register').parentNode;
                        const existingError = dropzoneContainer.querySelector('.text-red-500');
                        if (existingError) existingError.remove();
                        dropzoneContainer.appendChild(errorDiv);
                    }
                    if (Object.keys(this.errors).length) return;
                }
                if (this.step === 2) {
                    // Validación Paso 2
                    if (!this.form.password || this.form.password.length < 6) {
                        this.errors.password = 'La contraseña debe tener al menos 6 caracteres';
                    }
                    const confirm = this.getInputValue('password_confirmation');
                    if (!confirm) {
                        this.errors.password_confirmation = 'Debes confirmar la contraseña';
                    } else if (this.form.password !== confirm) {
                        this.errors.password_confirmation = 'Las contraseñas no coinciden';
                    }
                    if (Object.keys(this.errors).length) return;
                }
                if (this.step === 3) {
                    // Validación Paso 3
                    const gender = this.getInputValue('gender');
                    const profession = this.getInputValue('profession');
                    if (!gender) {
                        this.errors.gender = 'Selecciona un género';
                    }
                    if (!profession || profession.length > 50) {
                        this.errors.profession = 'La profesión es obligatoria y máximo 50 caracteres';
                    }
                    if (Object.keys(this.errors).length) return;
                }
                if (this.step < 3) this.step++;
            },
            prevStep() {
                if (this.step > 1) this.step--;
            },
            checkPasswordStrength() {
                const val = this.form.password;
                if (val.length < 6) {
                    this.passwordStrengthText = 'Demasiado débil';
                } else if (val.match(/[a-z]/) && val.match(/[A-Z]/) && val.match(/[0-9]/) && val.match(
                    /[^a-zA-Z0-9]/)) {
                    this.passwordStrengthText = 'Contraseña fuerte';
                } else {
                    this.passwordStrengthText = 'Puede ser más fuerte';
                }
            },
            handleSubmit(e) {
                // Validar que hay imagen antes de enviar
                const imagen = this.getInputValue('imagen');
                if (!imagen) {
                    e.preventDefault();
                    this.step = 1;
                    this.errors.imagen = 'Debes subir una imagen de perfil';
                    return false;
                }
                
                // Guarda el paso actual en un input oculto para restaurar tras error
                let stepInput = document.querySelector('input[name="step"]');
                if (!stepInput) {
                    stepInput = document.createElement('input');
                    stepInput.type = 'hidden';
                    stepInput.name = 'step';
                    document.getElementById('registerForm').appendChild(stepInput);
                }
                stepInput.value = this.step;
            },
            getInputValue(name) {
                if (name === 'gender') {
                    const radio = document.querySelector(`[name="${name}"]:checked`);
                    return radio ? radio.value : '';
                }
                const el = document.querySelector(`[name="${name}"]`);
                return el ? el.value : '';
            },
        }
    }
</script>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>
    /* Forzar el color negro en el enlace de eliminar archivo de Dropzone */
    .dropzone .dz-remove {
        color: #111 !important;
        font-weight: 600;
    }
    .dropzone .dz-remove:hover {
        color: #1d4ed8 !important; /* azul al pasar el mouse */
        text-decoration: underline;
    }
    #dropzone-register, #dropzone-register * {
        cursor: pointer !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    Dropzone.autoDiscover = false;
    window.addEventListener('DOMContentLoaded', function() {
        if (window.dropzoneRegisterInstance) {
            window.dropzoneRegisterInstance.destroy();
        }
        setTimeout(function() {
            const dropzoneRegister = new Dropzone('#dropzone-register', {
                url: '/imagenes',
                dictDefaultMessage: 'Arrastra aquí tu imagen de perfil o haz clic',
                acceptedFiles: '.jpg,.jpeg,.png,.gif',
                addRemoveLinks: true,
                dictRemoveFile: 'Eliminar archivo',
                maxFiles: 1,
                maxFilesize: 2, // MB
                uploadMultiple: false,
                paramName: 'imagen',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                init: function () {
                    this.on('maxfilesexceeded', function(file) {
                        this.removeAllFiles();
                        this.addFile(file);
                    });
                    const imagenInput = document.querySelector('[name="imagen"]');
                    if(imagenInput && imagenInput.value.trim()) {
                        const mockFile = { name: imagenInput.value, size: 1234 };
                        this.emit('addedfile', mockFile);
                        this.emit('thumbnail', mockFile, `/perfiles/${mockFile.name}`);
                        this.emit('complete', mockFile);
                        mockFile.previewElement && mockFile.previewElement.classList.add('dz-success', 'dz-complete');
                    }
                }
            });
            window.dropzoneRegisterInstance = dropzoneRegister;
            dropzoneRegister.on('success', function(file, response) {
                document.querySelector('[name=imagen]').value = response.imagen;
            });
            dropzoneRegister.on('removedfile', function(file) {
                document.querySelector('[name=imagen]').value = '';
            });
        }, 100);
    });
</script>
@endpush