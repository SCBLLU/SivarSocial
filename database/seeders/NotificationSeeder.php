<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan usuarios y posts
        if (User::count() < 2) {
            $this->command->info('Se necesitan al menos 2 usuarios para crear notificaciones.');
            return;
        }

        if (Post::count() < 1) {
            $this->command->info('Se necesita al menos 1 post para crear notificaciones.');
            return;
        }

        $users = User::all();

        foreach ($users as $user) {
            // Crear algunas notificaciones para cada usuario
            $otherUsers = $users->where('id', '!=', $user->id);

            foreach ($otherUsers->take(3) as $fromUser) {
                // Notificación de follow
                Notification::create([
                    'user_id' => $user->id,
                    'from_user_id' => $fromUser->id,
                    'type' => Notification::TYPE_FOLLOW,
                    'data' => [
                        'follower_username' => $fromUser->username,
                        'follower_name' => $fromUser->name,
                        'follower_image' => $fromUser->imagen
                    ],
                    'read_at' => rand(0, 1) ? now()->subHours(rand(1, 24)) : null,
                    'created_at' => now()->subHours(rand(1, 48))
                ]);

                // Notificaciones de like en posts del usuario
                $userPosts = Post::where('user_id', $user->id)->take(2)->get();
                foreach ($userPosts as $post) {
                    Notification::create([
                        'user_id' => $user->id,
                        'from_user_id' => $fromUser->id,
                        'type' => Notification::TYPE_LIKE,
                        'post_id' => $post->id,
                        'data' => [
                            'liker_username' => $fromUser->username,
                            'liker_name' => $fromUser->name,
                            'liker_image' => $fromUser->imagen,
                            'post_title' => $post->titulo ?? '',
                            'post_image' => $post->imagen ?? null
                        ],
                        'read_at' => rand(0, 1) ? now()->subHours(rand(1, 12)) : null,
                        'created_at' => now()->subHours(rand(1, 24))
                    ]);

                    // Notificación de comentario
                    if (rand(0, 1)) {
                        Notification::create([
                            'user_id' => $user->id,
                            'from_user_id' => $fromUser->id,
                            'type' => Notification::TYPE_COMMENT,
                            'post_id' => $post->id,
                            'data' => [
                                'commenter_username' => $fromUser->username,
                                'commenter_name' => $fromUser->name,
                                'commenter_image' => $fromUser->imagen,
                                'post_title' => $post->titulo ?? '',
                                'post_image' => $post->imagen ?? null,
                                'comment_preview' => 'Excelente publicación! Me encanta tu contenido.'
                            ],
                            'read_at' => rand(0, 1) ? now()->subHours(rand(1, 6)) : null,
                            'created_at' => now()->subHours(rand(1, 12))
                        ]);
                    }
                }
            }
        }

        $this->command->info('Notificaciones de ejemplo creadas exitosamente.');
    }
}
