<div class="flex flex-col items-center w-full">
    @if ($users->count())
        @foreach ($users as $user)
            {{-- Solo mostrar usuarios que no sean el usuario actual (si está logueado) --}}
            @if (!auth()->check() || (auth()->check() && $user->id !== auth()->id()))
                <div class="bg-white rounded-xl shadow-sm mb-3 sm:mb-4 w-full max-w-lg mx-auto">
                    <div class="flex items-center justify-between p-3">
                        <a href="{{ route('posts.index', $user) }}" class="flex items-center group flex-1">
                            <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg') }}"
                                alt="Avatar de {{ $user->username }}"
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-transparent group-hover:border-[#3B25DD] transition">
                            <div class="ml-3 sm:ml-4">
                                <h3
                                    class="font-semibold text-gray-900 text-sm sm:text-base group-hover:border-[#3B25DD] transition">
                                    {{-- Mostrar nombre o username --}}
                                    {{ $user->name ?? $user->username }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500">{{ $user->profession ?? 'Usuario de muestra' }}</p>
                            </div>
                        </a>
                        {{-- Solo mostrar botones si el usuario está logueado --}}
                        @auth
                            <div class="flex-shrink-0">
                                @if (!Auth::user()->isFollowing($user))
                                    <form action="{{ route('users.follow', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="bg-[#3B25DD] border-1 border-[#000000] text-[#FFFFFF] px-3 py-1.5 sm:px-6 sm:py-2 rounded-full text-xs sm:text-sm font-medium hover:bg-[#120073]">
                                            SEGUIR
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('users.unfollow', $user) }}" method="POST" class="inline">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit"
                                            class="bg-[#FFFFFF] border-1 border-[#000000] text-[#000000] px-2 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-medium hover:bg-gray-50">
                                            NO SEGUIR
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <p class="text-center text-gray-500 text-xs sm:text-sm">No se ha encontrado ningún perfil.</p>
    @endif
</div>