<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Banner extends Model
{
    use HasFactory;

    // Tipos de banner permitidos
    const TYPE_INFO = 'info';
    const TYPE_FEATURE = 'feature';
    const TYPE_UPDATE = 'update';

    protected $fillable = [
        'title',
        'content',
        'type',
        'image_url',
        'action_text',
        'action_url',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Usuarios que han visto este banner
     */
    public function viewedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'banner_user_views')
                    ->withPivot(['viewed_at'])
                    ->withTimestamps();
    }

    /**
     * Scope para banners activos y ordenados por fecha
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('start_date')
                           ->orWhere('start_date', '<=', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>=', now());
                     })
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Verifica si el usuario ha visto este banner
     */
    public function hasBeenViewedBy($userId): bool
    {
        return $this->viewedByUsers()->where('user_id', $userId)->exists();
    }

    /**
     * Marca el banner como visto por un usuario
     */
    public function markAsViewedBy($userId): void
    {
        if (!$this->hasBeenViewedBy($userId)) {
            $this->viewedByUsers()->attach($userId, ['viewed_at' => now()]);
        }
    }

    /**
     * Obtiene todos los tipos de banner disponibles
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_INFO,
            self::TYPE_FEATURE,
            self::TYPE_UPDATE,
        ];
    }

    /**
     * Verifica si el tipo de banner es válido
     */
    public static function isValidType(string $type): bool
    {
        return in_array($type, self::getAvailableTypes());
    }
}
