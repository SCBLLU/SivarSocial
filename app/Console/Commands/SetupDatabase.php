<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sivarsocial:setup
                          {--fresh : Ejecutar migraciones frescas}
                          {--images : Crear imágenes de muestra}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configura la base de datos de SivarSocial con datos de prueba';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Configurando SivarSocial...');
        $this->newLine();

        // Ejecutar migraciones frescas si se especifica
        if ($this->option('fresh')) {
            $this->info('📦 Ejecutando migraciones frescas...');
            Artisan::call('migrate:fresh');
            $this->info('✅ Migraciones completadas');
            $this->newLine();
        }

        // Ejecutar seeders
        $this->info('🌱 Poblando base de datos...');
        
        if ($this->option('images')) {
            $this->info('🖼️  Creando imágenes de muestra...');
            Artisan::call('db:seed', ['--class' => 'ImageSeeder']);
        }

        $this->info('👥 Creando usuarios...');
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);

        $this->info('📝 Creando posts...');
        Artisan::call('db:seed', ['--class' => 'PostSeeder']);

        $this->info('💬 Creando comentarios...');
        Artisan::call('db:seed', ['--class' => 'ComentarioSeeder']);

        $this->info('❤️  Creando likes...');
        Artisan::call('db:seed', ['--class' => 'LikeSeeder']);

        $this->info('👫 Creando relaciones de seguimiento...');
        Artisan::call('db:seed', ['--class' => 'FollowerSeeder']);

        $this->newLine();
        $this->info('🎉 ¡SivarSocial configurado exitosamente!');
        $this->newLine();

        // Mostrar información de usuarios destacados
        $this->table(
            ['Usuario', 'Email', 'Password', 'Profesión'],
            [
                ['admin', 'admin@sivarsocial.com', 'password', 'Senior System Administrator'],
                ['maria_design', 'maria@sivarsocial.com', 'password', 'Senior UI/UX Designer'],
                ['carlos_dev', 'carlos@sivarsocial.com', 'password', 'Senior Full Stack Developer'],
                ['ana_ai', 'ana@sivarsocial.com', 'password', 'Machine Learning Engineer'],
                ['luis_devops', 'luis@sivarsocial.com', 'password', 'DevOps Engineer'],
                ['sofia_security', 'sofia@sivarsocial.com', 'password', 'Cybersecurity Specialist'],
                ['miguel_mobile', 'miguel@sivarsocial.com', 'password', 'Mobile App Developer'],
                ['laura_data', 'laura@sivarsocial.com', 'password', 'Data Scientist'],
                ['daniel_blockchain', 'daniel@sivarsocial.com', 'password', 'Blockchain Developer'],
                ['elena_cloud', 'elena@sivarsocial.com', 'password', 'Cloud Solutions Architect'],
                ['+ 15 usuarios más', 'generados con factory', 'password', 'Profesiones tecnológicas'],
            ]
        );

        $this->newLine();
        $this->line('📊 <fg=green>Datos creados:</>');
        $this->line('   • 25 usuarios con profesiones tecnológicas');
        $this->line('   • ~47 posts (19 imágenes tech + 8 música iTunes + 20 factory)');
        $this->line('   • ~270+ comentarios');
        $this->line('   • ~590+ likes');
        $this->line('   • 200 relaciones de seguimiento');
        $this->line('   • Imágenes con colores sólidos (negros y acentos neón)');
        $this->line('   • Música real de iTunes (The Weeknd, Ed Sheeran, Billie Eilish, etc.)');
        
        if ($this->option('images')) {
            $this->line('   • 32 imágenes adicionales de muestra generadas');
        }

        $this->newLine();
        $this->info('🌐 ¡Ya puedes usar tu aplicación SivarSocial!');
    }
}
