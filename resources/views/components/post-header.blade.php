@props(['post', 'showMenu' => true, 'linkRoute' => null, 'showFollowButton' => false])

@php
    $userRoute = $linkRoute ?? route('posts.index', $post->user->username ?? $post->user);
@endphp

<!-- Header de publicación reutilizable -->
<div class="flex items-center w-full px-4 py-3 border-b border-gray-200">
    <div class="flex items-center flex-1 min-h-[2.5rem]">
        <img src="{{ $post->user && $post->user->imagen ? asset('perfiles/' . $post->user->imagen) : asset('img/img.jpg') }}"
            alt="Avatar de {{ $post->user ? $post->user->username : 'usuario' }}"
            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-[#3B25DD] transition"
            onerror="this.src='{{ asset('img/img.jpg') }}'">

        <div class="ml-2 flex flex-col flex-1">
            <div class="flex items-center justify-between w-full">
                <!-- Información del usuario (izquierda) -->
                <div class="flex items-center gap-1">
                    <a href="{{ $userRoute }}" class="group">
                        <span class="font-bold text-black group-hover:underline text-sm sm:text-base">
                            {{ $post->user ? ($post->user->name ?? $post->user->username) : 'usuario' }}
                        </span>
                    </a>
                    <x-user-badge :badge="$post->user->insignia ?? null" size="small" />
                </div>

                <!-- Fecha y visibilidad (derecha) -->
                <div class="flex items-center gap-1.5 ml-2">
                    <span class="text-xs text-gray-500">{{ $post->compact_time }}</span>

                    <!-- Icono de visibilidad -->
                    @if($post->visibility === 'public')
                        <div class="inline-flex items-center group/visibility"
                            title="Publicación pública - Visible para todos">
                            <i class="fas fa-globe text-gray-400 text-xs"></i>
                        </div>
                    @elseif($post->visibility === 'followers')
                        <div class="inline-flex items-center group/visibility"
                            title="Solo para seguidores - Visible solo para tus seguidores">
                            <i class="fas fa-users text-gray-400 text-xs"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de seguir y menú de opciones -->
    <div class="flex items-center gap-2 ml-2 h-full">
        <!-- Botón de seguir (solo en listar-post) -->
        @if($post->user && $showFollowButton)
            <livewire:post-follow-button :post="$post" :key="'follow-' . $post->id" />
        @endif

        <!-- Menú de opciones (solo para el propietario) -->
        @if($showMenu)
            @auth
                @if ($post->user_id === Auth::user()->id)
                    {{ $slot ?? '' }}
                @endif
            @endauth
        @endif
    </div>
</div>