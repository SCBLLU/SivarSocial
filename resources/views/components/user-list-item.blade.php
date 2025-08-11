<div class="p-3 sm:p-4 lg:p-6 hover:bg-gray-50 transition-colors">
    <div class="flex items-center justify-between gap-2 sm:gap-3">
        {{-- Info del usuario --}}
        <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
            {{-- Foto de perfil --}}
            <a href="{{ route('posts.index', $user->username) }}" class="flex-shrink-0">
                <div
                    class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full border-2 border-gray-200 overflow-hidden hover:border-[#3B25DD] transition-colors">
                    @if($user->imagen_url)
                        <img src="{{ $user->imagen_url }}" alt="Foto de perfil de {{ $user->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    @endif
                </div>
            </a>

            {{-- Nombre y estadísticas --}}
            <div class="flex-1 min-w-0 max-w-[calc(100%-120px)] sm:max-w-none">
                <a href="{{ route('posts.index', $user->username) }}"
                    class="block hover:text-[#3B25DD] transition-colors">
                    <div class="flex items-center gap-1 sm:gap-2 min-h-5">
                        <h3 class="text-sm sm:text-base font-bold text-black truncate">
                            {{ $user->name }}
                        </h3>
                        @if($user->insignia)
                            <x-user-badge :badge="$user->insignia" size="small" />
                        @endif
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 truncate">{{ '@' . $user->username }}</p>
                </a>

                {{-- Estadísticas del usuario --}}
                <div class="flex items-center gap-2 sm:gap-3 mt-1 text-xs text-gray-500">
                    <span class="hover:text-[#3B25DD] transition-colors">
                        <span class="font-medium">{{ number_format($user->followers_count ?? 0) }}</span>
                        <span class="hidden sm:inline ml-1">seguidores</span>
                        <span class="sm:hidden ml-1">seg.</span>
                    </span>
                    <span class="hover:text-[#3B25DD] transition-colors">
                        <span class="font-medium">{{ number_format($user->posts_count ?? 0) }}</span>
                        <span class="ml-1">posts</span>
                    </span>
                </div>

                {{-- Profesión si existe --}}
                @if($user->profession)
                    <p class="text-xs text-gray-500 mt-1 truncate hidden sm:block">{{ $user->profession }}</p>
                @endif
            </div>
        </div>

        {{-- Botón de seguir --}}
        @if($showFollowButton)
            <div class="flex-shrink-0 ml-auto">
                @auth
                    @if($user->id !== auth()->id())
                        <livewire:follow-user :user="$user" size="small" :key="'follow-' . $user->id . '-' . auth()->id()" />
                    @else
                        <span
                            class="text-xs text-gray-500 font-medium px-2 sm:px-3 py-1.5 sm:py-2 bg-gray-100 rounded-full">Tú</span>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>