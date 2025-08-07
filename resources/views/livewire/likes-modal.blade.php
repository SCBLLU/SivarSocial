<!-- Modal de Likes - Estilo Instagram -->
<div>
    <div x-data="{ show: @entangle('showModal') }" 
         x-show="show" 
         @keydown.escape.window="$wire.closeModal()"
         class="fixed inset-0 z-50 overflow-y-auto">
        
        <!-- Backdrop -->
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50" 
             @click="$wire.closeModal()"></div>

        <!-- Modal Container -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="relative z-10 flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
             
            <!-- Modal Content -->
            <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg sm:max-w-lg">
                
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        A quién le gusta
                    </h3>
                    <button @click="$wire.closeModal()" 
                            class="p-1 rounded-full hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Post Info -->
                @if($currentPost)
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <p class="text-sm text-gray-600 truncate">
                            "{{ $currentPost->contenido ?? $currentPost->titulo }}"
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $totalLikes }} {{ $totalLikes == 1 ? 'like' : 'likes' }}
                        </p>
                    </div>
                @endif

                <!-- Users List -->
                <div class="max-h-80 overflow-y-auto">
                    @if($likes && count($likes) > 0)
                        <div class="space-y-3">
                            @foreach($likes as $like)
                                @php $user = $like->user; @endphp
                                @if($user)
                                <div class="flex items-center space-x-3">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        @if($user->imagen)
                                            <img class="w-10 h-10 rounded-full object-cover" 
                                                 src="{{ asset('perfiles/' . $user->imagen) }}" 
                                                 alt="{{ $user->name }}">
                                        @else
                                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- User Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $user->name }}
                                        </p>
                                        @if($user->username)
                                            <p class="text-sm text-gray-500 truncate">
                                                @{{ $user->username }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <!-- Follow Button -->
                                    <div class="flex-shrink-0">
                                        <button class="px-4 py-1.5 text-sm font-semibold text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-200">
                                            Seguir
                                        </button>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Aún no hay likes en esta publicación</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
