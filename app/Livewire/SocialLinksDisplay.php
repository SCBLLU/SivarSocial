<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class SocialLinksDisplay extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function getPlatformColor($platform)
    {
        $colors = [
            'instagram' => '#E4405F',
            'github' => '#333',
            'discord' => '#5865F2',
            'twitter' => '#1DA1F2',
            'linkedin' => '#0077B5',
            'youtube' => '#FF0000',
            'tiktok' => '#000000',
            'facebook' => '#1877F2',
            'spotify' => '#1DB954',
            'twitch' => '#9146FF',
            'other' => '#6B7280'
        ];

        return $colors[$platform] ?? $colors['other'];
    }

    public function render()
    {
        $socialLinks = $this->user->socialLinks()->ordered()->get();
        
        return view('livewire.social-links-display', [
            'socialLinks' => $socialLinks
        ]);
    }
}
