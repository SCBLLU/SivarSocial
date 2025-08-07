@extends('layouts.app')

@section('titulo')

@endsection

@section('contenido-recover')
<main class="container mx-auto mt-10 p-5 mb-5 d-flex items-center justify-center">
 <div>
     <h2 class="font-bold text-center text-3xl mb-10">
        <div class="flex items-center justify-center gap-4">
            <div class="text-3xl font-bold text-white">
                Recuperando Contraseña
            </div>
        </div>
    </h2>
    <div class="md:justify-center items-center gap-4">
        <div class="rounded-lg flex items-center">
            <form class="w-full" method="POST" action="{{ route('recuperar.enviar') }}">
                @csrf

                @if (session('status'))
                    <div class="bg-red-500 text-white font-medium my-2 rounded-lg text-sm p-2 text-center">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="mb-4">
                    <label for="email" class="mb-2 block uppercase text-stone-200 font-bold">
                        Email
                    </label>
                    <input 
                        value=""
                        id="emailre" 
                        name="email" type="email" placeholder="Correo"
                        class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20 
                        @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="bg-red-500 text-white font-medium my-2 rounded-lg text-sm p-2 text-center">
                            {{ $message }}</p>
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
