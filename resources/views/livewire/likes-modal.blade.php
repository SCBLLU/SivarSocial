<!-- Modal de Likes Livewire -->
<div>
    @if($showModal)
        <div class="fixed inset-0 flex items-end sm:items-center justify-center transition-all duration-300 ease-out"
            style="background-color: rgba(0, 0, 0, 0.6); z-index: 1100;" x-data="{ show: false }"
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
                x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0" x-data="dragToClose()"
                x-on:touchstart="startDrag($event)" x-on:touchmove="onDrag($event)" x-on:touchend="endDrag($event)">
                <!-- Drag handle mobile -->
                <div class="p-4 border-b border-gray-200 text-center text-lg font-semibold cursor-grab touch-none sm:hidden"
                    x-ref="dragHandle">
                    <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-2"></div>
                    <div class="flex items-center justify-between px-2">
                        <span class="text-base font-bold text-gray-900">Me gusta</span>
                        <button wire:click="closeModal" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
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
                        <h3 class="text-base font-semibold text-gray-900">Me gusta</h3>
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

                    @if(count($likes) > 0)
                        @foreach($likes as $like)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('posts.index', $like['user']->username) }}">
                                        <img src="{{ $like['user']->imagen ? asset('perfiles/' . $like['user']->imagen) : asset('img/img.jpg') }}"
                                            alt="{{ $like['user']->username }}" class="w-12 h-12 rounded-full object-cover"
                                            onerror="this.src='{{ asset('img/img.jpg') }}'">
                                    </a>
                                    <div>
                                        <a href="{{ route('posts.index', $like['user']->username) }}" class="block">
                                            <div class="font-semibold text-sm text-gray-900">
                                                <div class="flex items-center gap-1 min-h-5">
                                                    <span>{{ $like['user']->name ?: $like['user']->username }}</span>
                                                    <x-user-badge :badge="$like['user']->insignia" size="small" />
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $like['user']->username }}</p>
                                        </a>
                                    </div>
                                </div>

                                @auth
                                    @if(auth()->id() !== $like['user']->id)
                                        <button wire:click="toggleFollow({{ $like['user']->id }})"
                                            class="px-4 py-1.5 text-sm font-medium rounded-full transition-all duration-200
                                            {{ $like['isFollowing'] ? 'bg-white border border-black text-black hover:bg-gray-50' : 'bg-[#3B25DD] border border-black text-white hover:bg-[#120073]' }}">
                                            {{ $like['isFollowing'] ? 'NO SEGUIR' : 'SEGUIR' }}
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        @endforeach

                        <!-- Indicador de carga para más contenido -->
                        @if($hasMore)
                            <!-- Loadmore sin indicator visual -->
                        @endif
                    @else
                        <!-- Estado vacío -->
                        <div class="py-8 text-center">
                            <svg class="mx-auto w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <p class="text-gray-500 text-sm">Aún no hay me gusta en esta publicación</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function dragToClose() {
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
                    const modal = this.$el;
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