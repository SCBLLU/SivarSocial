<div>
    @if($notifications->count() > 0)
        @foreach($notifications as $notification)
            <div class="p-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors duration-150 {{ !$notification->isRead() ? 'bg-blue-50' : '' }}">
                @if($notification->fromUser)
                    <div class="flex items-start space-x-3">
                        <!-- Avatar del usuario -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('posts.index', $notification->fromUser->username) }}">
                                <img 
                                    src="{{ $notification->fromUser->imagen_url }}" 
                                    alt="{{ $notification->fromUser->name }}"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200"
                                >
                            </a>
                        </div>

                        <!-- Contenido de la notificación -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Mensaje de la notificación -->
                                    <p class="text-sm text-gray-900">
                                        <a href="{{ route('posts.index', $notification->fromUser->username) }}" 
                                           class="font-semibold hover:text-blue-600 transition-colors">
                                            {{ $notification->fromUser->username }}
                                        </a>
                                        <span class="text-gray-600"> {{ $notification->getMessage() }}</span>
                                    </p>

                                    <!-- Tiempo -->
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $notification->time_ago }}
                                    </p>
                                </div>

                            <!-- Imagen del post para likes y comentarios -->
                            @if($notification->post && ($notification->type === 'like' || $notification->type === 'comment'))
                                <div class="flex-shrink-0 ml-3">
                                    <a href="{{ route('posts.show', ['user' => $notification->post->user->username, 'post' => $notification->post->id]) }}">
                                        @if($notification->post->imagen)
                                            <img 
                                                src="{{ asset('uploads/' . $notification->post->imagen) }}" 
                                                alt="Post"
                                                class="w-11 h-11 rounded-md object-cover border border-gray-200"
                                            >
                                        @else
                                            <div class="w-11 h-11 bg-gray-200 rounded-md flex items-center justify-center border border-gray-200">
                                                <i class="bx bx-image text-gray-400 text-lg"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            @endif

                            <!-- Indicador de no leída -->
                            @if(!$notification->isRead())
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 {{ $notification->post && ($notification->type === 'like' || $notification->type === 'comment') ? 'mr-3' : '' }}"></div>
                            @endif
                        </div>

                        <!-- Previsualización de comentario -->
                        @if($notification->type === 'comment' && isset($notification->data['comment_preview']))
                            <p class="text-xs text-gray-500 mt-1 truncate">
                                "{{ $notification->data['comment_preview'] }}"
                            </p>
                        @endif

                        <!-- Acciones según el tipo de notificación -->
                        <div class="mt-3 flex items-center space-x-3">
                            @if($notification->type === 'follow')
                                <!-- Botón de seguir/no seguir -->
                                <button 
                                    wire:click="followUser({{ $notification->fromUser->id }})"
                                    class="px-3 py-1 text-xs font-medium rounded-full transition-colors duration-200 
                                        {{ $this->isFollowingUser($notification->fromUser->id) ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-blue-600 text-white hover:bg-blue-700' }}"
                                >
                                    @if($this->isFollowingUser($notification->fromUser->id))
                                        Siguiendo
                                    @else
                                        Seguir
                                    @endif
                                </button>
                                
                                <!-- Ver perfil -->
                                <a 
                                    href="{{ route('posts.index', $notification->fromUser->username) }}"
                                    class="px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-800 border border-gray-300 rounded-full hover:bg-gray-50 transition-colors duration-200"
                                >
                                    Ver perfil
                                </a>

                            @elseif($notification->type === 'like' && $notification->post)
                                @if($notification->post->user)
                                    <!-- Ver post -->
                                    <a 
                                        href="{{ route('posts.show', ['user' => $notification->post->user->username, 'post' => $notification->post->id]) }}"
                                        class="px-3 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 border border-blue-300 rounded-full hover:bg-blue-50 transition-colors duration-200"
                                    >
                                        Ver publicación
                                    </a>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium text-gray-400 border border-gray-200 rounded-full">
                                        Autor no disponible
                                    </span>
                                @endif
                                
                                <!-- Ver perfil -->
                                @if($notification->fromUser)
                                    <a 
                                        href="{{ route('posts.index', $notification->fromUser->username) }}"
                                        class="px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-800 border border-gray-300 rounded-full hover:bg-gray-50 transition-colors duration-200"
                                    >
                                        Ver perfil
                                    </a>
                                @endif

                            @elseif($notification->type === 'comment' && $notification->post)
                                @if($notification->post->user)
                                    <!-- Ver comentarios -->
                                    <a 
                                        href="{{ route('posts.show', ['user' => $notification->post->user->username, 'post' => $notification->post->id]) }}#comments"
                                        class="px-3 py-1 text-xs font-medium text-green-600 hover:text-green-800 border border-green-300 rounded-full hover:bg-green-50 transition-colors duration-200"
                                    >
                                        Ver comentario
                                    </a>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium text-gray-400 border border-gray-200 rounded-full">
                                        Autor no disponible
                                    </span>
                                @endif
                                
                                <!-- Ver perfil -->
                                @if($notification->fromUser)
                                    <a 
                                        href="{{ route('posts.index', $notification->fromUser->username) }}"
                                        class="px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-800 border border-gray-300 rounded-full hover:bg-gray-50 transition-colors duration-200"
                                    >
                                        Ver perfil
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @else
                    <!-- Notificación sin usuario -->
                    <div class="p-4 text-center text-gray-500">
                        <i class="bx bx-user-x text-2xl mb-2"></i>
                        <p class="text-sm">Usuario no disponible</p>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <!-- Estado vacío -->
        <div class="p-8 text-center">
            <i class="bx bx-bell-off text-4xl text-gray-400 mb-2"></i>
            <p class="text-gray-500 text-sm">No tienes notificaciones</p>
            <p class="text-gray-400 text-xs mt-1">Cuando alguien interactúe contigo, las verás aquí</p>
        </div>
    @endif
</div>
