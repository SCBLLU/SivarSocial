<div class="mt-2 space-y-1 text-xs sm:text-sm text-gray-800">
    <p>
        <a href="{{ route('users.followers', $user->username) }}" 
           class="hover:text-blue-600 transition-colors cursor-pointer">
            <span class="font-semibold">{{ number_format($followersCount) }}</span> Seguidores
        </a>
    </p>
    <p>
        <a href="{{ route('users.following', $user->username) }}" 
           class="hover:text-blue-600 transition-colors cursor-pointer">
            <span class="font-semibold">{{ number_format($followingCount) }}</span> Siguiendo
        </a>
    </p>
    <p><span class="font-semibold">{{ number_format($postsCount) }}</span> Publicaciones</p>
</div>