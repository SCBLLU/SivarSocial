<?php

namespace App\Models;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'user_id',
        'tipo',
        'spotify_track_id',
        'spotify_track_name',
        'spotify_artist_name',
        'spotify_album_name',
        'spotify_album_image',
        'spotify_preview_url',
        'spotify_external_url'
    ];

    public function user()
    {
        // Debe incluir también el campo 'imagen' para mostrar la foto de perfil
        return $this->belongsTo(User::class)->select(['id', 'name', 'username', 'imagen']);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function checkLike(User $user)
    {
        return $this->likes->contains('user_id', $user->id);
    }

    public function isMusicPost()
    {
        return $this->tipo === 'musica';
    }

    public function isImagePost()
    {
        return $this->tipo === 'imagen';
    }
}
