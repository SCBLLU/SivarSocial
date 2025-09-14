<div class="relative">
    <!-- Botón de notificaciones -->
    <button wire:click="openNotificationsModal"
        class="relative flex items-center justify-center w-8 h-8 focus:outline-none">
        <!-- Icono para móvil (Boxicons) -->
        <i class='text-2xl text-black transition bx bx-bell hover:text-purple-700' style="display: block;">
            <style>
                @media (min-width: 768px) {
                    .bx-bell {
                        display: none !important;
                    }
                }
            </style>
        </i>
        <!-- Icono para desktop (Font Awesome sólido) -->
        <i class="text-2xl text-black transition fas fa-bell hover:text-purple-700" style="display: none;">
            <style>
                @media (min-width: 768px) {
                    .fa-bell {
                        display: block !important;
                    }
                }
            </style>
        </i>

        <!-- Badge de contador -->
        @if ($unreadCount > 0)
            <span
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center min-w-[20px] font-medium">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>
</div>
