<div>
    @if($banner && $isVisible)
        <!-- Modal Banner de Novedades - Estilo Instagram/Spotify -->
        <div 
            id="bannerModal" 
            class="fixed inset-0 flex items-end justify-center md:items-center" 
            style="background-color: rgba(0, 0, 0, 0.75); z-index: 1200; overflow: hidden; overscroll-behavior: none;"
            wire:ignore.self
        >
        <!-- Contenedor del Modal -->
        <div 
            id="modalContainer"
            class="relative bg-white w-full md:mx-4 md:mb-0 rounded-t-2xl md:rounded-2xl shadow-2xl 
                   md:max-w-md lg:max-w-lg transition-transform duration-300 ease-out"
            style="height: auto; max-height: 85vh; overflow: hidden !important;"
        >
            <!-- Drag handle para móvil (ahora funcional) -->
            <div id="dragHandle" class="flex justify-center pt-4 pb-2 md:hidden cursor-grab active:cursor-grabbing">
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
            <div class="px-6 pb-6 text-center" style="overflow: hidden !important;">
                <!-- Título -->
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 leading-tight">
                    {{ $banner->title }}
                </h2>

                <!-- Descripción -->
                <div class="text-gray-600 text-sm md:text-base mb-6 leading-relaxed" style="overflow: hidden !important; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
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
                                wire:click="dismissBanner"
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
                                    wire:click="dismissBanner"
                                    class="w-full bg-blue-800 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-xl transition-colors duration-200"
                                >
                                    {{ $banner->action_text ?? 'Enterado' }}
                                </button>
                            @endif
                        </div>
                    @else
                    @endif
                </div>
            </div>

        </div>
    </div>

    @if($isVisible)
        <style>
            /* Bloquear scroll del body */
            body {
                overflow: hidden !important;
            }
            
            /* Solo en móviles, el JavaScript maneja el position fixed */
            @media (max-width: 768px) {
                html {
                    overflow: hidden !important;
                }
            }
            
            /* Estilos para el draggable */
            #modalContainer {
                will-change: transform;
                backface-visibility: hidden;
                overflow: hidden !important; /* Prevenir scroll del contenedor principal */
                overscroll-behavior: none !important;
            }
            
            /* Prevenir scroll en TODO el modal de manera agresiva */
            #modalContainer,
            #modalContainer *,
            #modalContainer div,
            #modalContainer p,
            #modalContainer span {
                overflow: hidden !important;
                overscroll-behavior: none !important;
                scroll-behavior: auto !important;
            }
            
            /* Forzar que nada tenga scroll */
            #modalContainer ::-webkit-scrollbar {
                display: none !important;
                width: 0 !important;
                height: 0 !important;
            }
            
            #dragHandle {
                user-select: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                touch-action: none;
                padding: 8px 16px;
            }
            
            #dragHandle:hover .w-12 {
                background-color: #9CA3AF;
                transform: scaleY(1.2);
                transition: all 0.2s ease;
            }
            
            #dragHandle:active .w-12 {
                background-color: #6B7280;
                transform: scaleY(1.4);
            }
            
            /* Animación de entrada del modal desde abajo */
            @keyframes slideUpFromBottom {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            
            #modalContainer {
                animation: slideUpFromBottom 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }
            
            /* En desktop, centrar el modal */
            @media (min-width: 768px) {
                #bannerModal {
                    align-items: center !important;
                }
                
                #modalContainer {
                    animation: slideUpFromBottom 0.3s ease-out;
                }
            }
            
            /* Mejorar la apariencia del handle en dispositivos móviles */
            @media (max-width: 768px) {
                #dragHandle .w-12 {
                    width: 3.5rem;
                    height: 0.25rem;
                    background-color: #D1D5DB;
                    border-radius: 9999px;
                    transition: all 0.2s ease;
                }
                
                /* Modal ocupa todo el ancho en móviles */
                #modalContainer {
                    margin: 0 !important;
                    border-radius: 1rem 1rem 0 0 !important;
                    max-height: 80vh !important;
                    height: auto !important;
                    overflow: hidden !important;
                }
                
                /* Asegurar que no hay scroll en móviles */
                #modalContainer *,
                #modalContainer div {
                    overflow: hidden !important;
                    overscroll-behavior: none !important;
                }
                
                /* Limitar texto aún más en móviles */
                #modalContainer .text-gray-600 {
                    -webkit-line-clamp: 2 !important;
                    max-height: 3em !important;
                }
            }
        </style>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Modal draggable que solo se puede cerrar por drag hacia abajo o botones
                const modalContainer = document.getElementById('modalContainer');
                const dragHandle = document.getElementById('dragHandle');
                const bannerModal = document.getElementById('bannerModal');
                
                if (!modalContainer || !dragHandle || !bannerModal) return;
                
                // Variables para manejar scroll
                let originalScrollY = 0;
                let scrollListeners = [];
                
                // Función para bloquear scroll
                function blockScroll() {
                    if (window.innerWidth <= 768) {
                        // Guardar posición actual
                        originalScrollY = window.scrollY || document.documentElement.scrollTop || 0;
                        
                        // Bloquear scroll en móviles
                        document.body.style.position = 'fixed';
                        document.body.style.top = `-${originalScrollY}px`;
                        document.body.style.width = '100%';
                        document.body.style.overflow = 'hidden';
                        document.documentElement.style.overflow = 'hidden';
                    } else {
                        // Solo overflow en desktop
                        document.body.style.overflow = 'hidden';
                    }
                }
                
                // Función para restaurar scroll
                function restoreScroll() {
                    if (window.innerWidth <= 768) {
                        // Restaurar todos los estilos móviles
                        document.body.style.position = '';
                        document.body.style.top = '';
                        document.body.style.width = '';
                        document.body.style.overflow = '';
                        document.documentElement.style.overflow = '';
                        
                        // Restaurar posición de scroll
                        window.scrollTo(0, originalScrollY);
                    } else {
                        // Restaurar desktop
                        document.body.style.overflow = '';
                    }
                }
                
                // Exponer función globalmente
                window.restorePageScroll = restoreScroll;
                
                // Forzar la eliminación completa del scroll del modal
                function forceNoScroll() {
                    modalContainer.style.overflow = 'hidden';
                    modalContainer.style.overflowX = 'hidden';
                    modalContainer.style.overflowY = 'hidden';
                    modalContainer.style.overscrollBehavior = 'none';
                    
                    // Aplicar a todos los elementos hijos también
                    const allElements = modalContainer.querySelectorAll('*');
                    allElements.forEach(element => {
                        element.style.overflow = 'hidden';
                        element.style.overflowX = 'hidden';
                        element.style.overflowY = 'hidden';
                        element.style.overscrollBehavior = 'none';
                    });
                }
                
                // Ejecutar bloqueo de scroll al cargar
                blockScroll();
                forceNoScroll();
                setTimeout(() => {
                    blockScroll();
                    forceNoScroll();
                }, 100);
                
                let isDragging = false;
                let startY = 0;
                let currentY = 0;
                let initialTransform = 0;
                
                // Función para cerrar el modal
                function closeModal() {
                    @this.dismissBanner();
                }
                
                // Obtener coordenada Y del touch/mouse
                function getClientY(e) {
                    return e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
                }
                
                // Iniciar drag - solo desde el handle
                function startDrag(e) {
                    // Solo permitir drag si se inicia desde el handle
                    if (!e.target.closest('#dragHandle')) return;
                    
                    isDragging = true;
                    startY = getClientY(e);
                    currentY = startY;
                    initialTransform = 0;
                    
                    modalContainer.style.transition = 'none';
                    dragHandle.style.cursor = 'grabbing';
                    
                    e.preventDefault();
                    e.stopPropagation();
                }
                
                // Durante el drag
                function duringDrag(e) {
                    if (!isDragging) return;
                    
                    currentY = getClientY(e);
                    const deltaY = Math.max(0, currentY - startY); // Solo permitir arrastrar hacia abajo
                    
                    // Aplicar transformación con resistencia
                    const resistance = deltaY > 50 ? 0.7 : 1;
                    const transform = deltaY * resistance;
                    
                    modalContainer.style.transform = `translateY(${transform}px)`;
                    
                    // Cambiar opacidad del backdrop basado en la distancia
                    const opacity = Math.max(0.3, 0.75 - (deltaY / 300));
                    bannerModal.style.backgroundColor = `rgba(0, 0, 0, ${opacity})`;
                    
                    e.preventDefault();
                }
                
                // Finalizar drag
                function endDrag(e) {
                    if (!isDragging) return;
                    
                    isDragging = false;
                    const deltaY = currentY - startY;
                    
                    modalContainer.style.transition = 'transform 0.3s ease-out';
                    dragHandle.style.cursor = 'grab';
                    
                    // Si se arrastró más de 100px hacia abajo, cerrar modal
                    if (deltaY > 100) {
                        modalContainer.style.transform = 'translateY(100vh)';
                        setTimeout(closeModal, 300);
                    } else {
                        // Volver a la posición original
                        modalContainer.style.transform = 'translateY(0)';
                        bannerModal.style.backgroundColor = 'rgba(0, 0, 0, 0.75)';
                    }
                }
                
                // Event listeners para touch (móvil) - solo en el handle
                dragHandle.addEventListener('touchstart', startDrag, { passive: false });
                document.addEventListener('touchmove', duringDrag, { passive: false });
                document.addEventListener('touchend', endDrag);
                
                // Event listeners para mouse (escritorio) - solo en el handle
                dragHandle.addEventListener('mousedown', startDrag);
                document.addEventListener('mousemove', duringDrag);
                document.addEventListener('mouseup', endDrag);
                
                // Prevenir que el modal se cierre al hacer clic dentro del contenedor
                modalContainer.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
                
                // Prevenir interference con botones y otros elementos interactivos
                const interactiveElements = modalContainer.querySelectorAll('button, a, input, select, textarea');
                interactiveElements.forEach(element => {
                    element.addEventListener('touchstart', function(e) {
                        e.stopPropagation();
                    });
                    element.addEventListener('mousedown', function(e) {
                        e.stopPropagation();
                    });
                });
            });
        </script>
    @endif
    @endif
</div>
