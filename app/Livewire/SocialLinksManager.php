<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SocialLinksManager extends Component
{
    public $url = '';
    public $showForm = false;

    protected $rules = [
        'url' => 'required|url|max:255',
    ];

    protected $messages = [
        'url.required' => 'La URL es obligatoria.',
        'url.url' => 'La URL debe ser válida.',
        'url.max' => 'La URL no puede tener más de 255 caracteres.',
    ];

    public function mount()
    {
        // Inicialización del componente
    }

    public function toggleForm()
    {
        Log::info('SocialLinksManager: toggleForm called', [
            'currentShowForm' => $this->showForm,
            'user_id' => Auth::id()
        ]);
        
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->resetForm();
        }
        
        Log::info('SocialLinksManager: toggleForm completed', [
            'newShowForm' => $this->showForm
        ]);
    }

    public function addLink()
    {
        $this->validate();

        // Verificar que el usuario no exceda el límite de enlaces (máximo 4)
        $currentLinksCount = Auth::user()->socialLinks()->count();
        if ($currentLinksCount >= 4) {
            session()->flash('error', 'Solo puedes tener un máximo de 4 enlaces sociales.');
            return;
        }

        // Detectar la plataforma automáticamente
        $platformData = SocialLink::detectPlatform($this->url);
        
        // Verificar que no exista ya un enlace de esta plataforma
        $existingLink = Auth::user()->socialLinks()
            ->where('platform', $platformData['platform'])
            ->first();

        if ($existingLink) {
            session()->flash('error', 'Ya tienes un enlace de ' . ucfirst($platformData['platform']) . '. Solo puedes tener uno por plataforma.');
            return;
        }

        // Extraer username
        $username = SocialLink::extractUsername($this->url, $platformData['platform']);

        // Obtener el siguiente número de orden
        $nextOrder = Auth::user()->socialLinks()->max('order') + 1;

        // Crear el enlace
        Auth::user()->socialLinks()->create([
            'platform' => $platformData['platform'],
            'url' => $this->url,
            'username' => $username,
            'icon' => $platformData['icon'],
            'order' => $nextOrder
        ]);

        session()->flash('success', 'Enlace agregado exitosamente.');
        $this->resetForm();
    }

    public function deleteLink($id)
    {
        $link = Auth::user()->socialLinks()->findOrFail($id);
        $link->delete();

        session()->flash('success', 'Enlace eliminado exitosamente.');
    }

    private function resetForm()
    {
        $this->url = '';
        $this->showForm = false;
        $this->resetErrorBag();
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
        $socialLinks = Auth::user()->socialLinks()->ordered()->get();
        
        return view('livewire.social-links-manager', [
            'socialLinks' => $socialLinks
        ]);
    }
}
