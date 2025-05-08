@extends('layouts.app')

{{-- la section inyecta el contenido dentro del yield --}}

@section('titulo')
    <div class="flex items-center justify-center gap-4">
        <div class="text-3xl font-bold text-white">
            Regístrate en
        </div>
        <div>
            <img srcset="https://res.cloudinary.com/dj848z4er/image/upload/v1746637776/dp3pooq2vfhgqfgygkm3.png 2x"
                alt="LOGO2" class="h-25">
        </div>
    </div>
@endsection

@section('contenido')
    <div class="md:flex md:justify-center items-center gap-4  ">

        <div class="md:w-4/12 flex flex-col justify-center items-center bg-blue-800 p-7 rounded-lg">
            <p class="text-3xl font-bold text-white pb-4">Imagen Aquí</p>
            <div>
                <input type="file"
                    class="file:mr-4 file:rounded-full file:border-0 file:bg-violet-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-600 dark:file:text-violet-100 dark:hover:file:bg-violet-500 ..." />
            </div>
        </div>

        <div class="md:w-4/12 p-8 rounded-lg flex items-center">
            <form class="w-full">
                <div class="mb-4">
                    <label for="name" class="mb-2 block uppercase text-stone-200 font-bold">
                        Nombre
                    </label>
                    <input 
                        id="name" 
                        name="name"     
                        type="text" 
                        placeholder="Tu Nombre"
                        class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20 
                        @error('name') border-red-500 @enderror">
                </div>

                <div class="mb-4">
                    <label for="username" class="mb-2 block uppercase text-stone-200 font-bold">
                        Username
                    </label>
                    <input 
                        id="username" 
                        name="username" 
                        type="text" 
                        placeholder="Tu Nombre de Usuario"
                        class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20 
                        @error('username') border-red-500 @enderror">
                </div>

                <div class="mb-4">
                    <label for="email" class="mb-2 block uppercase text-stone-200 font-bold">
                        Email
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        placeholder="Tu Correo Electrónico"
                        class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20 
                        @error('email') border-red-500 @enderror">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="mb-2 block uppercase text-stone-200 font-bold">
                        Password
                    </label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        placeholder="Password de registro"
                        class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20 
                        @error('password') border-red-500 @enderror">
                </div>
                
                <div class="mb-4">
                    <label for="password_confirmation" class="mb-2 block uppercase text-stone-200 font-bold">
                        Confirmar Password
                    </label>
                    <input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        placeholder="Repite tu Password"
                        class="border p-3 w-full rounded-lg bg-stone-100 text-black placeholder:text-stone-500
                        invalid:border-pink-500 invalid:text-pink-600 focus:border-sky-500 focus:outline focus:outline-sky-500 focus:invalid:border-pink-500 focus:invalid:outline-pink-500 disabled:border-gray-200 disabled:bg-gray-50 disabled:text-gray-500 disabled:shadow-none dark:disabled:border-gray-700 dark:disabled:bg-gray-800/20 
                        @error('password_confirmation') border-red-500 @enderror">
                </div>

                <button type="submit" value="Crear Cuenta"
                    class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded w-full mt-4">
                    Crear Cuenta
                </button>
            </form>
        </div>
    </div>
@endsection
