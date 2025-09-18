<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /**
     * Mutator para asegurar que el campo imagen siempre tenga un valor válido.
     */
    public function setImagenAttribute($value)
    {
        // Si no se proporciona imagen, usa la imagen por defecto
        $this->attributes['imagen'] = $value ?: 'img.jpg';
    }
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'imagen',      // Asegúrate de que este campo esté aquí
        'gender',
        'profession',
        'insignia',
        'last_activity',
        'is_online',
        'last_seen',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity' => 'datetime',
            'last_seen' => 'datetime',
            'is_online' => 'boolean',
        ];
    }

    /**
     * Obtener la URL completa de la imagen de perfil
     */
    public function getImagenUrlAttribute()
    {
        // Siempre retorna la imagen real o la por defecto
        return asset('perfiles/' . ($this->imagen ?: 'img.jpg'));
    }

    public function getRouteKeyName()
    {
        return 'username'; // indica a laravel usar este campo para el binding
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    //metodo que almacena los seguidores de un usuario
    public function followers()
    {
        // Un usuario tiene muchos seguidores (quiénes lo siguen)
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // Un usuario sigue a muchos usuarios
    public function following()
    {
        // Usuarios a los que este usuario sigue
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function isFollowing(User $user)
    {
        // Verifica si el usuario autenticado sigue a $user
        return $this->following->contains($user->id);
    }

    // Relación con notificaciones
    public function notifications()
    {
        return $this->hasMany(Notification::class)->recent();
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->unread()->recent();
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    // Relación con enlaces sociales
    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class)->ordered();
    }

    /**
     * Métodos para manejar estado activo global
     */
    public function updateActivity()
    {
        $this->forceFill([
            'last_activity' => now(),
            'is_online' => true,
        ])->save();

        return $this;
    }

    public function setOffline()
    {
        $this->forceFill([
            'is_online' => false,
            'last_seen' => now(),
        ])->save();

        return $this;
    }

    public function isOnline()
    {
        // Un usuario está online si:
        // 1. is_online es true Y
        // 2. su última actividad fue hace menos de 5 minutos
        return $this->is_online &&
            $this->last_activity &&
            $this->last_activity->greaterThan(now()->subMinutes(5));
    }

    public function getLastSeenAttribute($value)
    {
        if (!$value) return null;

        $lastSeen = \Carbon\Carbon::parse($value);
        return $lastSeen->diffForHumans();
    }

    // Scope para obtener solo usuarios online
    public function scopeOnline($query)
    {
        return $query->where('is_online', true)
            ->where('last_activity', '>', now()->subMinutes(5));
    }
}
