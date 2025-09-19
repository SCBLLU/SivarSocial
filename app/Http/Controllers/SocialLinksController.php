<?php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialLinksController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:255',
        ], [
            'url.required' => 'La URL es obligatoria.',
            'url.url' => 'La URL debe ser válida.',
            'url.max' => 'La URL no puede tener más de 255 caracteres.',
        ]);

        try {
            // Verificar límite máximo
            $existingCount = Auth::user()->socialLinks()->count();
            if ($existingCount >= 4) {
                return response()->json([
                    'success' => false,
                    'error' => 'Máximo 4 enlaces sociales permitidos.'
                ], 422);
            }

            $url = $request->url;
            $platform = $this->detectPlatform($url);
            
            if (!$platform) {
                return response()->json([
                    'success' => false,
                    'error' => 'Plataforma no soportada. Use Instagram, TikTok, GitHub, YouTube, Spotify, Discord, LinkedIn, Twitter/X, Facebook o Telegram.'
                ], 422);
            }

            // Verificar si ya existe esta plataforma
            $existingLink = Auth::user()->socialLinks()->where('platform', $platform)->first();
            if ($existingLink) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ya tienes un enlace de ' . ucfirst($platform) . '.'
                ], 422);
            }

            // Obtener el siguiente número de orden
            $maxOrder = Auth::user()->socialLinks()->max('order') ?? 0;

            $socialLink = SocialLink::create([
                'user_id' => Auth::id(),
                'platform' => $platform,
                'url' => $url,
                'username' => $this->extractUsername($url, $platform),
                'order' => $maxOrder + 1
            ]);

            Log::info('Social link created', [
                'user_id' => Auth::id(),
                'platform' => $platform,
                'url' => $url
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enlace agregado correctamente.',
                'link' => [
                    'id' => $socialLink->id,
                    'platform' => $socialLink->platform,
                    'url' => $socialLink->url,
                    'icon' => $this->getPlatformIcon($socialLink->platform),
                    'color' => $this->getPlatformColor($socialLink->platform),
                    'order' => $socialLink->order
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating social link', [
                'user_id' => Auth::id(),
                'url' => $request->url,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al agregar el enlace. Inténtalo de nuevo.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $socialLink = Auth::user()->socialLinks()->findOrFail($id);
            $order = $socialLink->order;
            
            $socialLink->delete();

            // Reordenar los enlaces restantes
            Auth::user()->socialLinks()
                ->where('order', '>', $order)
                ->decrement('order');

            Log::info('Social link deleted', [
                'user_id' => Auth::id(),
                'link_id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enlace eliminado correctamente.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting social link', [
                'user_id' => Auth::id(),
                'link_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al eliminar el enlace.'
            ], 500);
        }
    }

    public function moveUp($id)
    {
        try {
            $socialLink = Auth::user()->socialLinks()->findOrFail($id);
            
            if ($socialLink->order <= 1) {
                return response()->json([
                    'success' => false,
                    'error' => 'El enlace ya está en la primera posición.'
                ]);
            }

            $previousLink = Auth::user()->socialLinks()
                ->where('order', $socialLink->order - 1)
                ->first();

            if ($previousLink) {
                $previousLink->update(['order' => $socialLink->order]);
                $socialLink->update(['order' => $socialLink->order - 1]);
            }

            Log::info('Social link moved up', [
                'user_id' => Auth::id(),
                'link_id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enlace movido hacia arriba.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error moving link up', [
                'user_id' => Auth::id(),
                'link_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al mover el enlace.'
            ], 500);
        }
    }

    public function moveDown($id)
    {
        try {
            $socialLink = Auth::user()->socialLinks()->findOrFail($id);
            $maxOrder = Auth::user()->socialLinks()->max('order');
            
            if ($socialLink->order >= $maxOrder) {
                return response()->json([
                    'success' => false,
                    'error' => 'El enlace ya está en la última posición.'
                ]);
            }

            $nextLink = Auth::user()->socialLinks()
                ->where('order', $socialLink->order + 1)
                ->first();

            if ($nextLink) {
                $nextLink->update(['order' => $socialLink->order]);
                $socialLink->update(['order' => $socialLink->order + 1]);
            }

            Log::info('Social link moved down', [
                'user_id' => Auth::id(),
                'link_id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enlace movido hacia abajo.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error moving link down', [
                'user_id' => Auth::id(),
                'link_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al mover el enlace.'
            ], 500);
        }
    }

    private function detectPlatform($url)
    {
        $platforms = [
            'instagram' => ['instagram.com', 'instagr.am'],
            'tiktok' => ['tiktok.com'],
            'github' => ['github.com'],
            'youtube' => ['youtube.com', 'youtu.be'],
            'spotify' => ['spotify.com', 'open.spotify.com'],
            'discord' => ['discord.gg', 'discord.com'],
            'linkedin' => ['linkedin.com'],
            'twitter' => ['twitter.com', 'x.com'],
            'facebook' => ['facebook.com', 'fb.com'],
            'telegram' => ['t.me', 'telegram.me']
        ];

        foreach ($platforms as $platform => $domains) {
            foreach ($domains as $domain) {
                if (strpos($url, $domain) !== false) {
                    return $platform;
                }
            }
        }

        return null;
    }

    private function extractUsername($url, $platform)
    {
        switch ($platform) {
            case 'instagram':
                if (preg_match('/instagram\.com\/([^\/\?]+)/', $url, $matches)) {
                    return $matches[1];
                }
                break;
            case 'tiktok':
                if (preg_match('/tiktok\.com\/@([^\/\?]+)/', $url, $matches)) {
                    return $matches[1];
                }
                break;
            case 'github':
                if (preg_match('/github\.com\/([^\/\?]+)/', $url, $matches)) {
                    return $matches[1];
                }
                break;
            case 'twitter':
                if (preg_match('/(?:twitter\.com|x\.com)\/([^\/\?]+)/', $url, $matches)) {
                    return $matches[1];
                }
                break;
        }
        
        return null;
    }

    private function getPlatformIcon($platform)
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

    private function getPlatformColor($platform)
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
}