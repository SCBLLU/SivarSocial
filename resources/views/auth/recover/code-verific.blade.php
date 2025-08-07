@extends('layouts.app')

@section('titulo')

@endsection

@section('contenido-recover')
<main class="container mx-auto mt-10 p-5 mb-5 d-flex items-center justify-center">
 <div>
     <h2 class="font-bold text-center text-3xl mb-5">
        <div class="flex items-center justify-center gap-4">
            <div class="text-3xl font-bold text-white">
                Codigo de verificación
            </div>
        </div>
    </h2>
    <h3 class="text-width font-bold text-center mb-5">
        <div class="flex items-center justify-center gap-4">
            <div class="font-bold pl-12 pr-12 text-white">
                Te hemos enviado un codigo de 6 digitos a tu correo {{ preg_replace('/(?<=.{2}).(?=.*@)/', '*', session('email_verificacion')) }}
            </div>
        </div>
    </h3>

    <div class="md:justify-center items-center gap-4">
        <div class="rounded-lg flex items-center">
            <form class="w-full" method="POST" action="{{ route('code.verification') }}">
                @csrf

                @if (session('status'))
                    <div class="bg-red-500 text-white font-medium my-2 rounded-lg text-sm p-2 text-center">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-4">
                     <label for="codigo" class="mb-2 block uppercase text-stone-200 font-bold">
                        Código
                     </label>
                     <input 
                        value="{{ old('codigo') }}"
                        id="codigo" 
                        name="codigo" 
                        type="text" 
                        placeholder="Código de 6 dígitos"
                        class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 @error('codigo') border-red-500 @enderror">
                    @error('codigo')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded w-full mt-4">
                    Recuperar
                </button>
            </form>
        </div>
    </div>
 </div>

</main>
    
@endsection
