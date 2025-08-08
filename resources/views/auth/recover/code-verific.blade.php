@extends('layouts.app')

@section('titulo')
@endsection

@section('contenido-recover')
    <main class="container items-center justify-center p-5 mx-auto mt-10 mb-5 d-flex">
        <div>
            <h2 class="mb-5 text-3xl font-bold text-center">
                <div class="flex items-center justify-center gap-4">
                    <div class="text-3xl font-bold text-white">
                        Codigo de verificación
                    </div>
                </div>
            </h2>
            <h3 class="mb-5 font-bold text-center text-width">
                <div class="flex items-center justify-center gap-4">
                    <div class="pl-12 pr-12 font-bold text-white">
                        Te hemos enviado un codigo de 6 digitos a tu correo
                        {{ preg_replace('/(?<=.{2}).(?=.*@)/', '*', session('email_verificacion')) }}
                    </div>
                </div>
            </h3>

            <div class="items-center gap-4 md:justify-center">
                <div class="flex items-center rounded-lg">
                    <form class="w-full" method="POST" action="{{ route('code.verification') }}">
                        @csrf

                        @if (session('status'))
                            <div class="p-2 my-2 text-sm font-medium text-center text-white bg-red-500 rounded-lg">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="mb-4">
                            <label for="codigo" class="block mb-2 font-bold uppercase text-stone-200">
                                Código
                            </label>
                            <input value="{{ old('codigo') }}" id="codigo" name="codigo" type="text"
                                placeholder="Código de 6 dígitos"
                                class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 @error('codigo') border-red-500 @enderror">
                            @error('codigo')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2 mt-4 font-bold text-white bg-blue-700 rounded hover:bg-blue-800"
                            style="border-radius:30px">
                            Recuperar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </main>
@endsection
