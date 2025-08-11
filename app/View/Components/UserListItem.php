<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\User;

class UserListItem extends Component
{
    public $user;
    public $showFollowButton;
    public $componentKey;

    /**
     * Create a new component instance.
     */
    public function __construct(User $user, $showFollowButton = true, $componentKey = null)
    {
        $this->user = $user;
        $this->showFollowButton = $showFollowButton;
        $this->componentKey = $componentKey ?: 'user-' . $user->id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-list-item');
    }
}
