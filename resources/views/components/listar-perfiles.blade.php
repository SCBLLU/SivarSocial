<div class="flex flex-col items-center w-full">
    @if ($users->count())
        @foreach ($users as $user)
            @if (auth()->check() && $user->id !== auth()->id())
            <div class="bg-white rounded-2xl shadow-lg mb-8 w-full max-w-md flex flex-col items-center">
                <div class="flex items-center w-full px-4 py-4 border-b border-gray-200">
                    <a href="{{ route('posts.index', $user) }}" class="flex items-center group">
                        <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/default-avatar.png') }}" alt="Avatar de {{ $user->username }}" class="w-14 h-14 rounded-full object-cover border-2 border-purple-700 group-hover:border-black transition">
                        <div class="ml-4 flex flex-col">
                            <span class="font-bold text-black text-lg group-hover:underline">{{ $user->name ?? $user->username }}</span>
                            <span class="text-xs text-gray-400 mt-1">{{ $user->profession ?? '' }}</span>
                        </div>
                    </a>
                    @auth
                        <div class="ml-auto">
                            @if (!Auth::user()->isFollowing($user))
                                <form action="{{ route('users.follow', $user) }}" method="POST">
                                    @csrf
                                    <input type="submit" class="bg-sky-400 hover:bg-blue-500 text-white uppercase rounded-lg text-xs font-bold cursor-pointer py-1 px-3" value="Seguir" />
                                </form>
                            @else
                                <form action="{{ route('users.unfollow', $user) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <input type="submit" class="bg-blue-500 hover:bg-sky-400 text-white uppercase rounded-lg text-xs font-bold cursor-pointer py-1 px-3" value="Dejar de seguir" />
                                </form>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
            @elseif (!auth()->check())
            <div class="bg-white rounded-2xl shadow-lg mb-8 w-full max-w-md flex flex-col items-center">
                <div class="flex items-center w-full px-4 py-4 border-b border-gray-200">
                    <a href="{{ route('posts.index', $user) }}" class="flex items-center group">
                        <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/default-avatar.png') }}" alt="Avatar de {{ $user->username }}" class="w-14 h-14 rounded-full object-cover border-2 border-purple-700 group-hover:border-black transition">
                        <div class="ml-4 flex flex-col">
                            <span class="font-bold text-black text-lg group-hover:underline">{{ $user->name ?? $user->username }}</span>
                            <span class="text-xs text-gray-400 mt-1">{{ $user->profession ?? '' }}</span>
                        </div>
                    </a>
                </div>
            </div>
            @endif
        @endforeach
    @else
        <p class="text-center text-gray-500">No hay perfiles registrados aún.</p>
    @endif
</div>
