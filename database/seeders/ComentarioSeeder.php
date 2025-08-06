<?php

namespace Database\Seeders;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComentarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💬 Creando comentarios en los posts...');
        
        $posts = Post::all();
        $users = User::all();

        // Comentarios más realistas y variados
        $specificComments = [
            // Comentarios para posts de desarrollo
            'Excelente trabajo! ¿Qué stack de tecnologías usaste?',
            'El código se ve muy limpio, me gusta tu enfoque',
            'Increíble proyecto, ¿está disponible en GitHub?',
            'Me inspira mucho ver desarrolladores tan talentosos',
            'La UI se ve súper profesional, buen trabajo!',
            '¿Cuánto tiempo te tomó desarrollar esto?',
            'Definitivamente voy a intentar algo similar',
            'Clean code at its best! 👨‍💻',
            
            // Comentarios para diseño
            'Me encanta la paleta de colores que elegiste',
            'El diseño está increíble, muy creativo!',
            'Tienes un estilo único, sigue así',
            '¿Qué herramientas usas para diseñar?',
            'La tipografía combina perfectamente',
            'Este branding está muy profesional',
            'Wow, qué talento tienes para el diseño',
            'Simple pero muy efectivo, me gusta',
            
            // Comentarios para fotografía
            'Hermosa captura, la composición es perfecta',
            'La luz natural hace toda la diferencia',
            '¡Qué momento tan hermoso capturaste!',
            'Tienes un ojo increíble para la fotografía',
            'Esta foto merece estar en una galería',
            '¿Con qué cámara tomaste esta foto?',
            'El encuadre está perfecto 📸',
            'Nature photography at its finest!',
            
            // Comentarios para música
            'Esta canción también es una de mis favoritas!',
            'Excelente elección musical 🎵',
            'Me encanta este artista, gran gusto musical',
            'Perfect song for coding sessions',
            'Esta canción siempre me pone de buen humor',
            'Clásico atemporal, nunca pasa de moda',
            'Great vibes! 🎶',
            'Need to add this to my playlist',
            
            // Comentarios para arte
            'Tu arte siempre me inspira mucho',
            'Los colores transmiten mucha emoción',
            'Art is life! Increíble trabajo',
            '¿Cuál fue tu inspiración para esta pieza?',
            'El contraste está increíble',
            'Cada obra tuya es mejor que la anterior',
            
            // Comentarios para gastronomía
            'Se ve delicioso! ¿Tienes la receta?',
            'Presentation is on point! 👨‍🍳',
            'Mi chef favorito strikes again!',
            'Esto se ve gourmet de verdad',
            'Making me hungry with these photos',
            
            // Comentarios para educación
            'Gracias por compartir conocimiento',
            'Excelente explicación, muy clara',
            'Learning so much from your posts',
            'Keep inspiring the next generation!',
            'Education is the key to everything',
            
            // Comentarios para viajes
            'What an amazing adventure!',
            'Wanderlust activated! 🌍',
            'Adding this place to my bucket list',
            'Travel goals right there',
            'The view is absolutely stunning',
            
            // Comentarios generales positivos
            'Increíble como siempre!',
            'Keep up the amazing work!',
            'You never fail to amaze me',
            'Absolutely love this!',
            'So inspiring! 🙌',
            'This is pure talent',
            'Can\'t wait to see what\'s next',
            'Always bringing the best content',
            'You\'re so talented!',
            'This made my day better',
            'Pure perfection! ✨',
            'Amazing work as always',
        ];

        $commentCount = 0;

        foreach ($posts as $post) {
            // Posts más nuevos y de usuarios específicos tendrán más comentarios
            $baseComments = rand(2, 6);
            
            // Posts de usuarios populares (primeros 10) tendrán más interacción
            if ($post->user_id <= 10) {
                $baseComments += rand(1, 3);
            }
            
            for ($i = 0; $i < $baseComments; $i++) {
                // Evitar que el usuario comente en su propio post (ocasionalmente)
                $availableUsers = $users->where('id', '!=', $post->user_id);
                if ($availableUsers->isEmpty()) {
                    $selectedUser = $users->random();
                } else {
                    $selectedUser = $availableUsers->random();
                }
                
                Comentario::create([
                    'user_id' => $selectedUser->id,
                    'post_id' => $post->id,
                    'comentario' => $specificComments[array_rand($specificComments)],
                    'created_at' => $post->created_at->addMinutes(rand(5, 1440)), // Comentarios después del post
                ]);
                $commentCount++;
            }
        }

        $this->command->info('🎲 Creando comentarios adicionales...');
        
        // Crear comentarios adicionales manualmente si no hay factory disponible
        $remainingPosts = $posts->random(min(10, $posts->count()));
        foreach ($remainingPosts as $post) {
            $extraComments = rand(1, 3);
            for ($i = 0; $i < $extraComments; $i++) {
                $availableUsers = $users->where('id', '!=', $post->user_id);
                if ($availableUsers->isNotEmpty()) {
                    Comentario::create([
                        'user_id' => $availableUsers->random()->id,
                        'post_id' => $post->id,
                        'comentario' => $specificComments[array_rand($specificComments)],
                        'created_at' => $post->created_at->addMinutes(rand(30, 2880)),
                    ]);
                    $commentCount++;
                }
            }
        }
        
        $totalComments = Comentario::count();
        $this->command->info("✅ Total de comentarios creados: {$totalComments}");
    }
}
