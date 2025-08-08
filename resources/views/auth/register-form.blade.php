<div x-data="registerWizard()" x-cloak>
    <form id="registerForm" action="{{ url('/register') }}" method="POST" enctype="multipart/form-data"
        @submit="handleSubmit">
        @csrf

        {{-- Mensaje de éxito solo si viene de la sesión (registro exitoso) --}}
        @if (session('success'))
            <div class="relative px-4 py-3 mb-6 text-green-700 bg-green-100 border border-green-400 rounded">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
                <ul class="pl-5 list-disc">
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
                <div class="py-4 border-b-2 border-gray-300">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-2 text-xs font-bold leading-tight tracking-wide text-gray-800 uppercase md:mb-0"
                            x-text="`Paso ${step} de 2`"></div>
                        <div class="flex items-center md:w-64">
                            <div class="flex w-full h-2 space-x-2">
                                <div class="w-1/2 h-full rounded-full"
                                    :class="step >= 1 ? 'bg-purple-700' : 'bg-gray-300'"></div>
                                <div class="w-1/2 h-full rounded-full"
                                    :class="step >= 2 ? 'bg-purple-700' : 'bg-gray-300'"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- PASO 1 -->
                <transition name="fade-slide" mode="out-in">
                    <div class="pt-10 min-h-[350px]" x-show="step === 1" key="step1">
                        <div class="mb-5 text-center">
                            <div class="flex justify-center">
                                <div id="dropzone-register"
                                    class="flex items-center justify-center p-0 overflow-hidden bg-transparent border-4 border-gray-400 border-dashed dropzone w-36 h-28 rounded-2xl">
                                    <div
                                        class="flex flex-col items-center justify-center w-full h-full m-0 text-gray-500 dz-message">
                                        <i class="mb-2 text-4xl fas fa-cloud-upload-alt"></i>
                                        <p class="text-xs">Subir foto de perfil</p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="imagen" id="imagen" value="{{ old('imagen') }}">
                            <template x-if="errors.imagen">
                                <div class="mt-2 text-sm text-red-500" x-text="errors.imagen"></div>
                            </template>

                        </div>
                        <div class="mb-5">
                            <label class="block mb-1 font-bold text-gray-800">Nombre de Perfil</label>
                            <template x-if="errors.name">
                                <div class="p-2 my-2 text-sm text-center text-white bg-red-500 rounded-lg"
                                    x-text="errors.name"></div>
                            </template>
                            <input type="text" name="name"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg profile-form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Ingresar tu nombre y apellido" maxlength="30" required
                                value="{{ old('name') }}">
                        </div>
                        <div class="mb-5">
                            <label class="block mb-1 font-bold text-gray-800">Nombre de Usuario</label>
                            <template x-if="errors.username">
                                <div class="p-2 my-2 text-sm text-center text-white bg-red-500 rounded-lg"
                                    x-text="errors.username"></div>
                            </template>
                            <input type="text" name="username"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg profile-form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Ingresa tu nombre de usuario" maxlength="15" required
                                value="{{ old('username') }}">
                        </div>
                        <div class="mb-5">
                            <label class="block mb-1 font-bold text-gray-800">Profesión</label>
                            <template x-if="errors.profession">
                                <div class="p-2 my-2 text-sm text-center text-white bg-red-500 rounded-lg"
                                    x-text="errors.profession"></div>
                            </template>
                            <input type="text" name="profession"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg profile-form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Ingrese su profesión" maxlength="50" required
                                value="{{ old('profession') }}">
                        </div>
                        <div class="mb-5">
                            <label class="block mb-1 font-bold text-gray-800">Correo Electrónico</label>
                            <template x-if="errors.email">
                                <div class="p-2 my-2 text-sm text-center text-white bg-red-500 rounded-lg"
                                    x-text="errors.email"></div>
                            </template>
                            <input type="email" name="email"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg profile-form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Ingresar tu correo a registrar" maxlength="45" required
                                value="{{ old('email') }}">
                        </div>
                        <div class="mb-5">
                            <label class="block mb-1 font-bold text-gray-800">Género</label>
                            <div class="flex flex-wrap gap-4">
                                <label
                                    class="flex items-center justify-center flex-1 p-3 bg-white border border-gray-300 rounded-lg shadow-sm basis-0 min-w-[120px]">
                                    <div class="mr-3 text-gray-800">
                                        <input type="radio" value="Male" name="gender" class="form-radio"
                                            {{ old('gender') == 'Male' ? 'checked' : '' }} />
                                    </div>
                                    <div class="text-gray-800 select-none">Masculino</div>
                                </label>
                                <label
                                    class="flex items-center justify-center flex-1 p-3 bg-white border border-gray-300 rounded-lg shadow-sm basis-0 min-w-[120px]">
                                    <div class="mr-3 text-gray-800">
                                        <input type="radio" value="Female" name="gender" class="form-radio"
                                            {{ old('gender') == 'Female' ? 'checked' : '' }} />
                                    </div>
                                    <div class="text-gray-800 select-none">Femenino</div>
                                </label>
                                <label
                                    class="flex items-center justify-center flex-1 p-3 bg-white border border-gray-300 rounded-lg shadow-sm basis-0 min-w-[120px]">
                                    <div class="mr-3 text-gray-800">
                                        <input type="radio" value="" name="gender" class="form-radio"
                                            {{ old('gender') == '' ? 'checked' : '' }} />
                                    </div>
                                    <div class="text-gray-800 select-none">Otro</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </transition>
                <!-- PASO 2 -->
                <transition name="fade-slide" mode="out-in">
                    <div class="pt-10 min-h-[350px]" x-show="step === 2" key="step2">
                        <div class="mb-5">
                            <label class="block mb-1 font-bold text-gray-800">Crea tu contraseña</label>
                            <div class="mt-2 mb-4 text-gray-600">
                                Por favor crea una contraseña segura incluyendo:
                                <ul class="mt-2 ml-4 text-sm list-disc">
                                    <li>letras minúsculas</li>
                                    <li>números</li>
                                    <li>letras mayúsculas</li>
                                    <li>caracteres especiales</li>
                                </ul>
                            </div>
                            <template x-if="errors.password">
                                <div class="p-2 my-2 text-sm text-center text-white bg-red-500 rounded-lg"
                                    x-text="errors.password"></div>
                            </template>
                            @error('password')
                                <div class="p-2 my-2 text-sm text-center text-white bg-red-500 rounded-lg">
                                    {{ $message }}</div>
                            @enderror
                            <div class="relative">
                                <input :type="togglePassword ? 'text' : 'password'" @keyup="checkPasswordStrength()"
                                    x-model="form.password" name="password"
                                    class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg profile-form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Tu contraseña segura..." minlength="6" required>
                                <div class="absolute top-0 bottom-0 right-0 px-3 py-3 cursor-pointer"
                                    @click="togglePassword = !togglePassword">
                                    <svg :class="{ 'hidden': !togglePassword, 'block': togglePassword }"
                                        xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-900 fill-current"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 19c.946 0 1.81-.103 2.598-.281l-1.757-1.757C12.568 16.983 12.291 17 12 17c-5.351 0-7.424-3.846-7.926-5 .204-.47.674-1.381 1.508-2.297L4.184 8.305c-1.538 1.667-2.121 3.346-2.132 3.379-.069.205-.069.428 0 .633C2.073 12.383 4.367 19 12 19zM12 5c-1.837 0-3.346.396-4.604.981L3.707 2.293 2.293 3.707l18 18 1.414-1.414-3.319-3.319c2.614-1.951 3.547-4.615 3.561-4.657.069-.205.069-.428 0-.633C21.927 11.617 19.633 5 12 5zM16.972 15.558l-2.28-2.28C14.882 12.888 15 12.459 15 12c0-1.641-1.359-3-3-3-.459 0-.888.118-1.277.309L8.915 7.501C9.796 7.193 10.814 7 12 7c5.351 0,7.424 3.846,7.926 5C19.624 12.692,18.76 14.342,16.972 15.558z" />
                                    </svg>
                                    <svg :class="{ 'hidden': togglePassword, 'block': !togglePassword }"
                                        xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-900 fill-current"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12,9c-1.642,0-3,1.359-3,3c0,1.642,1.358,3,3,3c1.641,0,3-1.358,3-3C15,10.359,13.641,9,12,9z" />
                                        <path
                                            d="M12,5c-7.633,0-9.927,6.617-9.948,6.684L1.946,12l0.105,0.316C2.073,12.383,4.367,19,12,19s9.927-6.617,9.948-6.684 L22.054,12l-0.105-0.316C21.927,11.617,19.633,5,12,5z M12,17c-5.351,0-7.424-3.846-7.926-5C4.578,10.842,6.652,7,12,7 c5.351,0,7.424,3.846,7.926,5C19.422,13.158,17.348,17,12,17z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center h-3 mt-4">
                                <div class="flex justify-between w-2/3 h-2">
                                    <div :class="{
                                        'bg-red-400': passwordStrengthText == 'Demasiado débil' ||
                                            passwordStrengthText ==
                                            'Puede ser más fuerte' || passwordStrengthText == 'Contraseña fuerte'
                                    }"
                                        class="w-1/3 h-2 mr-1 bg-gray-300 rounded-full"></div>
                                    <div :class="{
                                        'bg-orange-400': passwordStrengthText == 'Puede ser más fuerte' ||
                                            passwordStrengthText == 'Contraseña fuerte'
                                    }"
                                        class="w-1/3 h-2 mr-1 bg-gray-300 rounded-full"></div>
                                    <div :class="{ 'bg-green-400': passwordStrengthText == 'Contraseña fuerte' }"
                                        class="w-1/3 h-2 bg-gray-300 rounded-full"></div>
                                </div>
                                <div x-text="passwordStrengthText"
                                    class="ml-3 text-sm font-medium leading-none text-gray-600"></div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="block mb-1 font-bold text-gray-800">Confirmar contraseña</label>
                            <template x-if="errors.password_confirmation">
                                <div class="p-2 my-2 text-sm text-center text-white bg-red-500 rounded-lg"
                                    x-text="errors.password_confirmation"></div>
                            </template>
                            @error('password_confirmation')
                                <div class="p-2 my-2 text-sm text-center text-white bg-red-500 rounded-lg">
                                    {{ $message }}</div>
                            @enderror
                            <input type="password" name="password_confirmation"
                                class="w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg profile-form-input focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Repite tu contraseña..." required>
                        </div>
                    </div>
                </transition>
                <!-- Botones navegación -->
                <div class="flex justify-between">
                    <button x-show="step > 1" type="button" @click="prevStep"
                        class="w-32 px-5 py-2 font-medium text-center text-gray-800 bg-white border rounded-lg shadow-sm focus:outline-none hover:bg-gray-100" style="border-radius:30px">Anterior</button>
                    <div class="flex-1"></div>
                    <button x-show="step < 2" type="button" @click="nextStep"
                        class="w-32 px-5 py-2 ml-auto font-medium text-center text-white bg-purple-700 rounded-lg shadow-sm focus:outline-none hover:bg-purple-900" style="border-radius:30px">Siguiente</button>
                    <button type="submit" x-show="step === 2"
                        class="w-32 px-5 py-2 ml-auto font-medium text-center text-white bg-blue-600 rounded-lg shadow-sm focus:outline-none hover:bg-blue-700" style="border-radius:30px">Completar</button>
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
            async nextStep() {
                this.errors = {};
                if (this.step === 1) {
                    const name = this.getInputValue('name');
                    const username = this.getInputValue('username');
                    const email = this.getInputValue('email');
                    const imagen = this.getInputValue('imagen');
                    const profession = this.getInputValue('profession');
                    const gender = this.getInputValue('gender');

                    // Validaciones del lado del cliente
                    if (!name || name.length > 30) this.errors.name =
                        'El nombre es obligatorio y máximo 30 caracteres';
                    if (!username || username.length > 15) this.errors.username =
                        'El usuario es obligatorio y máximo 15 caracteres';
                    if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email) || email.length > 45) this.errors
                        .email = 'Correo electrónico inválido o muy largo';
                    if (!profession || profession.length > 50) this.errors.profession =
                        'La profesión es obligatoria y máximo 50 caracteres';
                    if (!imagen) this.errors.imagen = 'Debes subir una imagen de perfil';

                    // Si hay errores de cliente, no continuar
                    if (Object.keys(this.errors).length > 0) return;

                    // Validación del lado del servidor para campos únicos
                    try {
                        const response = await fetch('/register/validate-step1', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                username,
                                email
                            })
                        });
                        const data = await response.json();
                        if (!response.ok) {
                            this.errors = data.errors;
                            return;
                        }
                    } catch (error) {
                        console.error('Error en la validación:', error);
                        return;
                    }
                }
                if (this.step < 2) this.step++;
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
                // La validación del paso 1 ya se hizo en nextStep.
                // Solo se necesita validar el paso 2 al hacer submit.
                if (this.step === 2) {
                    this.errors = {};
                    if (!this.form.password || this.form.password.length < 6) {
                        this.errors.password = 'La contraseña debe tener al menos 6 caracteres';
                    }
                    const confirm = this.getInputValue('password_confirmation');
                    if (!confirm) {
                        this.errors.password_confirmation = 'Debes confirmar la contraseña';
                    } else if (this.form.password !== confirm) {
                        this.errors.password_confirmation = 'Las contraseñas no coinciden';
                    }
                    if (Object.keys(this.errors).length > 0) {
                        e.preventDefault();
                        return;
                    }
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
                    return radio ? radio.value : null; // Devuelve null si no hay nada seleccionado
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
            color: #1d4ed8 !important;
            /* azul al pasar el mouse */
            text-decoration: underline;
        }

        #dropzone-register {
            border-style: dashed !important;
        }

        #dropzone-register,
        #dropzone-register * {
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
                    acceptedFiles: '.jpg,.jpeg,.png',
                    addRemoveLinks: true,
                    dictRemoveFile: 'Eliminar archivo',
                    maxFiles: 1,
                    maxFilesize: 20, // MB
                    uploadMultiple: false,
                    paramName: 'imagen',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    init: function() {
                        this.on('maxfilesexceeded', function(file) {
                            this.removeAllFiles();
                            this.addFile(file);
                        });
                        const imagenInput = document.querySelector('[name="imagen"]');
                        if (imagenInput && imagenInput.value.trim()) {
                            const mockFile = {
                                name: imagenInput.value,
                                size: 1234
                            };
                            this.emit('addedfile', mockFile);
                            this.emit('thumbnail', mockFile, `/perfiles/${mockFile.name}`);
                            this.emit('complete', mockFile);
                            mockFile.previewElement && mockFile.previewElement.classList.add(
                                'dz-success', 'dz-complete');
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
