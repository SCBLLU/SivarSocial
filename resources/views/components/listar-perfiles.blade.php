<div class="flex flex-col items-center w-full">
    @if ($users->count())
        @foreach ($users as $user)
            @if (auth()->check() && $user->id !== auth()->id())
                <div class="bg-white rounded-xl shadow-sm mb-4 w-full max-w-lg mx-auto">
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center">
                            <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/usuario.svg') }}"
                                alt="Avatar de {{ $user->username }}" class="w-12 h-12 rounded-full object-cover">
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-900 text-base">{{ $user->name ?? $user->username }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->profession ?? 'Usuario de muestra' }}</p>
                            </div>
                        </div>
                        @auth
                            <div class="flex-shrink-0">
                                @if (!Auth::user()->isFollowing($user))
                                    <form action="{{ route('users.follow', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-6 py-2 rounded-full transition-colors duration-200">
                                            SEGUIR
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('users.unfollow', $user) }}" method="POST" class="inline">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium text-sm px-4 py-2 rounded-full transition-colors duration-200">
                                            NO SEGUIR
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>
            @elseif (!auth()->check())
                <div class="bg-white rounded-xl shadow-sm mb-4 w-full max-w-lg mx-auto">
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center">
                            <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/usuario.svg') }}"
                                alt="Avatar de {{ $user->username }}" class="w-12 h-12 rounded-full object-cover">
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-900 text-base">{{ $user->name ?? $user->username }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->profession ?? 'Usuario de muestra' }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-6 py-2 rounded-full transition-colors duration-200">
                                SEGUIR
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <p class="text-center text-gray-500">No hay perfiles registrados aún.</p>
    @endif
</div>