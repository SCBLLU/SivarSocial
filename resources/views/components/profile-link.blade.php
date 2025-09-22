@props(['user'])

@if($user instanceof \App\Models\User)
    {{-- Bloque para usuarios normales --}}
    <a href="{{ route('posts.index', ['user' => $user->username]) }}"
        class="profile-cont flex items-center justify-between group max-w-md mx-auto rounded-lg transition">
        
        <div class="profile-cont-title text-right mr-4 flex-1 cursor-pointer">
            <h2 class="ml-2 font-bold text-black group-hover:text-[#3B25DD] transition text-base">
                {{ $user->name ?? $user->username }}
            </h2>
            <p class="text-gray-500 text-xs">
                {{ $user->profession ?? 'Usuario de muestra' }}
            </p>
        </div>

    {{-- Imagen de perfil --}}
        <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg') }}"
            alt="Avatar de {{ $user->username }}"
            class="w-9 h-9 rounded-full object-cover cursor-pointer">
    </a>
@endif

{{-- Bloque adicional solo si es un superusuario --}}
@if($user instanceof \App\Models\su_ad)
<div class="hidden md:block">
    <a href="{{ route('su.dash') }}"
        class="flex items-center justify-between group max-w-md mx-auto rounded-lg transition">
        <div class="text-right mr-4 flex-1 cursor-pointer">
            <h2 class="ml-2 font-bold text-black group-hover:text-[#3B25DD] transition text-base">
                {{ $user->name }} (SU)
            </h2>
            <p class="text-gray-500 text-xs">
                {{ $user->profession ?? 'Superusuario' }}
            </p>
        </div>

     {{-- Imagen de perfil --}}
        <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/super.jpg') }}"
            alt="Avatar de {{ $user->username }}"
            class="w-9 h-9 rounded-full object-cover cursor-pointer">
    </a>
</div>
    
<div class="md:hidden">
    <a href="{{ route('su.dash') }}"
        class="flex items-center justify-between group max-w-md mx-auto rounded-lg transition ">
        <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/super.jpg') }}"
            alt="Avatar de {{ $user->username }}"
            class="w-9 h-9 rounded-full object-cover cursor-pointer">
        <div class="text-left mr-4 flex-1 cursor-pointer">
            <h2 class="ml-4 font-bold text-black group-hover:text-[#3B25DD] transition text-base">
                {{ $user->name }} (SU)
            </h2>
            <p class="ml-4 text-gray-500 text-xs">
                {{ $user->profession ?? 'Superusuario' }}
            </p>
        </div>
    </a>
</div>
    
@endif