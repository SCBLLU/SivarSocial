<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpotifyApiController extends Controller
{
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

            // Obtener token de acceso
            $tokenResponse = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.spotify.client_id'),
                'client_secret' => config('services.spotify.client_secret'),
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('Error obteniendo token de Spotify: ' . $tokenResponse->body());
                return response()->json(['error' => 'Error de autenticación con Spotify'], 500);
            }

            $token = $tokenResponse->json()['access_token'];
            
            // Buscar canciones
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('https://api.spotify.com/v1/search', [
                'q' => $query,
                'type' => 'track',
                'limit' => 20,
                'market' => 'ES'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Formatear datos
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
            
            Log::error('Error en API de Spotify: ' . $response->body());
            return response()->json(['tracks' => ['items' => []]], 400);
            
        } catch (\Exception $e) {
            Log::error('Excepción en búsqueda de Spotify: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
