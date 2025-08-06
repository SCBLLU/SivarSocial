<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('❤️  Creando likes en los posts...');
        
        $posts = Post::all();
        $users = User::all();
        $likesCreated = 0;

        foreach ($posts as $post) {
            // Posts más antiguos y de usuarios populares tendrán más likes
            $baseLikes = rand(3, 12);
            
            // Posts de usuarios específicos (primeros 10) tendrán más likes
            if ($post->user_id <= 10) {
                $baseLikes += rand(2, 8);
            }
            
            // Posts de imagen tienden a tener más likes que posts de música
            if ($post->tipo === 'imagen') {
                $baseLikes += rand(1, 5);
            }
            
            // Asegurar que no tengamos más likes que usuarios
            $maxLikes = min($baseLikes, $users->count() - 1); // -1 para evitar que el autor se haga like a sí mismo
            
            $likedUsers = $users->where('id', '!=', $post->user_id)->random(min($maxLikes, $users->count() - 1));
            
            foreach ($likedUsers as $user) {
                // Verificar que el usuario no haya dado like ya a este post
                $existingLike = Like::where('user_id', $user->id)
                                   ->where('post_id', $post->id)
                                   ->first();
                
                if (!$existingLike) {
                    Like::create([
                        'user_id' => $user->id,
                        'post_id' => $post->id,
                        'created_at' => $post->created_at->addMinutes(rand(1, 2880)), // Likes después del post
                    ]);
                    $likesCreated++;
                }
            }
        }

        $this->command->info('🎲 Creando likes adicionales...');
        
        // Crear algunos likes adicionales manualmente
        $randomPosts = $posts->random(min(15, $posts->count()));
        foreach ($randomPosts as $post) {
            $extraLikes = rand(1, 3);
            $availableUsers = $users->where('id', '!=', $post->user_id)->random(min($extraLikes, $users->count() - 1));
            
            foreach ($availableUsers as $user) {
                $existingLike = Like::where('user_id', $user->id)
                                   ->where('post_id', $post->id)
                                   ->first();
                
                if (!$existingLike) {
                    Like::create([
                        'user_id' => $user->id,
                        'post_id' => $post->id,
                        'created_at' => $post->created_at->addMinutes(rand(5, 1440)),
                    ]);
                    $likesCreated++;
                }
            }
        }

        $totalLikes = Like::count();
        $this->command->info("✅ Total de likes creados: {$totalLikes}");
        $this->command->line("   • Likes creados: {$likesCreated}");
        
        // Mostrar estadísticas adicionales
        $avgLikesPerPost = round($totalLikes / $posts->count(), 1);
        $this->command->line("   • Promedio de likes por post: {$avgLikesPerPost}");
    }
}
