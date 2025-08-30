<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class iTunesApiController extends Controller
{
    private const BASE_URL = 'https://itunes.apple.com/search';
    private const CACHE_TTL = 3600; // 1 hora

    /**
     * Buscar canciones en iTunes
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
                    'error' => 'Parámetros de búsqueda inválidos',
                    'details' => $validator->errors()
                ], 400);
            }

            $query = $request->get('query');
            $limit = $request->get('limit', 20);
            $country = $request->get('country', 'US');
            
            if (empty($query)) {
                return response()->json(['results' => []]);
            }

            $results = $this->searchTracks($query, $limit, $country);
            
            return response()->json($results);
            
        } catch (\Exception $e) {
            Log::error('Error en búsqueda de iTunes: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Obtener información específica de una canción
     */
    public function getTrack(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'trackId' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'ID de track inválido',
                    'details' => $validator->errors()
                ], 400);
            }

            $trackId = $request->get('trackId');
            $track = $this->getTrackById($trackId);
            
            if (!$track) {
                return response()->json(['error' => 'Track no encontrado'], 404);
            }
            
            return response()->json($track);
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo track de iTunes: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Buscar por género
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
                    'error' => 'Parámetros inválidos',
                    'details' => $validator->errors()
                ], 400);
            }

            $genre = $request->get('genre');
            $limit = $request->get('limit', 10);
            
            $results = $this->searchTracks("genre:$genre", $limit);
            
            return response()->json($results);
            
        } catch (\Exception $e) {
            Log::error('Error en búsqueda por género: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Obtener canciones populares
     */
    public function getPopular(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $country = $request->get('country', 'US');
            
            // Buscar canciones populares usando términos generales
            $popularTerms = ['pop', 'rock', 'hip hop', 'reggaeton', 'electronic'];
            $randomTerm = $popularTerms[array_rand($popularTerms)];
            
            $results = $this->searchTracks($randomTerm, $limit, $country);
            
            return response()->json($results);
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo canciones populares: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Buscar canciones en iTunes (método privado)
     */
    private function searchTracks(string $query, int $limit = 20, string $country = 'US'): array
    {
        try {
            if (empty(trim($query))) {
                return ['results' => []];
            }

            // Crear clave de cache
            $cacheKey = "itunes_search_" . md5($query . $limit . $country);
            
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
                return ['results' => []];
            }

            $data = $response->json();
            
            // Formatear datos para mantener consistencia
            $tracks = collect($data['results'])->map(function ($track) {
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
                ];
            })->filter(function ($track) {
                // Filtrar tracks que tengan al menos nombre y preview
                return !empty($track['trackName']) && !empty($track['previewUrl']);
            });

            $result = ['results' => $tracks->values()->toArray()];
            
            // Guardar en cache
            Cache::put($cacheKey, $result, self::CACHE_TTL);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Excepción en búsqueda de iTunes: ' . $e->getMessage());
            return ['results' => []];
        }
    }

    /**
     * Obtener información específica de una canción por ID
     */
    private function getTrackById(int $trackId): ?array
    {
        try {
            $cacheKey = "itunes_track_" . $trackId;
            
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
            $result = [
                'trackId' => $track['trackId'],
                'trackName' => $track['trackName'],
                'artistName' => $track['artistName'],
                'collectionName' => $track['collectionName'],
                'artworkUrl100' => $track['artworkUrl100'],
                'previewUrl' => $track['previewUrl'],
                'trackViewUrl' => $track['trackViewUrl'],
                'trackTimeMillis' => $track['trackTimeMillis'],
                'country' => $track['country'],
                'primaryGenreName' => $track['primaryGenreName'],
                'artworkUrlHigh' => $this->getHighResArtwork($track['artworkUrl100']),
                'duration' => $this->formatDuration($track['trackTimeMillis']),
            ];

            Cache::put($cacheKey, $result, self::CACHE_TTL);
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo track de iTunes: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Convertir artwork a alta resolución
     */
    private function getHighResArtwork(?string $artworkUrl): ?string
    {
        if (!$artworkUrl) {
            return null;
        }
        
        // Convertir de 100x100 a 600x600 para mejor calidad
        return str_replace('100x100', '600x600', $artworkUrl);
    }

    /**
     * Formatear duración de milisegundos a formato legible
     */
    private function formatDuration(int $millis): string
    {
        if ($millis <= 0) {
            return '0:00';
        }
        
        $seconds = intval($millis / 1000);
        $minutes = intval($seconds / 60);
        $seconds = $seconds % 60;
        
        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
