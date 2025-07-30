<div class="flex flex-col items-center w-full">
    @if ($users->count())
        @foreach ($users as $user)
            {{-- Solo mostrar usuarios que no sean el usuario actual (si está logueado) --}}
            @if (!auth()->check() || (auth()->check() && $user->id !== auth()->id()))
                <div class="bg-white rounded-xl shadow-sm mb-4 w-full max-w-lg mx-auto">
                    <div class="flex items-center justify-between p-6">
                        <a href="{{ route('posts.index', $user) }}" class="flex items-center group flex-1">
                            <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/usuario.svg') }}"
                                alt="Avatar de {{ $user->username }}"
                                class="w-12 h-12 rounded-full object-cover border-2 border-transparent group-hover:border-[#3B25DD] transition">
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-900 text-base group-hover:border-[#3B25DD] transition">
                                    {{-- Mostrar nombre o username --}}
                                    {{ $user->name ?? $user->username }}
                                </h3>
                                <p class="text-sm text-gray-500">{{ $user->profession ?? 'Usuario de muestra' }}</p>
                            </div>
                        </a>
                        {{-- Solo mostrar botones si el usuario está logueado --}}
                        @auth
                            <div class="flex-shrink-0">
                                @if (!Auth::user()->isFollowing($user))
                                    <form action="{{ route('users.follow', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="bg-[#3B25DD] border-1 border-[#000000] text-[#FFFFFF] px-6 py-2 rounded-full text-sm font-medium hover:bg-[#120073]">
                                            SEGUIR
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('users.unfollow', $user) }}" method="POST" class="inline">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit"
                                            class="bg-[#FFFFFF] border-1 border-[#000000] text-[#000000] px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-50">
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
        <p class="text-center text-gray-500">No hay perfiles registrados aún.</p>
    @endif
</div>