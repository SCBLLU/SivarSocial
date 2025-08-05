<?php

namespace App\Services;

class CrossPlatformMusicService
{
    /**
     * Generar URL de búsqueda para Apple Music basada en artista y canción
     */
    public static function generateAppleMusicSearchUrl($artist, $track)
    {
        $query = urlencode(trim($artist . ' ' . $track));
        return "https://music.apple.com/search?term={$query}";
    }

    /**
     * Generar URL de búsqueda para Spotify basada en artista y canción
     */
    public static function generateSpotifySearchUrl($artist, $track)
    {
        $query = urlencode(trim($artist . ' ' . $track));
        return "https://open.spotify.com/search/{$query}";
    }

    /**
     * Generar URLs de búsqueda para ambas plataformas
     */
    public static function generateCrossPlatformUrls($artist, $track)
    {
        return [
            'apple_music' => self::generateAppleMusicSearchUrl($artist, $track),
            'spotify' => self::generateSpotifySearchUrl($artist, $track)
        ];
    }

    /**
     * Limpiar nombres de artista y canción para mejorar la búsqueda
     */
    public static function cleanSearchTerms($artist, $track)
    {
        // Remover caracteres especiales y contenido entre paréntesis que puede interferir
        $artist = preg_replace('/\s*\([^)]*\)/', '', $artist);
        $track = preg_replace('/\s*\([^)]*\)/', '', $track);
        
        // Remover "feat.", "ft.", etc.
        $artist = preg_replace('/\s*(feat\.?|ft\.?|featuring)\s+.*$/i', '', $artist);
        $track = preg_replace('/\s*(feat\.?|ft\.?|featuring)\s+.*$/i', '', $track);
        
        return [
            'artist' => trim($artist),
            'track' => trim($track)
        ];
    }
}
