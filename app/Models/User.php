<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /**
     * Se utilizan los traits HasApiTokens, HasFactory y Notifiable para agregar funcionalidades al modelo User.
     * - HasApiTokens: Permite la gestión de tokens de autenticación para APIs, facilitando la implementación de autenticación basada en tokens.
     * - HasFactory: Habilita el uso de factories para la generación de instancias del modelo en pruebas y seeders.
     * - Notifiable: Permite que el modelo reciba notificaciones a través de diferentes canales (correo, base de datos, etc.).
     * Estos traits son esenciales para trabajar con APIs en Laravel, especialmente cuando se requiere autenticación y notificaciones.
     */
    use HasApiTokens, HasFactory, Notifiable;
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
     * The accessors to append to the model's array form.
     * Esto hace que imagen_url se incluya automáticamente en JSON
     *
     * @var array<string>
     */
    protected $appends = [
        'imagen_url',
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
     * Este accessor genera automáticamente la URL cuando accedes a $user->imagen_url
     */
    public function getImagenUrlAttribute()
    {
        // Si el usuario tiene una imagen de perfil, genero la URL completa
        if ($this->imagen) {
            return url('perfiles/' . $this->imagen);
        }
        // Si no tiene imagen, retorno null para que la app móvil use una imagen por defecto
        return null;
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
