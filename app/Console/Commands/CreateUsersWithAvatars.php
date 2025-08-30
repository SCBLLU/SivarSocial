<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUsersWithAvatars extends Command
{
    protected $signature = 'users:create-with-avatars {count=5 : Number of users to create}';
    protected $description = 'Create users with automatically generated avatar images';

    public function handle()
    {
        $count = $this->argument('count');
        
        if ($count > 50) {
            $count = 50;
            $this->info('Máximo 50 usuarios permitidos. Ajustado a 50.');
        }
        
        if ($count < 1) {
            $count = 1;
            $this->info('Mínimo 1 usuario requerido. Ajustado a 1.');
        }

        $this->info("Creando {$count} usuarios con avatares...");

        // Colores para los avatares
        $colors = ['#1a1a1a', '#2d2d2d', '#333333', '#404040', '#1e3a8a', '#0f172a', '#374151', '#4b5563'];
        $techTerms = ['DEV', 'CODE', 'TECH', 'API', 'UI', 'UX', 'AI', 'ML', 'FULL', 'STACK'];

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        for ($i = 0; $i < $count; $i++) {
            // Crear usuario
            $user = User::factory()->create();
            
            // Generar nombre de avatar
            $avatarName = $user->username . '-avatar.jpg';
            
            // Actualizar usuario con imagen
            $user->update(['imagen' => $avatarName]);
            
            // Crear imagen de avatar
            $this->createAvatarImage($avatarName, $colors, $techTerms);
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("✅ {$count} usuarios creados exitosamente con avatares!");
        
        // Mostrar lista de usuarios creados
        $this->newLine();
        $this->info('👥 Usuarios creados:');
        $recentUsers = User::latest()->take($count)->get();
        foreach ($recentUsers as $user) {
            $this->line("   • {$user->name} (@{$user->username}) - {$user->profession} - Foto: {$user->imagen}");
        }
    }

    private function createAvatarImage($avatarName, $colors, $techTerms)
    {
        $width = 400;
        $height = 400;
        
        // Crear imagen
        $image = imagecreatetruecolor($width, $height);
        
        // Color de fondo aleatorio
        $bgColor = $colors[array_rand($colors)];
        list($r, $g, $b) = sscanf($bgColor, '#%02x%02x%02x');
        $backgroundColor = imagecolorallocate($image, $r, $g, $b);
        imagefill($image, 0, 0, $backgroundColor);
        
        // Colores para texto
        $white = imagecolorallocate($image, 255, 255, 255);
        $neonGreen = imagecolorallocate($image, 0, 255, 127);
        $neonBlue = imagecolorallocate($image, 0, 191, 255);
        $neonOrange = imagecolorallocate($image, 255, 69, 0);
        
        $textColors = [$white, $neonGreen, $neonBlue, $neonOrange];
        $textColor = $textColors[array_rand($textColors)];
        
        // Término tecnológico aleatorio
        $term = $techTerms[array_rand($techTerms)];
        
        // Calcular posición del texto
        $fontSize = 5; // Tamaño de fuente (1-5 para imagestring)
        $textWidth = imagefontwidth($fontSize) * strlen($term);
        $textHeight = imagefontheight($fontSize);
        
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        // Agregar texto
        imagestring($image, $fontSize, $x, $y, $term, $textColor);
        
        // Guardar imagen
        $avatarPath = public_path('perfiles/' . $avatarName);
        
        // Asegurar que el directorio existe
        $directory = dirname($avatarPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        imagejpeg($image, $avatarPath, 90);
        imagedestroy($image);
    }
}
