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

    /**
     * Extraer color dominante de una imagen de álbum
     */
    public function extractDominantColor(Request $request)
    {
        try {
            $imageUrl = $request->get('image_url');
            
            if (empty($imageUrl)) {
                return response()->json(['dominant_color' => '#1DB954']);
            }

            // Intentar descargar la imagen y extraer color dominante
            $imageData = Http::get($imageUrl);
            
            if (!$imageData->successful()) {
                return response()->json(['dominant_color' => '#1DB954']);
            }

            // Crear imagen temporal
            $tempFile = tempnam(sys_get_temp_dir(), 'spotify_cover');
            file_put_contents($tempFile, $imageData->body());

            // Extraer color dominante usando GD
            $dominantColor = $this->getDominantColor($tempFile);
            
            // Limpiar archivo temporal
            unlink($tempFile);

            return response()->json(['dominant_color' => $dominantColor]);
            
        } catch (\Exception $e) {
            Log::error('Error al extraer color dominante: ' . $e->getMessage());
            return response()->json(['dominant_color' => '#1DB954']);
        }
    }

    /**
     * Calcular color dominante de una imagen
     */
    private function getDominantColor($imagePath)
    {
        try {
            // Obtener información de la imagen
            $imageInfo = getimagesize($imagePath);
            if (!$imageInfo) {
                return '#1DB954';
            }

            // Crear recurso de imagen según el tipo
            switch ($imageInfo[2]) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($imagePath);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($imagePath);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($imagePath);
                    break;
                default:
                    return '#1DB954';
            }

            if (!$image) {
                return '#1DB954';
            }

            // Redimensionar imagen para hacer el cálculo más rápido
            $width = imagesx($image);
            $height = imagesy($image);
            $resized = imagecreatetruecolor(50, 50);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, 50, 50, $width, $height);

            // Contar colores
            $colors = [];
            for ($x = 0; $x < 50; $x++) {
                for ($y = 0; $y < 50; $y++) {
                    $rgb = imagecolorat($resized, $x, $y);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    
                    // Agrupar colores similares
                    $r = round($r / 32) * 32;
                    $g = round($g / 32) * 32;
                    $b = round($b / 32) * 32;
                    
                    $colorKey = sprintf('#%02x%02x%02x', $r, $g, $b);
                    $colors[$colorKey] = ($colors[$colorKey] ?? 0) + 1;
                }
            }

            // Limpiar recursos
            imagedestroy($image);
            imagedestroy($resized);

            // Encontrar el color más común (excluyendo blancos y grises)
            arsort($colors);
            foreach ($colors as $color => $count) {
                $r = hexdec(substr($color, 1, 2));
                $g = hexdec(substr($color, 3, 2));
                $b = hexdec(substr($color, 5, 2));
                
                // Evitar colores muy claros o grises
                if ($r + $g + $b < 600 && abs($r - $g) + abs($g - $b) + abs($r - $b) > 30) {
                    return $color;
                }
            }

            return '#1DB954'; // Verde de Spotify por defecto
            
        } catch (\Exception $e) {
            Log::error('Error calculando color dominante: ' . $e->getMessage());
            return '#1DB954';
        }
    }
}
