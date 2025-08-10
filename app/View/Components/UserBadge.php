<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserBadge extends Component
{
    public $badge;
    public $size;
    public $showHover;

    /**
     * Create a new component instance.
     */
    public function __construct($badge = null, $size = 'small', $showHover = true)
    {
        $this->badge = $badge;
        $this->size = $size;
        $this->showHover = $showHover;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('perfil.user-badge');
    }
}
