@extends('layouts.app')

@section('titulo')

@endsection

@section('contenido-recover')
<main class="container mx-auto mt-10 p-5 mb-5 d-flex items-center justify-center">
 <div>
     <h2 class="font-bold text-center text-3xl mb-5">
        <div class="flex items-center justify-center gap-4">
            <div class="text-3xl font-bold text-white">
                Nueva contraseña
            </div>
        </div>
    </h2>

    <div class="md:justify-center items-center gap-4">
        <div class="rounded-lg flex items-center">
            <form class="w-full" method="POST" action="{{ route('restablecer') }}">
                @csrf

                @if (session('status'))
                    <div class="bg-red-500 text-white font-medium my-2 rounded-lg text-sm p-2 text-center">
                        {{ session('status') }}
                    </div>
                @endif
                <div x-data="passwordStrength()">
                        <div class="mb-5">
                            <label class="font-bold mb-1 text-white block">Escribe tu nueva contraseña, y anotalo en un lugar seguro.</label>
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
                            <div class="relative">
                            <input :type="togglePassword2 ? 'text' : 'password'" type="password" name="password_confirmation"
                                class="w-full px-4 py-3 rounded-lg shadow-sm text-blue-900 font-medium bg-white border border-white"
                                placeholder="Repite tu contraseña..." required>
                                <div class="absolute right-0 bottom-0 top-0 px-3 py-3 cursor-pointer"
                                    @click="togglePassword2 = !togglePassword2">
                                    <svg :class="{ 'hidden': !togglePassword2, 'block': togglePassword2 }"
                                        xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current text-blue-900"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 19c.946 0 1.81-.103 2.598-.281l-1.757-1.757C12.568 16.983 12.291 17 12 17c-5.351 0-7.424-3.846-7.926-5 .204-.47.674-1.381 1.508-2.297L4.184 8.305c-1.538 1.667-2.121 3.346-2.132 3.379-.069.205-.069.428 0 .633C2.073 12.383 4.367 19 12 19zM12 5c-1.837 0-3.346.396-4.604.981L3.707 2.293 2.293 3.707l18 18 1.414-1.414-3.319-3.319c2.614-1.951 3.547-4.615 3.561-4.657.069-.205.069-.428 0-.633C21.927 11.617 19.633 5 12 5zM16.972 15.558l-2.28-2.28C14.882 12.888 15 12.459 15 12c0-1.641-1.359-3-3-3-.459 0-.888.118-1.277.309L8.915 7.501C9.796 7.193 10.814 7 12 7c5.351 0,7.424 3.846,7.926 5C19.624 12.692,18.76 14.342,16.972 15.558z" />
                                    </svg>
                                    <svg :class="{ 'hidden': togglePassword2, 'block': !togglePassword2 }"
                                        xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 fill-current text-blue-900"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12,9c-1.642,0-3,1.359-3,3c0,1.642,1.358,3,3,3c1.641,0,3-1.358,3-3C15,10.359,13.641,9,12,9z" />
                                        <path
                                            d="M12,5c-7.633,0-9.927,6.617-9.948,6.684L1.946,12l0.105,0.316C2.073,12.383,4.367,19,12,19s9.927-6.617,9.948-6.684 L22.054,12l-0.105-0.316C21.927,11.617,19.633,5,12,5z M12,17c-5.351,0-7.424-3.846-7.926-5C4.578,10.842,6.652,7,12,7 c5.351,0,7.424,3.846,7.926,5C19.422,13.158,17.348,17,12,17z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 mb-5 px-4 rounded w-full mt-4">
                    Recuperar
                </button>
            </form>
        </div>
    </div>
 </div>

</main>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    function passwordStrength() {
        return {
            form: {
                password: '',
            },
            togglePassword: false,
            togglePassword2: false,
            passwordStrengthText: '',
            checkPasswordStrength() {
                const password = this.form.password;
                let strength = 0;

                if (password.length >= 6) strength++;
                if (password.match(/[a-z]/)) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[\W_]/)) strength++;

                if (strength <= 2) {
                    this.passwordStrengthText = 'Demasiado débil';
                } else if (strength <= 4) {
                    this.passwordStrengthText = 'Puede ser más fuerte';
                } else {
                    this.passwordStrengthText = 'Contraseña fuerte';
                }
            },
            errors: {},
        }
    }
</script>


    
@endsection
