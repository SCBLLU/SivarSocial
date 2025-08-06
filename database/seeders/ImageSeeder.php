<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🖼️  Creando imágenes de muestra...');
        $this->createSampleImages();
    }

    private function createSampleImages(): void
    {
        // Crear directorios si no existen
        $perfilesPath = public_path('perfiles');
        $uploadsPath = public_path('uploads');

        if (!File::exists($perfilesPath)) {
            File::makeDirectory($perfilesPath, 0755, true);
            $this->command->info("📁 Directorio creado: perfiles/");
        }

        if (!File::exists($uploadsPath)) {
            File::makeDirectory($uploadsPath, 0755, true);
            $this->command->info("📁 Directorio creado: uploads/");
        }

        // Paleta de colores sólidos para perfiles (tonos negros con acentos tecnológicos)
        $profileColors = [
            'admin-avatar.jpg' => ['bg' => [15, 15, 15], 'text' => [0, 255, 127], 'name' => 'AD'], // Negro con verde tech
            'maria-avatar.jpg' => ['bg' => [20, 20, 20], 'text' => [0, 191, 255], 'name' => 'MG'], // Negro con azul cyber
            'carlos-avatar.jpg' => ['bg' => [25, 25, 25], 'text' => [50, 205, 50], 'name' => 'CR'], // Negro con verde matrix
            'ana-avatar.jpg' => ['bg' => [18, 18, 18], 'text' => [255, 20, 147], 'name' => 'AM'], // Negro con rosa neon
            'luis-avatar.jpg' => ['bg' => [22, 22, 22], 'text' => [255, 140, 0], 'name' => 'LH'], // Negro con naranja tech
            'sofia-avatar.jpg' => ['bg' => [28, 28, 28], 'text' => [138, 43, 226], 'name' => 'SC'], // Negro con violeta
            'miguel-avatar.jpg' => ['bg' => [30, 30, 30], 'text' => [255, 215, 0], 'name' => 'MT'], // Negro con dorado
            'laura-avatar.jpg' => ['bg' => [16, 16, 16], 'text' => [0, 255, 255], 'name' => 'LJ'], // Negro con cyan
            'daniel-avatar.jpg' => ['bg' => [24, 24, 24], 'text' => [124, 252, 0], 'name' => 'DV'], // Negro con verde lima
            'elena-avatar.jpg' => ['bg' => [19, 19, 19], 'text' => [255, 105, 180], 'name' => 'EM'], // Negro con rosa tech
        ];

        // Paleta de colores sólidos para posts (tonos negros y grises con temática tech)
        $postColors = [
            // Desarrollo y programación (tonos negros con acentos verdes)
            'proyecto-desarrollo.jpg' => ['bg' => [10, 10, 10], 'text' => [0, 255, 127], 'name' => 'DEV'],
            'codigo-programacion.jpg' => ['bg' => [15, 15, 15], 'text' => [46, 204, 113], 'name' => 'CODE'],
            'setup-developer.jpg' => ['bg' => [20, 20, 20], 'text' => [39, 174, 96], 'name' => 'SETUP'],
            'terminal-commands.jpg' => ['bg' => [12, 12, 12], 'text' => [0, 255, 0], 'name' => 'TERM'],
            
            // Diseño UI/UX (negros con acentos azules)
            'ui-design.jpg' => ['bg' => [18, 18, 18], 'text' => [52, 152, 219], 'name' => 'UI/UX'],
            'app-mockup.jpg' => ['bg' => [22, 22, 22], 'text' => [41, 128, 185], 'name' => 'APP'],
            'wireframe-sketch.jpg' => ['bg' => [25, 25, 25], 'text' => [100, 149, 237], 'name' => 'WIRE'],
            
            // Inteligencia Artificial (negros con acentos violetas/rosas)
            'ai-research.jpg' => ['bg' => [14, 14, 14], 'text' => [138, 43, 226], 'name' => 'AI'],
            'machine-learning.jpg' => ['bg' => [17, 17, 17], 'text' => [255, 20, 147], 'name' => 'ML'],
            'neural-network.jpg' => ['bg' => [21, 21, 21], 'text' => [186, 85, 211], 'name' => 'NN'],
            
            // DevOps y Cloud (negros con acentos naranjas)
            'devops-pipeline.jpg' => ['bg' => [16, 16, 16], 'text' => [255, 140, 0], 'name' => 'DEVOPS'],
            'cloud-architecture.jpg' => ['bg' => [19, 19, 19], 'text' => [255, 165, 0], 'name' => 'CLOUD'],
            'docker-containers.jpg' => ['bg' => [23, 23, 23], 'text' => [255, 69, 0], 'name' => 'DOCKER'],
            
            // Ciberseguridad (negros con acentos rojos)
            'security-audit.jpg' => ['bg' => [13, 13, 13], 'text' => [220, 20, 60], 'name' => 'SEC'],
            'pentesting-tools.jpg' => ['bg' => [26, 26, 26], 'text' => [255, 0, 0], 'name' => 'PENTEST'],
            'encryption-keys.jpg' => ['bg' => [24, 24, 24], 'text' => [178, 34, 34], 'name' => 'CRYPTO'],
            
            // Base de datos (negros con acentos amarillos)
            'database-design.jpg' => ['bg' => [27, 27, 27], 'text' => [255, 215, 0], 'name' => 'DB'],
            'sql-queries.jpg' => ['bg' => [29, 29, 29], 'text' => [255, 255, 0], 'name' => 'SQL'],
            'nosql-mongodb.jpg' => ['bg' => [31, 31, 31], 'text' => [240, 230, 140], 'name' => 'NoSQL'],
            
            // Fotografía (negros puros)
            'sesion-centro.jpg' => ['bg' => [10, 10, 10], 'text' => [255, 255, 255], 'name' => 'PHOTO'],
            'atardecer-playa.jpg' => ['bg' => [15, 15, 15], 'text' => [255, 193, 7], 'name' => 'SUNSET'],
            'naturaleza-macro.jpg' => ['bg' => [12, 12, 12], 'text' => [76, 175, 80], 'name' => 'MACRO'],
            'retrato-urbano.jpg' => ['bg' => [20, 20, 20], 'text' => [158, 158, 158], 'name' => 'STREET'],
            
            // Arte y creatividad (negros con colores vibrantes)
            'arte-abstracto.jpg' => ['bg' => [0, 0, 0], 'text' => [255, 87, 34], 'name' => 'ART'],
            'mural-urbano.jpg' => ['bg' => [17, 17, 17], 'text' => [255, 152, 0], 'name' => 'MURAL'],
            'escultura-moderna.jpg' => ['bg' => [25, 25, 25], 'text' => [103, 58, 183], 'name' => 'SCULPT'],
            
            // Comida (negros elegantes)
            'comida-gourmet.jpg' => ['bg' => [30, 30, 30], 'text' => [255, 193, 7], 'name' => 'GOURMET'],
            'cafe-latte.jpg' => ['bg' => [35, 35, 35], 'text' => [121, 85, 72], 'name' => 'COFFEE'],
            'viaje-aventura.jpg' => ['bg' => [18, 18, 18], 'text' => [0, 188, 212], 'name' => 'TRAVEL'],
            
            // Educación (negros profesionales)
            'clase-online.jpg' => ['bg' => [33, 33, 33], 'text' => [33, 150, 243], 'name' => 'TEACH'],
            'laboratorio.jpg' => ['bg' => [28, 28, 28], 'text' => [76, 175, 80], 'name' => 'LAB'],
            'biblioteca-estudio.jpg' => ['bg' => [40, 40, 40], 'text' => [255, 235, 59], 'name' => 'STUDY'],
        ];

        $this->command->info("📸 Creando imágenes de perfil con colores sólidos...");
        foreach ($profileColors as $filename => $colorData) {
            $this->createSolidColorImage($perfilesPath . '/' . $filename, 400, 400, $colorData, 'perfil');
        }

        $this->command->info("🎨 Creando imágenes de posts con paleta negra...");
        foreach ($postColors as $filename => $colorData) {
            $this->createSolidColorImage($uploadsPath . '/' . $filename, 800, 600, $colorData, 'post');
        }

        $this->command->info('✅ Imágenes con colores sólidos creadas exitosamente.');
    }

    private function createSolidColorImage(string $path, int $width, int $height, array $colorData, string $type): void
    {
        try {
            if (extension_loaded('gd')) {
                $image = imagecreatetruecolor($width, $height);
                
                // Crear colores
                $bgColor = imagecolorallocate($image, $colorData['bg'][0], $colorData['bg'][1], $colorData['bg'][2]);
                $textColor = imagecolorallocate($image, $colorData['text'][0], $colorData['text'][1], $colorData['text'][2]);
                
                // Rellenar fondo
                imagefill($image, 0, 0, $bgColor);
                
                // Agregar texto
                $text = $colorData['name'];
                
                // Usar fuente grande para el texto
                if ($width >= 800) {
                    // Para posts - texto más grande
                    $fontSize = 5;
                    $textWidth = imagefontwidth($fontSize) * strlen($text);
                    $textHeight = imagefontheight($fontSize);
                } else {
                    // Para perfiles - texto mediano
                    $fontSize = 4;
                    $textWidth = imagefontwidth($fontSize) * strlen($text);
                    $textHeight = imagefontheight($fontSize);
                }
                
                $x = ($width - $textWidth) / 2;
                $y = ($height - $textHeight) / 2;
                
                imagestring($image, $fontSize, $x, $y, $text, $textColor);
                
                // Agregar un borde sutil
                $borderColor = imagecolorallocate($image, $colorData['text'][0], $colorData['text'][1], $colorData['text'][2]);
                imagerectangle($image, 0, 0, $width-1, $height-1, $borderColor);
                
                imagejpeg($image, $path, 95);
                imagedestroy($image);
                
                $this->command->line("   ✓ " . basename($path) . " (color sólido)");
            } else {
                $this->command->warn("   ✗ GD no disponible para: " . basename($path));
            }
        } catch (\Exception $e) {
            $this->command->warn("   ✗ Error creando imagen: " . basename($path) . " - " . $e->getMessage());
        }
    }

    private function downloadImage(string $url, string $path, string $type = 'imagen'): void
    {
        try {
            // Intentar descargar la imagen usando cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            
            $imageContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($imageContent !== false && $httpCode === 200) {
                file_put_contents($path, $imageContent);
                $this->command->line("   ✓ " . basename($path) . " (" . $type . ")");
            } else {
                // Crear una imagen placeholder simple si falla la descarga
                $this->createPlaceholderImage($path, $type);
            }
        } catch (\Exception $e) {
            $this->createPlaceholderImage($path, $type);
        }
    }

    private function createPlaceholderImage(string $path, string $type): void
    {
        try {
            // Crear una imagen simple con GD si está disponible
            if (extension_loaded('gd')) {
                $width = ($type === 'perfil') ? 400 : 800;
                $height = ($type === 'perfil') ? 400 : 600;
                
                $image = imagecreatetruecolor($width, $height);
                $bgColor = imagecolorallocate($image, 59, 37, 221); // Color principal de SivarSocial
                $textColor = imagecolorallocate($image, 255, 255, 255);
                
                imagefill($image, 0, 0, $bgColor);
                
                $text = $type === 'perfil' ? 'PERFIL' : 'POST';
                $font = 5;
                $textWidth = imagefontwidth($font) * strlen($text);
                $textHeight = imagefontheight($font);
                $x = ($width - $textWidth) / 2;
                $y = ($height - $textHeight) / 2;
                
                imagestring($image, $font, $x, $y, $text, $textColor);
                
                imagejpeg($image, $path, 80);
                imagedestroy($image);
                
                $this->command->line("   ○ " . basename($path) . " (placeholder generado)");
            } else {
                $this->command->warn("   ✗ No se pudo crear: " . basename($path));
            }
        } catch (\Exception $e) {
            $this->command->warn("   ✗ Error creando placeholder: " . basename($path));
        }
    }
}
