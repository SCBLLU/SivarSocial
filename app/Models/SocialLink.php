<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialLink extends Model
{
    protected $fillable = [
        'user_id',
        'platform',
        'url',
        'username',
        'icon',
        'order'
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para ordenar por el campo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Detecta automáticamente la plataforma basado en la URL
     */
    public static function detectPlatform($url): array
    {
        $platforms = [
            'instagram' => [
                'patterns' => ['instagram.com', 'instagr.am'],
                'icon' => 'fab fa-instagram',
                'color' => '#E4405F'
            ],
            'github' => [
                'patterns' => ['github.com'],
                'icon' => 'fab fa-github',
                'color' => '#333'
            ],
            'discord' => [
                'patterns' => ['discord.gg', 'discord.com'],
                'icon' => 'fab fa-discord',
                'color' => '#5865F2'
            ],
            'twitter' => [
                'patterns' => ['twitter.com', 'x.com'],
                'icon' => 'fab fa-twitter',
                'color' => '#1DA1F2'
            ],
            'linkedin' => [
                'patterns' => ['linkedin.com'],
                'icon' => 'fab fa-linkedin',
                'color' => '#0077B5'
            ],
            'youtube' => [
                'patterns' => ['youtube.com', 'youtu.be'],
                'icon' => 'fab fa-youtube',
                'color' => '#FF0000'
            ],
            'tiktok' => [
                'patterns' => ['tiktok.com'],
                'icon' => 'fab fa-tiktok',
                'color' => '#000000'
            ],
            'facebook' => [
                'patterns' => ['facebook.com', 'fb.com'],
                'icon' => 'fab fa-facebook',
                'color' => '#1877F2'
            ],
            'spotify' => [
                'patterns' => ['spotify.com'],
                'icon' => 'fab fa-spotify',
                'color' => '#1DB954'
            ],
            'twitch' => [
                'patterns' => ['twitch.tv'],
                'icon' => 'fab fa-twitch',
                'color' => '#9146FF'
            ]
        ];

        foreach ($platforms as $platform => $config) {
            foreach ($config['patterns'] as $pattern) {
                if (strpos(strtolower($url), $pattern) !== false) {
                    return [
                        'platform' => $platform,
                        'icon' => $config['icon'],
                        'color' => $config['color']
                    ];
                }
            }
        }

        // Plataforma no reconocida
        return [
            'platform' => 'other',
            'icon' => 'fas fa-link',
            'color' => '#6B7280'
        ];
    }

    /**
     * Extrae el username de la URL
     */
    public static function extractUsername($url, $platform): ?string
    {
        $patterns = [
            'instagram' => '/instagram\.com\/([^\/\?]+)/',
            'github' => '/github\.com\/([^\/\?]+)/',
            'twitter' => '/(?:twitter\.com|x\.com)\/([^\/\?]+)/',
            'linkedin' => '/linkedin\.com\/in\/([^\/\?]+)/',
            'youtube' => '/youtube\.com\/(?:c\/|channel\/|user\/)?([^\/\?]+)/',
            'tiktok' => '/tiktok\.com\/@([^\/\?]+)/',
            'facebook' => '/facebook\.com\/([^\/\?]+)/',
            'spotify' => '/spotify\.com\/user\/([^\/\?]+)/',
            'twitch' => '/twitch\.tv\/([^\/\?]+)/'
        ];

        if (isset($patterns[$platform])) {
            if (preg_match($patterns[$platform], $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Obtiene el color de la plataforma
     */
    public function getPlatformColor(): string
    {
        return match($this->platform) {
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
            'twitch' => '#9146FF',
            default => '#6B7280'
        };
    }

    /**
     * Obtiene el icono de la plataforma
     */
    public function getPlatformIcon(): string
    {
        return match($this->platform) {
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
            'twitch' => 'fab fa-twitch',
            default => 'fas fa-link'
        };
    }
}
