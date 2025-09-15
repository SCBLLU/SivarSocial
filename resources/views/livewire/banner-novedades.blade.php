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

            <!-- Los usuarios DEBEN usar los botones contextuales para cerrar -->
            <!-- Se eliminó el botón X y la función de deslizar para forzar interacción específica -->
        </div>
    </div>

    @if($isVisible)
        <style>
            /* Prevenir scroll cuando el modal está abierto - Todas las plataformas */
            body, html {
                overflow: hidden !important;
                position: fixed !important;
                width: 100% !important;
                height: 100% !important;
            }
            
            /* Específico para móviles - Prevenir scroll touch */
            body {
                -webkit-overflow-scrolling: auto !important;
                overscroll-behavior: none !important;
                touch-action: none !important;
            }
            
            /* Asegurar que el contenido principal no se mueva */
            .content-wrapper {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                overflow: hidden !important;
            }
            
            /* Prevenir zoom en iOS cuando se toca */
            input, textarea, select {
                font-size: 16px !important;
                -webkit-user-select: none !important;
                -webkit-touch-callout: none !important;
            }
        </style>
        
        <script>
            // Prevenir scroll y gestos en móvil cuando el banner está visible
            document.addEventListener('DOMContentLoaded', function() {
                const bannerModal = document.getElementById('bannerModal');
                
                if (bannerModal) {
                    // Función para prevenir eventos de scroll/touch
                    function preventScroll(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                    
                    // Prevenir scroll con rueda del mouse
                    function preventWheel(e) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Prevenir teclas de navegación
                    function preventKeys(e) {
                        const keys = [32, 33, 34, 35, 36, 37, 38, 39, 40]; // space, page up/down, end, home, arrows
                        if (keys.includes(e.keyCode)) {
                            e.preventDefault();
                            return false;
                        }
                    }
                    
                    // Aplicar bloqueos
                    document.addEventListener('touchmove', preventScroll, { passive: false });
                    document.addEventListener('touchstart', function(e) {
                        // Solo permitir touch en el banner modal
                        if (!bannerModal.contains(e.target)) {
                            e.preventDefault();
                        }
                    }, { passive: false });
                    document.addEventListener('wheel', preventWheel, { passive: false });
                    document.addEventListener('keydown', preventKeys);
                    
                    // Limpiar eventos cuando el banner se oculte
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                                if (bannerModal.style.display === 'none' || !bannerModal.style.display) {
                                    // Banner oculto, remover bloqueos
                                    document.removeEventListener('touchmove', preventScroll);
                                    document.removeEventListener('wheel', preventWheel);
                                    document.removeEventListener('keydown', preventKeys);
                                    observer.disconnect();
                                }
                            }
                        });
                    });
                    
                    observer.observe(bannerModal, { attributes: true });
                }
            });
            
            // Escuchar evento de Livewire para restaurar scroll
            window.addEventListener('banner-closed', function() {
                // Restaurar scroll
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('position');
                document.body.style.removeProperty('width');
                document.body.style.removeProperty('height');
                document.body.style.removeProperty('-webkit-overflow-scrolling');
                document.body.style.removeProperty('overscroll-behavior');
                document.body.style.removeProperty('touch-action');
                
                document.documentElement.style.removeProperty('overflow');
                document.documentElement.style.removeProperty('position');
                document.documentElement.style.removeProperty('width');
                document.documentElement.style.removeProperty('height');
                
                // Restaurar content-wrapper si existe
                const contentWrapper = document.querySelector('.content-wrapper');
                if (contentWrapper) {
                    contentWrapper.style.removeProperty('position');
                    contentWrapper.style.removeProperty('top');
                    contentWrapper.style.removeProperty('left');
                    contentWrapper.style.removeProperty('right');
                    contentWrapper.style.removeProperty('bottom');
                    contentWrapper.style.removeProperty('overflow');
                }
            });
        </script>
    @endif
    @endif
</div>
