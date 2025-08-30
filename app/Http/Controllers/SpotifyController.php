<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpotifyController extends Controller
{
    private $accessToken = null;

    /**
     * Obtener token de acceso de Spotify
     */
    private function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.spotify.client_id'),
            'client_secret' => config('services.spotify.client_secret'),
        ]);

        if ($response->successful()) {
            $this->accessToken = $response->json()['access_token'];
            return $this->accessToken;
        }

        throw new \Exception('No se pudo obtener el token de Spotify');
    }

    /**
     * Buscar canciones en Spotify
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
            
            if (empty($query)) {
                return response()->json(['tracks' => ['items' => []]]);
            }

            $token = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('https://api.spotify.com/v1/search', [
                'q' => $query,
                'type' => 'track',
                'limit' => 20,
                'market' => 'ES' // Mercado español
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Formatear datos para el frontend
                $tracks = collect($data['tracks']['items'])->map(function ($track) {
                    return [
                        'id' => $track['id'],
                        'name' => $track['name'],
                        'artist' => $track['artists'][0]['name'] ?? 'Artista desconocido',
                        'album' => $track['album']['name'] ?? 'Álbum desconocido',
                        'image' => $track['album']['images'][0]['url'] ?? null,
                        'preview_url' => $track['preview_url'],
                        'external_url' => $track['external_urls']['spotify'] ?? null,
                        'duration_ms' => $track['duration_ms'],
                        'popularity' => $track['popularity']
                    ];
                });

                return response()->json([
                    'tracks' => [
                        'items' => $tracks
                    ]
                ]);
            }
            
            return response()->json(['tracks' => ['items' => []]], 400);
            
        } catch (\Exception $e) {
            Log::error('Error en búsqueda de Spotify: ' . $e->getMessage());
            return response()->json(['error' => 'Error al buscar en Spotify'], 500);
        }
    }

    /**
     * Obtener información de una canción específica
     */
    public function getTrack(Request $request)
    {
        try {
            $trackId = $request->get('id');
            
            if (empty($trackId)) {
                return response()->json(['error' => 'ID de track requerido'], 400);
            }

            $token = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get("https://api.spotify.com/v1/tracks/{$trackId}");

            if ($response->successful()) {
                $track = $response->json();
                
                return response()->json([
                    'id' => $track['id'],
                    'name' => $track['name'],
                    'artist' => $track['artists'][0]['name'] ?? 'Artista desconocido',
                    'album' => $track['album']['name'] ?? 'Álbum desconocido',
                    'image' => $track['album']['images'][0]['url'] ?? null,
                    'preview_url' => $track['preview_url'],
                    'external_url' => $track['external_urls']['spotify'] ?? null,
                    'duration_ms' => $track['duration_ms'],
                    'popularity' => $track['popularity']
                ]);
            }
            
            return response()->json(['error' => 'Track no encontrado'], 404);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener track de Spotify: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener información del track'], 500);
        }
    }
}
