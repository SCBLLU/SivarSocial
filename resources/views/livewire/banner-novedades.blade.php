<div>
    @if($banner && $isVisible)
        <!-- Modal Banner de Novedades - Estilo Instagram/Spotify -->
        <div 
            id="bannerModal" 
            class="fixed inset-0 items-center justify-center flex" 
            style="background-color: rgba(0, 0, 0, 0.75); z-index: 1200;"
            wire:ignore.self
        >
        <!-- Contenedor del Modal -->
        <div 
            class="relative bg-white mx-4 rounded-2xl shadow-2xl max-w-sm w-full max-h-[80vh] overflow-hidden
                   md:max-w-md lg:max-w-lg"
        >
            <!-- Drag handle para móvil (solo visual, no funcional) -->
            <div class="flex justify-center pt-4 pb-2 md:hidden">
                <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
            </div>

            <!-- Imagen/Icono del banner -->
            <div class="flex justify-center py-4">
                @if($banner->image_url)
                    <img src="{{ $banner->image_url }}" alt="Banner" class="w-20 h-20 object-cover">
                @else
                    <div class="w-20 h-20 flex items-center justify-center">
                        @switch($banner->type)
                           @case('feature')
                              <i class="fas fa-star text-purple-600 text-4xl"></i>
                              @break
                           @case('update')
                              <i class="fas fa-sync-alt text-blue-600 text-4xl"></i>
                              @break
                           @case('info')
                           @default
                              <i class="fas fa-info-circle text-gray-600 text-4xl"></i>
                        @endswitch
                    </div>
                @endif
            </div>

            <!-- Contenido del modal -->
            <div class="px-6 pb-6 text-center">
                <!-- Título -->
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 leading-tight">
                    {{ $banner->title }}
                </h2>

                <!-- Descripción -->
                <div class="text-gray-600 text-sm md:text-base mb-6 leading-relaxed">
                    {!! $banner->content !!}
                </div>

                <!-- Botones de acción según el tipo -->
                <div class="space-y-3">
                    @if($banner->type === 'feature')
                        <!-- Botones para funciones nuevas -->
                        <div class="space-y-3">
                            @if($banner->action_url)
                                <button 
                                    wire:click="tryFeature"
                                    class="w-full bg-blue-800 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-xl transition-colors duration-200"
                                >
                                    Probar {{ $banner->action_text ?? 'Nueva Función' }}
                                </button>
                            @endif
                            <button 
                                wire:click="dismissBanner"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-xl transition-colors duration-200"
                            >
                                Tal vez más tarde
                            </button>
                        </div>
                    @elseif($banner->type === 'update')
                        <!-- Botones para actualizaciones -->
                        <div class="space-y-3">
                            <button 
                                wire:click="markAsUnderstood"
                                class="w-full bg-blue-800 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-xl transition-colors duration-200"
                            >
                                Enterado
                            </button>
                            @if($banner->action_url)
                                <button 
                                    wire:click="actionClick"
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-xl transition-colors duration-200"
                                >
                                    {{ $banner->action_text ?? 'Ver detalles' }}
                                </button>
                            @endif
                        </div>
                    @elseif($banner->type === 'info')
                        <!-- Botones para información -->
                        <div class="space-y-3">
                            @if($banner->action_url)
                                <button 
                                    wire:click="actionClick"
                                    class="w-full bg-blue-800 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-xl transition-colors duration-200"
                                >
                                    {{ $banner->action_text ?? 'Enterado' }}
                                </button>
                            @endif
                        </div>
                    @else
                        <!-- Botones genéricos (fallback) -->
                        <div class="space-y-3">
                            @if($banner->action_url)
                                <button 
                                    wire:click="actionClick"
                                    class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105"
                                >
                                    {{ $banner->action_text ?? 'Continuar' }}
                                </button>
                            @endif
                            <button 
                                wire:click="dismissBanner"
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-xl transition-colors duration-200"
                            >
                                Cerrar
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @if($isVisible)
        <style>
            /* Solo bloquear scroll del body, nada más */
            body {
                overflow: hidden;
            }
        </style>
    @endif
    @endif
</div>
