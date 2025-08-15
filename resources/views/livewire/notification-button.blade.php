<div class="relative">
    <!-- Botón de notificaciones -->
    <button wire:click="openNotificationsModal"
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
</div>