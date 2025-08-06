<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Iniciando poblado de la base de datos...');
        
        $this->call([
            ImageSeeder::class,      // Crear imágenes de muestra primero
            UserSeeder::class,       // Usuarios con imágenes de perfil
            PostSeeder::class,       // Posts con imágenes y música
            ComentarioSeeder::class, // Comentarios en los posts
            LikeSeeder::class,       // Likes en posts
            FollowerSeeder::class,   // Relaciones de seguimiento
        ]);
        
        $this->command->info('✅ Base de datos poblada exitosamente!');
        $this->command->info('📊 Datos creados:');
        $this->command->line('   • Usuarios con imágenes de perfil');
        $this->command->line('   • Posts con imágenes y música');
        $this->command->line('   • Comentarios y likes');
        $this->command->line('   • Relaciones de seguimiento');
    }
}
