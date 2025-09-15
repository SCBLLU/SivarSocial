<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BannerNovedades extends Component
{
    public $banner = null;
    public $isVisible = false;

    public function mount()
    {
        Log::info('BannerNovedades: mount() called');
        $this->loadBanner();
    }

    public function loadBanner()
    {
        Log::info('BannerNovedades: loadBanner() called');

        if (!Auth::check()) {
            Log::info('BannerNovedades: User not authenticated');
            return;
        }

        // Buscar el banner activo que el usuario no haya visto
        $this->banner = Banner::active()
            ->whereDoesntHave('viewedByUsers', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        Log::info('BannerNovedades: Banner found', ['banner_id' => $this->banner ? $this->banner->id : 'none']);

        if ($this->banner) {
            $this->isVisible = true;
            Log::info('BannerNovedades: Setting isVisible to true');
        }
    }

    public function dismissBanner()
    {
        if ($this->banner && Auth::check()) {
            $this->banner->markAsViewedBy(Auth::id());
        }

        $this->closeBanner();
    }

    public function markAsUnderstood()
    {
        // Para banners tipo "update" - botón "Enterado"
        if ($this->banner && Auth::check()) {
            $this->banner->markAsViewedBy(Auth::id());
        }

        $this->closeBanner();
    }

    public function tryFeature()
    {
        // Para banners tipo "feature" - botón "Probar"
        if ($this->banner && Auth::check()) {
            $this->banner->markAsViewedBy(Auth::id());
        }

        $url = $this->banner->action_url ?? null;
        $this->closeBanner();

        // Redirigir a la URL de acción si existe
        if ($url) {
            return redirect($url);
        }
    }

    public function actionClick()
    {
        if ($this->banner) {
            // Marcar como visto antes de redirigir
            if (Auth::check()) {
                $this->banner->markAsViewedBy(Auth::id());
            }

            $url = $this->banner->action_url ?? '/';
            $this->closeBanner();

            return redirect($url);
        }
    }

    private function closeBanner()
    {
        $this->isVisible = false;
        $this->banner = null;
        
        // Restaurar scroll de manera simple y efectiva
        $this->js('
            if (window.restorePageScroll) {
                window.restorePageScroll();
            } else {
                // Fallback robusto
                document.body.style.overflow = "";
                document.body.style.position = "";
                document.body.style.top = "";
                document.body.style.left = "";
                document.body.style.width = "";
                document.documentElement.style.overflow = "";
                document.body.style.height = "";
                
                // Forzar restauración completa
                setTimeout(() => {
                    document.body.style.cssText = "";
                    document.documentElement.style.cssText = "";
                }, 50);
            }
        ');
    }

    public function render()
    {
        return view('livewire.banner-novedades');
    }
}
