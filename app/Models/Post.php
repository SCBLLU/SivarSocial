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
        // Campos iTunes (para búsquedas principales)
        'itunes_track_id',
        'itunes_track_name',
        'itunes_artist_name',
        'itunes_collection_name',
        'itunes_artwork_url',
        'itunes_preview_url',
        'itunes_track_view_url',
        'itunes_track_time_millis',
        'itunes_country',
        'itunes_primary_genre_name',
        'music_source',
        // Campos para enlaces cruzados entre plataformas (solo enlaces, no datos de búsqueda)
        'apple_music_url',
        'spotify_web_url',
        'artist_search_term',
        'track_search_term'
    ];

    public function user()
    {
        // Debe incluir también el campo 'imagen' para mostrar la foto de perfil
        return $this->belongsTo(User::class)->select(['id', 'name', 'username', 'imagen', 'insignia']); //te odio pinche models
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

    /**
     * Obtener el nombre de la canción (solo iTunes)
     */
    public function getTrackName()
    {
        return $this->itunes_track_name;
    }

    /**
     * Obtener el nombre del artista (solo iTunes)
     */
    public function getArtistName()
    {
        return $this->itunes_artist_name;
    }

    /**
     * Obtener el nombre del álbum (solo iTunes)
     */
    public function getAlbumName()
    {
        return $this->itunes_collection_name;
    }

    /**
     * Obtener la imagen del álbum (solo iTunes)
     */
    public function getArtworkUrl()
    {
        return $this->itunes_artwork_url;
    }

    /**
     * Obtener el preview URL (solo iTunes)
     */
    public function getPreviewUrl()
    {
        return $this->itunes_preview_url;
    }

    /**
     * Obtener el URL externo (solo Apple Music)
     */
    public function getExternalUrl()
    {
        return $this->itunes_track_view_url;
    }

    /**
     * Obtener la duración formateada (solo iTunes)
     */
    public function getFormattedDuration()
    {
        if ($this->itunes_track_time_millis) {
            $seconds = intval($this->itunes_track_time_millis / 1000);
            $minutes = intval($seconds / 60);
            $seconds = $seconds % 60;
            return sprintf('%d:%02d', $minutes, $seconds);
        }
        return null;
    }

    /**
     * Verificar si tiene preview disponible
     */
    public function hasPreview()
    {
        return !empty($this->getPreviewUrl());
    }

    /**
     * Obtener el género musical (solo iTunes)
     */
    public function getGenre()
    {
        return $this->itunes_primary_genre_name;
    }

    /**
     * Obtener URL de Apple Music (prioriza el campo específico, luego el campo original de iTunes)
     */
    public function getAppleMusicUrl()
    {
        return $this->apple_music_url ?: $this->itunes_track_view_url;
    }

    /**
     * Obtener URL de Spotify (solo campo de enlace cruzado)
     */
    public function getSpotifyUrl()
    {
        return $this->spotify_web_url;
    }

    /**
     * Verificar si tiene enlace a Apple Music disponible
     */
    public function hasAppleMusicLink()
    {
        return !empty($this->getAppleMusicUrl());
    }

    /**
     * Verificar si tiene enlace a Spotify disponible  
     */
    public function hasSpotifyLink()
    {
        return !empty($this->getSpotifyUrl());
    }

    /**
     * Obtener términos de búsqueda para plataformas cruzadas
     */
    public function getSearchTerms()
    {
        return [
            'artist' => $this->artist_search_term ?: $this->getArtistName(),
            'track' => $this->track_search_term ?: $this->getTrackName()
        ];
    }
}
