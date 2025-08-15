<!-- Modal de Notificaciones Livewire -->
<div>
    @if($showModal)
        <div class="fixed inset-0 flex items-end sm:items-center justify-center transition-all duration-300 ease-out"
            style="background-color: rgba(0, 0, 0, 0.6); z-index: 9999;" x-data="{ show: false }"
            x-init="$nextTick(() => show = true)" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <!-- Backdrop para cerrar modal -->
            <div class="absolute inset-0 cursor-pointer" wire:click="closeModal"></div>

            <!-- Contenedor del modal -->
            <div class="fixed bottom-0 left-0 right-0 bg-white text-black rounded-t-2xl shadow-lg z-50 flex flex-col max-h-[80vh] w-full mx-auto sm:relative sm:w-96 sm:h-96 sm:rounded-xl overflow-hidden transform transition-all duration-300 ease-out"
                x-show="show" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
                x-transition:enter-end="translate-y-0 sm:translate-y-0 sm:scale-100 sm:opacity-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-y-0 sm:translate-y-0 sm:scale-100 sm:opacity-100"
                x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
                x-data="dragToCloseNotifications()" x-ref="modalContainer">

                <!-- Drag handle mobile -->
                <div class="p-4 border-b border-gray-200 text-center text-lg font-semibold cursor-grab touch-none sm:hidden"
                    x-ref="dragHandle" @touchstart="startDrag($event)" @touchmove="onDrag($event)" @touchend="endDrag()">
                    <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-2"></div>
                    <div class="flex items-center justify-between px-2">
                        <span class="text-base font-bold text-gray-900">Notificaciones</span>
                        <button wire:click="closeModal" class="p-1 hover:bg-gray-100 rounded-full transition-colors" @touchstart.stop @touchmove.stop @touchend.stop>
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Header desktop -->
                <div class="hidden sm:block flex-none border-b border-gray-200 bg-white sm:rounded-t-xl sticky top-0 z-10">
                    <div class="flex items-center justify-between px-4 py-3">
                        <h3 class="text-base font-semibold text-gray-900">Notificaciones</h3>
                        <button wire:click="closeModal" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Lista scrolleable -->
                <div class="p-4 space-y-3 overflow-y-auto flex-1 pb-0 bg-white" x-data="{ 
                                            scrollHandler() {
                                                if (this.$el.scrollTop + this.$el.clientHeight >= this.$el.scrollHeight - 100) {
                                                    @this.loadMore();
                                                }
                                            }
                                        }" x-on:scroll="scrollHandler()">

                    @if(count($notifications) > 0)
                        @foreach($notifications as $notification)
                            <div
                                class="py-3 border-b border-gray-100 last:border-b-0 relative {{ !$notification['isRead'] ? 'bg-blue-50 rounded-lg px-3' : '' }}">
                                @if($notification['fromUser'])
                                    <div class="flex items-start space-x-3">
                                        <!-- Avatar del usuario -->
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('posts.index', $notification['fromUser']->username) }}">
                                                <img src="{{ $notification['fromUser']->imagen_url }}"
                                                    alt="{{ $notification['fromUser']->name }}"
                                                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                            </a>
                                        </div>

                                        <!-- Contenido de la notificación -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 pr-3">
                                                    <!-- Mensaje de la notificación -->
                                                    <p class="text-sm text-gray-900">
                                                        <a href="{{ route('posts.index', $notification['fromUser']->username) }}"
                                                            class="font-semibold hover:text-blue-600 transition-colors">
                                                            {{ $notification['fromUser']->username }}
                                                        </a>
                                                        <span class="text-gray-600"> {{ $notification['message'] }}</span>
                                                    </p>

                                                    <!-- Tiempo -->
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $notification['time_ago'] }}
                                                    </p>
                                                </div>

                                                <!-- Imagen del post para likes y comentarios -->
                                                @if($notification['post'] && ($notification['type'] === 'like' || $notification['type'] === 'comment'))
                                                    <div class="flex-shrink-0">
                                                        @if($notification['post']->user)
                                                            <a href="{{ route('posts.show', ['user' => $notification['post']->user->username, 'post' => $notification['post']->id]) }}"
                                                                class="block hover:opacity-80 transition-opacity">
                                                                @if($notification['post']->tipo === 'musica' && $notification['post']->itunes_artwork_url)
                                                                    <!-- Portada de música -->
                                                                    <div
                                                                        class="relative w-12 h-12 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                                                        <img src="{{ str_replace(['100x100', '60x60', '30x30'], '300x300', $notification['post']->itunes_artwork_url) }}"
                                                                            alt="Portada del álbum" class="w-full h-full object-cover"
                                                                            onerror="this.parentElement.innerHTML='<div class=\'w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center\'><i class=\'bx bx-music text-white text-lg\'></i></div>'">
                                                                        <!-- Overlay de música -->
                                                                        <div
                                                                            class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                                            <i class="bx bx-play text-white text-sm drop-shadow"></i>
                                                                        </div>
                                                                    </div>
                                                                @elseif($notification['post']->imagen)
                                                                    <!-- Imagen de post normal -->
                                                                    <img src="{{ asset('uploads/' . $notification['post']->imagen) }}"
                                                                        alt="Publicación"
                                                                        class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm"
                                                                        onerror="this.parentElement.innerHTML='<div class=\'w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center border border-gray-200\'><i class=\'bx bx-image text-gray-400 text-lg\'></i></div>'">
                                                                @elseif($notification['post']->tipo === 'musica')
                                                                    <!-- Placeholder para música sin portada -->
                                                                    <div
                                                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center border border-gray-200 shadow-sm">
                                                                        <i class="bx bx-music text-white text-lg"></i>
                                                                    </div>
                                                                @else
                                                                    <!-- Placeholder para imagen -->
                                                                    <div
                                                                        class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center border border-gray-200 shadow-sm">
                                                                        <i class="bx bx-image text-gray-400 text-lg"></i>
                                                                    </div>
                                                                @endif
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif

                                                <!-- Indicador de no leída -->
                                                @if(!$notification['isRead'])
                                                    <div class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full"></div>
                                                @endif
                                            </div>

                                            <!-- Acciones según el tipo de notificación -->
                                            <div class="mt-3 flex items-center space-x-2">
                                                @if($notification['type'] === 'follow')
                                                    <!-- Botón de seguir/no seguir -->
                                                    <button wire:click="followUser({{ $notification['fromUser']->id }})"
                                                        class="px-3 py-1 text-xs font-medium rounded-full transition-all duration-200
                                                                                                                                                                {{ $this->isFollowingUser($notification['fromUser']->id) ? 'bg-white border border-black text-black hover:bg-gray-50' : 'bg-[#3B25DD] border border-black text-white hover:bg-[#120073]' }}">
                                                        {{ $this->isFollowingUser($notification['fromUser']->id) ? 'NO SEGUIR' : 'SEGUIR' }}
                                                    </button>

                                                @elseif($notification['type'] === 'like' && $notification['post'])
                                                    @if($notification['post']->user)
                                                        <!-- Ver post -->
                                                        <a href="{{ route('posts.show', ['user' => $notification['post']->user->username, 'post' => $notification['post']->id]) }}"
                                                            class="px-3 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 border border-blue-300 rounded-full hover:bg-blue-50 transition-colors duration-200">
                                                            Ver publicación
                                                        </a>
                                                    @endif

                                                @elseif($notification['type'] === 'comment' && $notification['post'])
                                                    @if($notification['post']->user)
                                                        <!-- Ver comentarios -->
                                                        <a href="{{ route('posts.show', ['user' => $notification['post']->user->username, 'post' => $notification['post']->id]) }}#comments"
                                                            class="px-3 py-1 text-xs font-medium text-green-600 hover:text-green-800 border border-green-300 rounded-full hover:bg-green-50 transition-colors duration-200">
                                                            Ver comentario
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

                        <!-- Indicador de carga para más contenido -->
                        @if($hasMore)
                            <!-- Loadmore sin indicator visual -->
                        @endif
                    @else
                        <!-- Estado vacío -->
                        <div class="py-8 text-center">
                            <i class="bx bx-bell-off text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500 text-sm">No tienes notificaciones</p>
                            <p class="text-gray-400 text-xs mt-1">Cuando alguien interactúe contigo, las verás aquí</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function dragToCloseNotifications() {
        return {
            isDragging: false,
            startY: 0,
            currentY: 0,
            threshold: 100,

            startDrag(event) {
                // Solo en móvil
                if (window.innerWidth >= 640) return;

                this.isDragging = true;
                this.startY = event.touches[0].clientY;
                this.currentY = this.startY;
            },

            onDrag(event) {
                if (!this.isDragging || window.innerWidth >= 640) return;

                event.preventDefault();
                this.currentY = event.touches[0].clientY;

                const deltaY = this.currentY - this.startY;

                if (deltaY > 0) {
                    const modal = this.$refs.modalContainer;
                    modal.style.transform = `translateY(${deltaY}px)`;
                    modal.style.transition = 'none';
                }
            },

            endDrag() {
                if (!this.isDragging || window.innerWidth >= 640) return;

                this.isDragging = false;
                const deltaY = this.currentY - this.startY;
                const modal = this.$el;

                modal.style.transition = 'transform 0.3s ease-out';

                if (deltaY > this.threshold) {
                    // Cerrar modal
                    modal.style.transform = 'translateY(100%)';
                    setTimeout(() => {
                        @this.closeModal();
                    }, 300);
                } else {
                    // Volver a posición original
                    modal.style.transform = 'translateY(0)';
                }
            }
        }
    }
</script>