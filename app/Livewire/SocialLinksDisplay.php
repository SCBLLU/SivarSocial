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
        return match($platform) {
            'instagram' => '#E4405F',
            'tiktok' => '#FE2C55',
            'github' => '#333333',
            'youtube' => '#FF0000',
            'spotify' => '#1DB954',
            'discord' => '#7289DA',
            'linkedin' => '#0077B5',
            'twitter' => '#000000',
            'facebook' => '#1877F2',
            'telegram' => '#0088CC',
            default => '#6B7280'
        };
    }

    public function getPlatformIcon($platform)
    {
        return match($platform) {
            'instagram' => 'fab fa-instagram',
            'tiktok' => 'fab fa-tiktok',
            'github' => 'fab fa-github',
            'youtube' => 'fab fa-youtube',
            'spotify' => 'fab fa-spotify',
            'discord' => 'fab fa-discord',
            'linkedin' => 'fab fa-linkedin',
            'twitter' => 'fab fa-x-twitter',
            'facebook' => 'fab fa-facebook',
            'telegram' => 'fab fa-telegram',
            default => 'fas fa-link'
        };
    }

    public function render()
    {
        $socialLinks = $this->user->socialLinks()->ordered()->get();
        
        return view('livewire.social-links-display', [
            'socialLinks' => $socialLinks
        ]);
    }
}
