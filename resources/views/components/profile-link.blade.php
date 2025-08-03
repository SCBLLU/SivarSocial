@props(['user'])

<a href="{{ route('posts.index', $user) }}"
    class="flex items-center justify-between group max-w-md mx-auto rounded-lg transition">
    {{-- Contenedor del texto alineado a la derecha --}}
    <div class="text-right mr-4 flex-1 cursor-pointer">
        <h2 class="ml-2 font-bold text-black group-hover:text-[#3B25DD] transition text-base">
            {{ $user->name ?? $user->username }}
        </h2>
        <p class="text-gray-500 text-xs">
            {{ $user->profession ?? 'Usuario de muestra' }}
        </p>
    </div>

    {{-- Imagen de perfil --}}
    <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/usuario.svg') }}"
        alt="Avatar de {{ $user->username }}"
        class="w-9 h-9 rounded-full object-cover border-2 border-[#3B25DD] cursor-pointer">
</a>