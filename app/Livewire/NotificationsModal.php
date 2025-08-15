<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use App\Models\Follower;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class NotificationsModal extends Component
{
    public $showModal = false;
    public $notifications;
    public $hasMore = false;
    public $perPage = 15;
    public $page = 1;

    public function mount()
    {
        $this->loadNotifications();
    }

    #[On('open-notifications-modal')]
    public function openModal()
    {
        $this->showModal = true;
        $this->markNotificationsAsRead();
        $this->dispatch('notifications-opened');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function loadNotifications()
    {
        $query = Notification::with(['fromUser', 'post.user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        $total = $query->count();
        $notifications = $query->limit($this->perPage * $this->page)->get();

        $this->notifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $notification->data,
                'fromUser' => $notification->fromUser,
                'post' => $notification->post,
                'time_ago' => $notification->time_ago,
                'message' => $notification->getMessage(),
                'isRead' => $notification->isRead(),
                'created_at' => $notification->created_at,
            ];
        });

        $this->hasMore = $total > ($this->perPage * $this->page);
    }

    public function loadMore()
    {
        if ($this->hasMore) {
            $this->page++;
            $this->loadNotifications();
        }
    }

    public function markNotificationsAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function followUser($userId)
    {
        if (Auth::id() === $userId) {
            return;
        }

        $isFollowing = Follower::where('follower_id', Auth::id())
            ->where('user_id', $userId)
            ->exists();

        if ($isFollowing) {
            Follower::where('follower_id', Auth::id())
                ->where('user_id', $userId)
                ->delete();
        } else {
            Follower::create([
                'follower_id' => Auth::id(),
                'user_id' => $userId,
            ]);
        }

        $this->dispatch('follower-updated', $userId);
        $this->loadNotifications();
    }

    public function isFollowingUser($userId)
    {
        return Follower::where('follower_id', Auth::id())
            ->where('user_id', $userId)
            ->exists();
    }

    public function render()
    {
        return view('livewire.notifications-modal');
    }
}
