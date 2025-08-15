<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationButton extends Component
{
    public $unreadCount = 0;
    public $showDropdown = false;

    protected $listeners = [
        'notification-created' => 'updateUnreadCount',
        'notifications-read' => 'updateUnreadCount',
        'refreshNotifications' => 'updateUnreadCount',
    ];

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function updateUnreadCount()
    {
        if (Auth::check()) {
            $this->unreadCount = Auth::user()->unreadNotifications()->count();
        }
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;

        if ($this->showDropdown) {
            $this->dispatch('open-notifications-dropdown');
            // Marcar como leídas cuando se abre
            $this->markNotificationsAsRead();
        }
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    private function markNotificationsAsRead()
    {
        if (Auth::check()) {
            // Marcar todas las notificaciones como leídas
            Auth::user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

            // Actualizar el contador
            $this->updateUnreadCount();
        }
    }

    public function render()
    {
        return view('livewire.notification-button');
    }
}
