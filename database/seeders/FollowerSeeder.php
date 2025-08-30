<?php

namespace Database\Seeders;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Database\Seeder;

class FollowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Creando relaciones de seguimiento...');
        
        $users = User::all();
        $followsCreated = 0;

        foreach ($users as $user) {
            // Usuarios más activos (primeros 10) seguirán a más personas
            $baseFollowing = rand(4, 8);
            if ($user->id <= 10) {
                $baseFollowing += rand(2, 5);
            }
            
            // Asegurar que no seguimos a más usuarios de los que existen
            $maxFollowing = min($baseFollowing, $users->count() - 1); // -1 para no seguirse a sí mismo
            
            $usersToFollow = $users->where('id', '!=', $user->id)->random($maxFollowing);
            
            foreach ($usersToFollow as $userToFollow) {
                // Verificar que no exista ya la relación
                $existingFollow = Follower::where('user_id', $userToFollow->id)
                                         ->where('follower_id', $user->id)
                                         ->first();
                
                if (!$existingFollow) {
                    Follower::create([
                        'user_id' => $userToFollow->id, // Usuario que es seguido
                        'follower_id' => $user->id,     // Usuario que sigue
                    ]);
                    $followsCreated++;
                }
            }
        }

        $this->command->info('⭐ Creando relaciones específicas entre usuarios destacados...');
        
        // Crear algunas relaciones específicas para usuarios destacados
        $featuredUsers = [
            'admin' => User::where('username', 'admin')->first(),
            'maria_design' => User::where('username', 'maria_design')->first(),
            'carlos_dev' => User::where('username', 'carlos_dev')->first(),
            'ana_music' => User::where('username', 'ana_music')->first(),
            'luis_photo' => User::where('username', 'luis_photo')->first(),
            'sofia_art' => User::where('username', 'sofia_art')->first(),
            'miguel_chef' => User::where('username', 'miguel_chef')->first(),
            'laura_teach' => User::where('username', 'laura_teach')->first(),
            'daniel_travel' => User::where('username', 'daniel_travel')->first(),
            'elena_science' => User::where('username', 'elena_science')->first(),
        ];

        // Filtrar solo usuarios que existen
        $validFeaturedUsers = array_filter($featuredUsers, function($user) {
            return $user !== null;
        });

        if (!empty($validFeaturedUsers)) {
            $admin = $featuredUsers['admin'];
            $otherFeatured = array_slice($validFeaturedUsers, 1); // Todos excepto admin

            // El admin sigue a todos los usuarios destacados
            if ($admin) {
                foreach ($otherFeatured as $user) {
                    Follower::firstOrCreate([
                        'user_id' => $user->id,
                        'follower_id' => $admin->id,
                    ]);
                    $followsCreated++;
                }
            }

            // Los usuarios destacados se siguen entre ellos (relaciones cruzadas)
            foreach ($otherFeatured as $follower) {
                foreach ($otherFeatured as $followed) {
                    if ($follower->id !== $followed->id) {
                        // Crear seguimiento con probabilidad del 70%
                        if (rand(1, 10) <= 7) {
                            $created = Follower::firstOrCreate([
                                'user_id' => $followed->id,
                                'follower_id' => $follower->id,
                            ]);
                            if ($created->wasRecentlyCreated) {
                                $followsCreated++;
                            }
                        }
                    }
                }
            }

            // Usuarios populares (profesionales creativos) tendrán más seguidores
            $popularUsers = array_slice($otherFeatured, 0, 5);
            foreach ($popularUsers as $popularUser) {
                // Hacer que algunos usuarios aleatorios sigan a los populares
                $randomFollowers = $users->where('id', '!=', $popularUser->id)->random(rand(3, 7));
                foreach ($randomFollowers as $follower) {
                    $created = Follower::firstOrCreate([
                        'user_id' => $popularUser->id,
                        'follower_id' => $follower->id,
                    ]);
                    if ($created->wasRecentlyCreated) {
                        $followsCreated++;
                    }
                }
            }
        }

        $totalFollows = Follower::count();
        $this->command->info("✅ Total de relaciones de seguimiento creadas: {$totalFollows}");
        
        // Mostrar estadísticas adicionales
        $avgFollowsPerUser = round($totalFollows / $users->count(), 1);
        $this->command->line("   • Promedio de seguimientos por usuario: {$avgFollowsPerUser}");
        
        // Mostrar usuarios más seguidos
        $topFollowed = User::withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->limit(3)
            ->get();
            
        $this->command->line("   • Usuarios más seguidos:");
        foreach ($topFollowed as $user) {
            $this->command->line("     - {$user->name} (@{$user->username}): {$user->followers_count} seguidores");
        }
    }
}
