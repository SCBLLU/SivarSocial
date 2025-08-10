<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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
        ];
    }

    /**
     * Obtener la URL completa de la imagen de perfil
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset('perfiles/' . $this->imagen);
        }
        return asset('img/default-avatar.png'); // imagen por defecto
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
}