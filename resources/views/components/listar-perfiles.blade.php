<div class="flex flex-col items-center w-full">
    @if ($users->count())
        @foreach ($users as $user)
            {{-- Solo mostrar usuarios que no sean el usuario actual (si está logueado) --}}
            @if (!auth()->check() || (auth()->check() && $user->id !== auth()->id()))
                <div class="w-full max-w-lg mx-auto mb-3 bg-white shadow-sm rounded-xl sm:mb-4"
                    data-user-id="{{ $user->id }}">
                    <div class="flex items-center justify-between p-3">
                        <a href="{{ route('posts.index', $user) }}" class="flex items-center flex-1 group">
                            <div class="relative">
                                <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg') }}"
                                    alt="Avatar de {{ $user->username }}"
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-transparent group-hover:border-[#3B25DD] transition">
                                {{-- El círculo verde se agregará aquí dinámicamente via JavaScript --}}
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h3
                                    class="font-semibold text-gray-900 text-sm sm:text-base group-hover:border-[#3B25DD] transition">
                                    <div class="flex items-center gap-1 min-h-5">
                                        <span>{{ $user->name ?? $user->username }}</span>
                                        <x-user-badge :badge="$user->insignia" size="small" />
                                    </div>
                                </h3>
                                <p class="text-xs text-gray-500 sm:text-sm">
                                    {{ $user->profession ?? 'Usuario de muestra' }}</p>
                            </div>
                        </a>
                        {{-- Solo mostrar botones si el usuario está logueado --}}
                        @auth
                            <div class="flex-shrink-0">
                                <livewire:follow-user :user="$user" size="small" />
                            </div>
                        @endauth
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <p class="p-4 mb-5 text-xs text-center text-gray-500 sm:text-sm">No se ha encontrado ningún perfil.</p>
    @endif
</div>
