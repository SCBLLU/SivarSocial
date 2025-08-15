<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class NotificationButton extends Component
{
    public $unreadCount = 0;

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

    public function openNotificationsModal()
    {
        $this->dispatch('open-notifications-modal');
    }

    #[On('notifications-opened')]
    public function onNotificationsOpened()
    {
        $this->updateUnreadCount();
    }

    #[On('follower-updated')]
    #[On('notification-created')]
    #[On('notifications-read')]
    #[On('refreshNotifications')]
    public function onUpdateTrigger()
    {
        $this->updateUnreadCount();
    }

    public function render()
    {
        return view('livewire.notification-button');
    }
}
