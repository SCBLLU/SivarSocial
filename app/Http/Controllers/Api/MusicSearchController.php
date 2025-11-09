<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

/**
 * Controlador de iTunes API para SivarSocial Mobile
 * Permite buscar canciones, álbumes y artistas en iTunes
 * Proporciona datos para crear posts de música
 */
class MusicSearchController extends Controller
{
    private const BASE_URL = 'https://itunes.apple.com/search';
    private const CACHE_TTL = 3600; // 1 hora

    /**
     * Buscar canciones en iTunes
     * Endpoint principal para búsqueda de música desde la app móvil
     */
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2|max:100',
                'limit' => 'nullable|integer|min:1|max:50',
                'country' => 'nullable|string|size:2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetros de búsqueda inválidos',
                    'errors' => $validator->errors()
                ], 400);
            }

            $query = $request->get('query');
            $limit = $request->get('limit', 20);
            $country = $request->get('country', 'US');

            if (empty(trim($query))) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $results = $this->searchTracks($query, $limit, $country);

            return response()->json([
                'success' => true,
                'data' => $results,
                'count' => count($results)
            ]);
        } catch (\Exception $e) {
            Log::error('Error en búsqueda de iTunes API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar música',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información específica de una canción por ID
     */
    public function getTrack(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'trackId' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID de track inválido',
                    'errors' => $validator->errors()
                ], 400);
            }

            $trackId = $request->get('trackId');
            $track = $this->getTrackById($trackId);

            if (!$track) {
                return response()->json([
                    'success' => false,
                    'message' => 'Canción no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $track
            ]);
        } catch (\Exception $e) {
            Log::error('Error obteniendo track de iTunes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener canción',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar por género musical
     */
    public function searchByGenre(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'genre' => 'required|string|min:2|max:50',
                'limit' => 'nullable|integer|min:1|max:30'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetros inválidos',
                    'errors' => $validator->errors()
                ], 400);
            }

            $genre = $request->get('genre');
            $limit = $request->get('limit', 10);

            $results = $this->searchTracks($genre, $limit);

            return response()->json([
                'success' => true,
                'data' => $results,
                'count' => count($results)
            ]);
        } catch (\Exception $e) {
            Log::error('Error en búsqueda por género: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar por género',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener canciones populares/tendencias
     */
    public function getPopular(Request $request)
    {
        try {
            $limit = $request->get('limit', 20);
            $country = $request->get('country', 'US');

            // Lista de términos populares para obtener canciones variadas
            $popularTerms = ['pop', 'rock', 'hip hop', 'reggaeton', 'electronic', 'latin'];
            $randomTerm = $popularTerms[array_rand($popularTerms)];

            $results = $this->searchTracks($randomTerm, $limit, $country);

            return response()->json([
                'success' => true,
                'data' => $results,
                'count' => count($results)
            ]);
        } catch (\Exception $e) {
            Log::error('Error obteniendo canciones populares: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener canciones populares',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar canciones en iTunes (método privado)
     */
    private function searchTracks(string $query, int $limit = 20, string $country = 'US'): array
    {
        try {
            if (empty(trim($query))) {
                return [];
            }

            // Crear clave de cache
            $cacheKey = "itunes_search_api_" . md5($query . $limit . $country);

            // Verificar cache
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $response = Http::timeout(10)->get(self::BASE_URL, [
                'term' => $query,
                'media' => 'music',
                'entity' => 'song',
                'limit' => $limit,
                'country' => $country,
                'explicit' => 'No' // Filtrar contenido explícito
            ]);

            if (!$response->successful()) {
                Log::error('Error en iTunes API: ' . $response->body());
                return [];
            }

            $data = $response->json();

            // Formatear datos para la app móvil
            $tracks = collect($data['results'] ?? [])->map(function ($track) {
                return [
                    'trackId' => $track['trackId'] ?? null,
                    'trackName' => $track['trackName'] ?? 'Canción desconocida',
                    'artistName' => $track['artistName'] ?? 'Artista desconocido',
                    'collectionName' => $track['collectionName'] ?? 'Álbum desconocido',
                    'artworkUrl100' => $track['artworkUrl100'] ?? null,
                    'artworkUrl60' => $track['artworkUrl60'] ?? null,
                    'previewUrl' => $track['previewUrl'] ?? null,
                    'trackViewUrl' => $track['trackViewUrl'] ?? null,
                    'trackTimeMillis' => $track['trackTimeMillis'] ?? 0,
                    'country' => $track['country'] ?? 'US',
                    'primaryGenreName' => $track['primaryGenreName'] ?? 'Música',
                    'releaseDate' => $track['releaseDate'] ?? null,

                    // Campos adicionales útiles
                    'artworkUrlHigh' => $this->getHighResArtwork($track['artworkUrl100'] ?? null),
                    'duration' => $this->formatDuration($track['trackTimeMillis'] ?? 0),
                    'hasPreview' => !empty($track['previewUrl']),

                    // URLs para compartir en plataformas
                    'appleMusicUrl' => $track['trackViewUrl'] ?? null,
                    'spotifySearchUrl' => $this->generateSpotifySearchUrl(
                        $track['artistName'] ?? '',
                        $track['trackName'] ?? ''
                    )
                ];
            })->filter(function ($track) {
                // Filtrar tracks que tengan al menos nombre y preview
                return !empty($track['trackName']) && !empty($track['previewUrl']);
            })->values()->toArray();

            // Guardar en cache
            Cache::put($cacheKey, $tracks, self::CACHE_TTL);

            return $tracks;
        } catch (\Exception $e) {
            Log::error('Error buscando tracks en iTunes: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener información de una canción por ID
     */
    private function getTrackById(int $trackId): ?array
    {
        try {
            $cacheKey = "itunes_track_api_" . $trackId;

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $response = Http::timeout(10)->get(self::BASE_URL, [
                'id' => $trackId,
                'entity' => 'song'
            ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();

            if (empty($data['results'])) {
                return null;
            }

            $track = $data['results'][0];

            $formattedTrack = [
                'trackId' => $track['trackId'] ?? null,
                'trackName' => $track['trackName'] ?? 'Canción desconocida',
                'artistName' => $track['artistName'] ?? 'Artista desconocido',
                'collectionName' => $track['collectionName'] ?? 'Álbum desconocido',
                'artworkUrl100' => $track['artworkUrl100'] ?? null,
                'artworkUrl60' => $track['artworkUrl60'] ?? null,
                'previewUrl' => $track['previewUrl'] ?? null,
                'trackViewUrl' => $track['trackViewUrl'] ?? null,
                'trackTimeMillis' => $track['trackTimeMillis'] ?? 0,
                'country' => $track['country'] ?? 'US',
                'primaryGenreName' => $track['primaryGenreName'] ?? 'Música',
                'releaseDate' => $track['releaseDate'] ?? null,
                'artworkUrlHigh' => $this->getHighResArtwork($track['artworkUrl100'] ?? null),
                'duration' => $this->formatDuration($track['trackTimeMillis'] ?? 0),
                'hasPreview' => !empty($track['previewUrl']),
            ];

            Cache::put($cacheKey, $formattedTrack, self::CACHE_TTL);

            return $formattedTrack;
        } catch (\Exception $e) {
            Log::error('Error obteniendo track por ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Genera URL de alta resolución del artwork
     */
    private function getHighResArtwork(?string $artworkUrl): ?string
    {
        if (!$artworkUrl) {
            return null;
        }

        // Reemplazar 100x100 por 600x600 para mejor calidad
        return str_replace('100x100', '600x600', $artworkUrl);
    }

    /**
     * Formatea la duración en milisegundos a formato legible
     */
    private function formatDuration(int $millis): string
    {
        $seconds = floor($millis / 1000);
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Genera URL de búsqueda en Spotify
     */
    private function generateSpotifySearchUrl(string $artist, string $track): string
    {
        $query = urlencode(trim($artist . ' ' . $track));
        return "https://open.spotify.com/search/{$query}";
    }
}
