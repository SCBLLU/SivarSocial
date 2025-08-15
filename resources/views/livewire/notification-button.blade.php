<div class="relative">
    <!-- Botón de notificaciones -->
    <button wire:click="toggleDropdown"
        class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors duration-200 focus:outline-none">
        <i class="bx bx-bell text-xl"></i>

        <!-- Badge de contador -->
        @if($unreadCount > 0)
            <span
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center min-w-[20px] font-medium">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown de notificaciones -->
    @if($showDropdown)
        <!-- Overlay para cerrar al hacer clic fuera -->
        <div wire:click="closeDropdown" class="fixed inset-0 z-40" style="background: transparent;"></div>

        <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-96 overflow-hidden"
            style="animation: fadeIn 0.2s ease-out;">
            <!-- Header del modal -->
            <div class="p-4 border-b border-gray-100 bg-gray-50 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Notificaciones</h3>
                    <button wire:click="closeDropdown" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bx bx-x text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Contenido de notificaciones -->
            <div class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                @livewire('notifications-list')
            </div>
        </div>
    @endif

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</div>