<!-- Modal de Likes Livewire -->
<div>
    @if($showModal)
        <div class="fixed inset-0 flex items-end sm:items-center justify-center"
            style="background-color: rgba(0, 0, 0, 0.6); z-index: 1100;">
            <!-- Backdrop para cerrar modal -->
            <div class="absolute inset-0 cursor-pointer" wire:click="closeModal"></div>

            <!-- Contenedor del modal -->
            <div
                class="fixed bottom-0 left-0 right-0 bg-white text-black rounded-t-2xl shadow-lg z-50 flex flex-col max-h-[80vh] w-full mx-auto sm:relative sm:w-96 sm:h-96 sm:rounded-xl overflow-hidden">
                <!-- Drag handle mobile -->
                <div
                    class="p-4 border-b border-gray-200 text-center text-lg font-semibold cursor-grab touch-none sm:hidden">
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
                                            <p class="font-semibold text-sm text-gray-900">
                                                {{ $like['user']->name ?: $like['user']->username }}</p>
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