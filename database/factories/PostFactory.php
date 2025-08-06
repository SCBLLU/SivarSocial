<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipo = fake()->randomElement(['imagen', 'musica']);
        $userIds = [1, 2, 3, 4, 5]; // IDs de usuarios específicos primero
        $allUserIds = range(1, 20); // Luego todos los usuarios
        
        $baseData = [
            'titulo' => $tipo === 'imagen' ? fake()->sentence(5) : null,
            'descripcion' => $tipo === 'imagen' ? fake()->sentence(20) : null,
            'imagen' => $tipo === 'imagen' ? fake()->uuid() . '.jpg' : null,
            'user_id' => fake()->randomElement($allUserIds),
            'tipo' => $tipo,
        ];

        // Si es un post de música, agregar datos de iTunes
        if ($tipo === 'musica') {
            $artists = ['Taylor Swift', 'Ed Sheeran', 'Adele', 'Drake', 'Billie Eilish', 'The Weeknd', 'Dua Lipa', 'Harry Styles'];
            $genres = ['Pop', 'Rock', 'Hip-Hop', 'Electronic', 'Jazz', 'Classical', 'R&B', 'Alternative'];
            
            $artist = fake()->randomElement($artists);
            $track = fake()->words(3, true);
            $album = fake()->words(2, true) . ' Album';
            
            $musicData = [
                'itunes_track_id' => fake()->numberBetween(100000000, 999999999),
                'itunes_track_name' => $track,
                'itunes_artist_name' => $artist,
                'itunes_collection_name' => $album,
                'itunes_artwork_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music124/v4/' . fake()->uuid() . '/100x100bb.jpg',
                'itunes_preview_url' => 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview124/v4/' . fake()->uuid() . '.m4a',
                'itunes_track_view_url' => 'https://music.apple.com/us/album/' . fake()->slug(3) . '/' . fake()->numberBetween(100000000, 999999999),
                'itunes_track_time_millis' => fake()->numberBetween(120000, 300000), // 2-5 minutos
                'itunes_country' => 'USA',
                'itunes_primary_genre_name' => fake()->randomElement($genres),
                'music_source' => 'itunes',
                'apple_music_url' => 'https://music.apple.com/us/album/' . fake()->slug(3) . '/' . fake()->numberBetween(100000000, 999999999),
                'spotify_web_url' => 'https://open.spotify.com/search/' . urlencode($artist . ' ' . $track),
                'artist_search_term' => $artist,
                'track_search_term' => $track,
            ];
            
            $baseData = array_merge($baseData, $musicData);
        }

        return $baseData;
    }
}
