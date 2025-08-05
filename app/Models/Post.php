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
        // Campos Spotify (mantener para compatibilidad)
        'spotify_track_id',
        'spotify_track_name',
        'spotify_artist_name',
        'spotify_album_name',
        'spotify_album_image',
        'spotify_preview_url',
        'spotify_external_url',
        // Campos iTunes (nuevos)
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
        // Campos para enlaces cruzados entre plataformas
        'apple_music_url',
        'spotify_web_url',
        'artist_search_term',
        'track_search_term'
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

    /**
     * Obtener el nombre de la canción (iTunes o Spotify)
     */
    public function getTrackName()
    {
        return $this->music_source === 'itunes' 
            ? $this->itunes_track_name 
            : $this->spotify_track_name;
    }

    /**
     * Obtener el nombre del artista (iTunes o Spotify)
     */
    public function getArtistName()
    {
        return $this->music_source === 'itunes' 
            ? $this->itunes_artist_name 
            : $this->spotify_artist_name;
    }

    /**
     * Obtener el nombre del álbum (iTunes o Spotify)
     */
    public function getAlbumName()
    {
        return $this->music_source === 'itunes' 
            ? $this->itunes_collection_name 
            : $this->spotify_album_name;
    }

    /**
     * Obtener la imagen del álbum (iTunes o Spotify)
     */
    public function getArtworkUrl()
    {
        return $this->music_source === 'itunes' 
            ? $this->itunes_artwork_url 
            : $this->spotify_album_image;
    }

    /**
     * Obtener el preview URL (iTunes o Spotify)
     */
    public function getPreviewUrl()
    {
        return $this->music_source === 'itunes' 
            ? $this->itunes_preview_url 
            : $this->spotify_preview_url;
    }

    /**
     * Obtener el URL externo (Apple Music o Spotify)
     */
    public function getExternalUrl()
    {
        return $this->music_source === 'itunes' 
            ? $this->itunes_track_view_url 
            : $this->spotify_external_url;
    }

    /**
     * Obtener la duración formateada
     */
    public function getFormattedDuration()
    {
        if ($this->music_source === 'itunes' && $this->itunes_track_time_millis) {
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
     * Obtener el género musical
     */
    public function getGenre()
    {
        return $this->music_source === 'itunes' 
            ? $this->itunes_primary_genre_name 
            : null;
    }

    /**
     * Obtener URL de Apple Music (prioriza el campo específico, luego el campo original de iTunes)
     */
    public function getAppleMusicUrl()
    {
        return $this->apple_music_url ?: $this->itunes_track_view_url;
    }

    /**
     * Obtener URL de Spotify (prioriza el campo específico, luego el campo original de Spotify)
     */
    public function getSpotifyUrl()
    {
        return $this->spotify_web_url ?: $this->spotify_external_url;
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
