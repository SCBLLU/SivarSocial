<div class="mt-2 space-y-1 text-xs sm:text-sm text-gray-800">
    <p>
        <a href="{{ route('users.followers', $user->username) }}"
            class="group hover:text-[#3B25DD] transition-colors cursor-pointer">
            <span
                class="font-semibold text-black group-hover:text-[#3B25DD] transition">{{ number_format($followersCount) }}</span>
            <span class="text-gray-500 group-hover:text-[#3B25DD] transition">Seguidores</span>
        </a>
    </p>
    <p>
        <a href="{{ route('users.following', $user->username) }}"
            class="group hover:text-[#3B25DD] transition-colors cursor-pointer">
            <span
                class="font-semibold text-black group-hover:text-[#3B25DD] transition">{{ number_format($followingCount) }}</span>
            <span class="text-gray-500 group-hover:text-[#3B25DD] transition">Siguiendo</span>
        </a>
    </p>
    <p>
        <span class="font-semibold text-black">{{ number_format($postsCount) }}</span>
        <span class="text-gray-500">Publicaciones</span>
    </p>
</div>