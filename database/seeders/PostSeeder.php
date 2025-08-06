<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📝 Creando posts específicos con imágenes tech...');

        // Posts de imagen con temática tecnológica y colores negros
        $imagePosts = [
            // Posts de Desarrollo
            [
                'titulo' => 'Mi nuevo proyecto de desarrollo',
                'descripcion' => 'Compartiendo el progreso de mi aplicación web desarrollada con Laravel y Vue.js. Ha sido un desafío increíble pero muy gratificante.',
                'imagen' => 'proyecto-desarrollo.jpg',
                'tipo' => 'imagen',
                'user_id' => 3, // Carlos
            ],
            [
                'titulo' => 'Setup de programación actualizado',
                'descripcion' => 'Después de mucho ahorro, finalmente tengo el setup de mis sueños. ¡Productividad al máximo!',
                'imagen' => 'setup-developer.jpg',
                'tipo' => 'imagen',
                'user_id' => 3, // Carlos
            ],
            [
                'titulo' => 'Terminal commands mastery',
                'descripcion' => 'Dominando la línea de comandos. El verdadero poder está en el terminal.',
                'imagen' => 'terminal-commands.jpg',
                'tipo' => 'imagen',
                'user_id' => 5, // Luis (DevOps)
            ],
            [
                'titulo' => 'Código limpio en acción',
                'descripcion' => 'Trabajando en optimización de algoritmos. El código limpio no es solo estético, es funcional.',
                'imagen' => 'codigo-programacion.jpg',
                'tipo' => 'imagen',
                'user_id' => 3, // Carlos
            ],

            // Posts de Diseño UI/UX
            [
                'titulo' => 'UI Design para app móvil',
                'descripcion' => 'Diseño de interfaz para una nueva aplicación. La experiencia del usuario siempre es lo primero.',
                'imagen' => 'ui-design.jpg',
                'tipo' => 'imagen',
                'user_id' => 2, // María
            ],
            [
                'titulo' => 'App mockup en progreso',
                'descripcion' => 'Wireframes y prototipos para la próxima gran app. El diseño es donde todo comienza.',
                'imagen' => 'app-mockup.jpg',
                'tipo' => 'imagen',
                'user_id' => 2, // María
            ],
            [
                'titulo' => 'Wireframe sketching session',
                'descripcion' => 'Bosquejos iniciales para un proyecto ambicioso. Sometimes the best ideas start on paper.',
                'imagen' => 'wireframe-sketch.jpg',
                'tipo' => 'imagen',
                'user_id' => 2, // María
            ],

            // Posts de Inteligencia Artificial
            [
                'titulo' => 'AI Research breakthrough',
                'descripcion' => 'Últimos avances en mi investigación de machine learning. La IA está transformando todo.',
                'imagen' => 'ai-research.jpg',
                'tipo' => 'imagen',
                'user_id' => 4, // Ana
            ],
            [
                'titulo' => 'Machine Learning en producción',
                'descripcion' => 'Implementando modelos de ML en producción. De los notebooks a la realidad.',
                'imagen' => 'machine-learning.jpg',
                'tipo' => 'imagen',
                'user_id' => 4, // Ana
            ],
            [
                'titulo' => 'Neural Network architecture',
                'descripcion' => 'Diseñando la arquitectura de red neuronal para el próximo proyecto. Deep learning en su máxima expresión.',
                'imagen' => 'neural-network.jpg',
                'tipo' => 'imagen',
                'user_id' => 4, // Ana
            ],

            // Posts de DevOps y Cloud
            [
                'titulo' => 'DevOps pipeline automation',
                'descripcion' => 'CI/CD pipeline completamente automatizado. Deploy continuo sin estrés.',
                'imagen' => 'devops-pipeline.jpg',
                'tipo' => 'imagen',
                'user_id' => 5, // Luis
            ],
            [
                'titulo' => 'Cloud architecture design',
                'descripcion' => 'Arquitectura escalable en la nube. AWS, Azure y GCP working in harmony.',
                'imagen' => 'cloud-architecture.jpg',
                'tipo' => 'imagen',
                'user_id' => 10, // Elena
            ],
            [
                'titulo' => 'Docker containers en acción',
                'descripcion' => 'Containerización de aplicaciones. Docker makes everything portable.',
                'imagen' => 'docker-containers.jpg',
                'tipo' => 'imagen',
                'user_id' => 5, // Luis
            ],

            // Posts de Ciberseguridad
            [
                'titulo' => 'Security audit completado',
                'descripcion' => 'Auditoría de seguridad exhaustiva. Protegiendo la infraestructura digital.',
                'imagen' => 'security-audit.jpg',
                'tipo' => 'imagen',
                'user_id' => 6, // Sofía
            ],
            [
                'titulo' => 'Pentesting tools arsenal',
                'descripcion' => 'Las mejores herramientas para ethical hacking. Testing security boundaries.',
                'imagen' => 'pentesting-tools.jpg',
                'tipo' => 'imagen',
                'user_id' => 6, // Sofía
            ],
            [
                'titulo' => 'Encryption keys management',
                'descripcion' => 'Gestión segura de claves de cifrado. La criptografía es la base de la seguridad moderna.',
                'imagen' => 'encryption-keys.jpg',
                'tipo' => 'imagen',
                'user_id' => 6, // Sofía
            ],

            // Posts de Base de datos
            [
                'titulo' => 'Database design optimization',
                'descripcion' => 'Optimización de la estructura de base de datos. Performance matters.',
                'imagen' => 'database-design.jpg',
                'tipo' => 'imagen',
                'user_id' => 8, // Laura
            ],
            [
                'titulo' => 'SQL queries masterclass',
                'descripcion' => 'Queries complejas que funcionan como arte. SQL is poetry in motion.',
                'imagen' => 'sql-queries.jpg',
                'tipo' => 'imagen',
                'user_id' => 8, // Laura
            ],
            [
                'titulo' => 'NoSQL MongoDB setup',
                'descripcion' => 'Configuración de MongoDB para big data. NoSQL for modern applications.',
                'imagen' => 'nosql-mongodb.jpg',
                'tipo' => 'imagen',
                'user_id' => 8, // Laura
            ],
        ];

        // Crear posts de imagen
        foreach ($imagePosts as $postData) {
            $post = Post::create($postData);
            $this->command->info("📷 Post de imagen: {$post->titulo}");
        }

        $this->command->info('🎵 Creando posts de música con canciones reales de iTunes...');

        // Posts de música con canciones reales de iTunes
        $musicPosts = [
            [
                'titulo' => null,
                'descripcion' => 'Perfect coding soundtrack! This track keeps me in the zone 🎧💻',
                'tipo' => 'musica',
                'user_id' => 3, // Carlos (developer)
                'music_source' => 'itunes',
                'itunes_track_id' => '1440742593',
                'itunes_track_name' => 'Blinding Lights',
                'itunes_artist_name' => 'The Weeknd',
                'itunes_collection_name' => 'After Hours',
                'itunes_artwork_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music114/v4/3b/87/c4/3b87c4b6-8b5d-4ad5-8ec4-7e65b59a2a54/19UM1IM16997.rgb.jpg/100x100bb.jpg',
                'itunes_preview_url' => 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview125/v4/24/52/93/245293d4-f525-4995-5b42-c9b52030b817/mzaf_11803286827690303566.plus.aac.p.m4a',
                'itunes_track_view_url' => 'https://music.apple.com/us/album/blinding-lights/1440742590?i=1440742593',
                'itunes_track_time_millis' => 200040,
                'itunes_country' => 'USA',
                'itunes_primary_genre_name' => 'Pop',
            ],
            [
                'titulo' => null,
                'descripcion' => 'When you need that extra motivation for debugging 💻🔥',
                'tipo' => 'musica',
                'user_id' => 5, // Luis (DevOps)
                'music_source' => 'itunes',
                'itunes_track_id' => '1574406284',
                'itunes_track_name' => 'Shivers',
                'itunes_artist_name' => 'Ed Sheeran',
                'itunes_collection_name' => '= (Equals)',
                'itunes_artwork_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music126/v4/75/eb/ca/75ebca31-c9b9-7d9b-8ce6-f63b0ac87e15/190296490347.jpg/100x100bb.jpg',
                'itunes_preview_url' => 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview116/v4/6b/5c/3a/6b5c3a93-0826-4ad8-5448-2da8c9b0a95e/mzaf_12244923738701899056.plus.aac.p.m4a',
                'itunes_track_view_url' => 'https://music.apple.com/us/album/shivers/1574406283?i=1574406284',
                'itunes_track_time_millis' => 207534,
                'itunes_country' => 'USA',
                'itunes_primary_genre_name' => 'Pop',
            ],
            [
                'titulo' => null,
                'descripcion' => 'AI research sessions powered by this masterpiece 🤖✨',
                'tipo' => 'musica',
                'user_id' => 4, // Ana (ML Engineer)
                'music_source' => 'itunes',
                'itunes_track_id' => '1581892922',
                'itunes_track_name' => 'Bad Habits',
                'itunes_artist_name' => 'Ed Sheeran',
                'itunes_collection_name' => 'Bad Habits',
                'itunes_artwork_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music125/v4/87/59/8f/87598f7e-5c87-8ae6-ae31-b1b45ab15c96/190296618086.jpg/100x100bb.jpg',
                'itunes_preview_url' => 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview125/v4/42/4e/c6/424ec64d-b827-1c92-c6b7-84c1c71ca3e4/mzaf_12051095996765503378.plus.aac.p.m4a',
                'itunes_track_view_url' => 'https://music.apple.com/us/album/bad-habits/1581892921?i=1581892922',
                'itunes_track_time_millis' => 231067,
                'itunes_country' => 'USA',
                'itunes_primary_genre_name' => 'Pop',
            ],
            [
                'titulo' => null,
                'descripcion' => 'Cloud deployment goes smoother with this rhythm ☁️🎵',
                'tipo' => 'musica',
                'user_id' => 10, // Elena (Cloud Architect)
                'music_source' => 'itunes',
                'itunes_track_id' => '1454068063',
                'itunes_track_name' => 'Sunflower (Spider-Man: Into the Spider-Verse)',
                'itunes_artist_name' => 'Post Malone & Swae Lee',
                'itunes_collection_name' => 'Spider-Man: Into the Spider-Verse (Soundtrack From & Inspired by the Motion Picture)',
                'itunes_artwork_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music128/v4/05/8e/72/058e72c0-1297-6cfd-5fdd-d5891c6e7ea1/886447720608.jpg/100x100bb.jpg',
                'itunes_preview_url' => 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview125/v4/d4/f3/f5/d4f3f569-c4fe-a2a9-7b3e-10c2d6fa2645/mzaf_10031780069760478984.plus.aac.p.m4a',
                'itunes_track_view_url' => 'https://music.apple.com/us/album/sunflower-spider-man-into-the-spider-verse/1454068058?i=1454068063',
                'itunes_track_time_millis' => 158040,
                'itunes_country' => 'USA',
                'itunes_primary_genre_name' => 'Hip-Hop/Rap',
            ],
            [
                'titulo' => null,
                'descripcion' => 'Security audits feel less stressful with Billie 🔒💜',
                'tipo' => 'musica',
                'user_id' => 6, // Sofía (Cybersecurity)
                'music_source' => 'itunes',
                'itunes_track_id' => '1450695739',
                'itunes_track_name' => 'bad guy',
                'itunes_artist_name' => 'Billie Eilish',
                'itunes_collection_name' => 'WHEN WE ALL FALL ASLEEP, WHERE DO WE GO?',
                'itunes_artwork_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music114/v4/eb/ab/59/ebab5986-8f2d-c44f-4efe-7e0d33c5b687/19UMGIM06998.rgb.jpg/100x100bb.jpg',
                'itunes_preview_url' => 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview125/v4/7b/7e/53/7b7e533a-3984-a1b1-d1aa-cd8374a6e4c9/mzaf_9292706977663848562.plus.aac.p.m4a',
                'itunes_track_view_url' => 'https://music.apple.com/us/album/bad-guy/1450695723?i=1450695739',
                'itunes_track_time_millis' => 194088,
                'itunes_country' => 'USA',
                'itunes_primary_genre_name' => 'Alternative',
            ],
            [
                'titulo' => null,
                'descripcion' => 'Mobile development vibes with this classic 📱🎶',
                'tipo' => 'musica',
                'user_id' => 7, // Miguel (Mobile Dev)
                'music_source' => 'itunes',
                'itunes_track_id' => '1530806655',
                'itunes_track_name' => 'Watermelon Sugar',
                'itunes_artist_name' => 'Harry Styles',
                'itunes_collection_name' => 'Fine Line',
                'itunes_artwork_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music124/v4/84/14/6a/84146a4d-1540-2071-08f1-f47e02abe2e3/886448240273.jpg/100x100bb.jpg',
                'itunes_preview_url' => 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview125/v4/51/51/90/515190b9-4433-d8bb-9c46-ee1434b29af6/mzaf_1999230829166439316.plus.aac.p.m4a',
                'itunes_track_view_url' => 'https://music.apple.com/us/album/watermelon-sugar/1530806650?i=1530806655',
                'itunes_track_time_millis' => 174000,
                'itunes_country' => 'USA',
                'itunes_primary_genre_name' => 'Pop',
            ],
            [
                'titulo' => null,
                'descripcion' => 'Data analysis flows better with Dua Lipa 📊💃',
                'tipo' => 'musica',
                'user_id' => 8, // Laura (Data Scientist)
                'music_source' => 'itunes',
                'itunes_track_id' => '1510071688',
                'itunes_track_name' => 'Don\'t Start Now',
                'itunes_artist_name' => 'Dua Lipa',
                'itunes_collection_name' => 'Future Nostalgia',
                'itunes_artwork_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music124/v4/ca/3f/93/ca3f9393-6359-08fb-2f6e-7c5de94b44bd/190295516475.jpg/100x100bb.jpg',
                'itunes_preview_url' => 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview124/v4/26/a8/12/26a812aa-3e67-bf34-bcf6-b39c29b6e21c/mzaf_7831536506776485616.plus.aac.p.m4a',
                'itunes_track_view_url' => 'https://music.apple.com/us/album/dont-start-now/1503909018?i=1510071688',
                'itunes_track_time_millis' => 183290,
                'itunes_country' => 'USA',
                'itunes_primary_genre_name' => 'Pop',
            ],
            [
                'titulo' => null,
                'descripcion' => 'Blockchain coding sessions with The Weeknd hits different 🔗⛓️',
                'tipo' => 'musica',
                'user_id' => 9, // Daniel (Blockchain)
                'music_source' => 'itunes',
                'itunes_track_id' => '1440742579',
                'itunes_track_name' => 'Heartless',
                'itunes_artist_name' => 'The Weeknd',
                'itunes_collection_name' => 'After Hours',
                'itunes_artwork_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music114/v4/3b/87/c4/3b87c4b6-8b5d-4ad5-8ec4-7e65b59a2a54/19UM1IM16997.rgb.jpg/100x100bb.jpg',
                'itunes_preview_url' => 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview125/v4/b2/54/86/b25486eb-3c1a-1fe8-fe98-f9de8b41e62b/mzaf_12354474056522261958.plus.aac.p.m4a',
                'itunes_track_view_url' => 'https://music.apple.com/us/album/heartless/1440742590?i=1440742579',
                'itunes_track_time_millis' => 208627,
                'itunes_country' => 'USA',
                'itunes_primary_genre_name' => 'Pop',
            ],
        ];

        // Crear los posts de música con canciones reales
        foreach ($musicPosts as $postData) {
            // Generar términos de búsqueda para plataformas cruzadas
            if (isset($postData['itunes_artist_name']) && isset($postData['itunes_track_name'])) {
                $searchTerms = \App\Services\CrossPlatformMusicService::cleanSearchTerms(
                    $postData['itunes_artist_name'],
                    $postData['itunes_track_name']
                );
                $postData['artist_search_term'] = $searchTerms['artist'];
                $postData['track_search_term'] = $searchTerms['track'];
                $postData['spotify_web_url'] = \App\Services\CrossPlatformMusicService::generateSpotifySearchUrl(
                    $searchTerms['artist'],
                    $searchTerms['track']
                );
            }

            $post = Post::create($postData);
            $this->command->info("🎵 Post de música: {$post->itunes_track_name} por {$post->itunes_artist_name}");
        }

        // Crear posts adicionales usando factory
        $this->command->info('📝 Creando posts adicionales con factory...');
        $factoryPosts = Post::factory(20)->create();

        $this->command->info("✅ Posts factory creados: " . $factoryPosts->count());

        // Estadísticas finales
        $totalPosts = Post::count();
        $imagePosts = Post::where('tipo', 'imagen')->count();
        $musicPosts = Post::where('tipo', 'musica')->count();

        $this->command->info('📊 RESUMEN DE POSTS CREADOS:');
        $this->command->line('   • Total de posts: ' . $totalPosts);
        $this->command->line('   • Posts de imagen: ' . $imagePosts);
        $this->command->line('   • Posts de música: ' . $musicPosts);
    }
}
