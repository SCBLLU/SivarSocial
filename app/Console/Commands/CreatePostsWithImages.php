<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;

class CreatePostsWithImages extends Command
{
    protected $signature = 'posts:create-with-images {count=10 : Number of posts to create}';
    protected $description = 'Create posts with automatically generated images';

    public function handle()
    {
        $count = $this->argument('count');
        
        if ($count > 30) {
            $count = 30;
            $this->info('Máximo 30 posts permitidos. Ajustado a 30.');
        }
        
        if ($count < 1) {
            $count = 1;
            $this->info('Mínimo 1 post requerido. Ajustado a 1.');
        }

        // Verificar que hay usuarios
        $userCount = User::count();
        if ($userCount == 0) {
            $this->error('No hay usuarios en la base de datos. Crea usuarios primero.');
            return;
        }

        $this->info("Creando {$count} posts con imágenes automáticas...");

        // Colores para las imágenes
        $colors = ['#1a1a1a', '#2d2d2d', '#333333', '#404040', '#1e3a8a', '#0f172a', '#374151', '#4b5563'];
        $techTerms = ['CODING', 'DEVELOP', 'DESIGN', 'TECH', 'API', 'CODE', 'UI/UX', 'STACK', 'FRONTEND', 'BACKEND'];

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        for ($i = 0; $i < $count; $i++) {
            // Crear post usando factory pero asegurándonos de que el usuario existe
            $randomUser = User::inRandomOrder()->first();
            $post = Post::factory()->create(['user_id' => $randomUser->id]);
            
            // Si es un post de imagen, crear la imagen
            if ($post->tipo === 'imagen') {
                $imageName = 'auto-post-' . $post->id . '.jpg';
                $post->update(['imagen' => $imageName]);
                
                // Crear imagen para el post
                $this->createPostImage($imageName, $colors, $techTerms);
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("✅ {$count} posts creados exitosamente!");
        
        // Mostrar estadísticas
        $this->newLine();
        $this->info('📊 Resumen de posts creados:');
        $recentPosts = Post::latest()->take($count)->get();
        $imagePosts = $recentPosts->where('tipo', 'imagen')->count();
        $musicPosts = $recentPosts->where('tipo', 'musica')->count();
        
        $this->line("   • Posts de imagen: {$imagePosts}");
        $this->line("   • Posts de música: {$musicPosts}");
        $this->line("   • Total: " . $recentPosts->count());
    }

    private function createPostImage($imageName, $colors, $techTerms)
    {
        $width = 600;
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
        $neonPink = imagecolorallocate($image, 255, 20, 147);
        
        $textColors = [$white, $neonGreen, $neonBlue, $neonOrange, $neonPink];
        $textColor = $textColors[array_rand($textColors)];
        
        // Término tecnológico aleatorio
        $term = $techTerms[array_rand($techTerms)];
        
        // Calcular posición del texto (centrado)
        $fontSize = 5; // Tamaño de fuente (1-5 para imagestring)
        $textWidth = imagefontwidth($fontSize) * strlen($term);
        $textHeight = imagefontheight($fontSize);
        
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        // Agregar texto principal
        imagestring($image, $fontSize, $x, $y, $term, $textColor);
        
        // Agregar elementos decorativos
        $this->addDecorations($image, $width, $height, $textColor);
        
        // Guardar imagen
        $imagePath = public_path('uploads/' . $imageName);
        
        // Asegurar que el directorio existe
        $directory = dirname($imagePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        imagejpeg($image, $imagePath, 90);
        imagedestroy($image);
    }

    private function addDecorations($image, $width, $height, $color)
    {
        // Agregar algunos elementos decorativos simples
        
        // Líneas en las esquinas
        imageline($image, 10, 10, 50, 10, $color);
        imageline($image, 10, 10, 10, 50, $color);
        
        imageline($image, $width - 50, 10, $width - 10, 10, $color);
        imageline($image, $width - 10, 10, $width - 10, 50, $color);
        
        imageline($image, 10, $height - 10, 50, $height - 10, $color);
        imageline($image, 10, $height - 50, 10, $height - 10, $color);
        
        imageline($image, $width - 50, $height - 10, $width - 10, $height - 10, $color);
        imageline($image, $width - 10, $height - 50, $width - 10, $height - 10, $color);
        
        // Algunos puntos decorativos
        for ($i = 0; $i < 10; $i++) {
            $x = rand(0, $width);
            $y = rand(0, $height);
            imagesetpixel($image, $x, $y, $color);
        }
    }
}
