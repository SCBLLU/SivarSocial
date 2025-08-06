<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Creando usuarios con perfiles específicos...');
        
        // Crear usuarios específicos de prueba con perfiles enfocados en tecnología avanzada
        $specificUsers = [
            [
                'name' => 'Admin SivarSocial',
                'username' => 'admin',
                'email' => 'admin@sivarsocial.com',
                'password' => Hash::make('password'),
                'imagen' => 'admin-avatar.jpg',
                'gender' => 'Male',
                'profession' => 'Senior System Administrator',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'María González',
                'username' => 'maria_design',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'imagen' => 'maria-avatar.jpg',
                'gender' => 'Female',
                'profession' => 'Senior UI/UX Designer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carlos Rodríguez',
                'username' => 'carlos_dev',
                'email' => 'carlos@example.com',
                'password' => Hash::make('password'),
                'imagen' => 'carlos-avatar.jpg',
                'gender' => 'Male',
                'profession' => 'Senior Full Stack Developer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ana Martínez',
                'username' => 'ana_ai',
                'email' => 'ana@example.com',
                'password' => Hash::make('password'),
                'imagen' => 'ana-avatar.jpg',
                'gender' => 'Female',
                'profession' => 'Machine Learning Engineer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Luis Hernández',
                'username' => 'luis_devops',
                'email' => 'luis@example.com',
                'password' => Hash::make('password'),
                'imagen' => 'luis-avatar.jpg',
                'gender' => 'Male',
                'profession' => 'DevOps Engineer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sofía Chen',
                'username' => 'sofia_security',
                'email' => 'sofia@example.com',
                'password' => Hash::make('password'),
                'imagen' => 'sofia-avatar.jpg',
                'gender' => 'Female',
                'profession' => 'Cybersecurity Specialist',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Miguel Torres',
                'username' => 'miguel_mobile',
                'email' => 'miguel@example.com',
                'password' => Hash::make('password'),
                'imagen' => 'miguel-avatar.jpg',
                'gender' => 'Male',
                'profession' => 'Mobile App Developer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Laura Johnson',
                'username' => 'laura_data',
                'email' => 'laura@example.com',
                'password' => Hash::make('password'),
                'imagen' => 'laura-avatar.jpg',
                'gender' => 'Female',
                'profession' => 'Data Scientist',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Daniel Vega',
                'username' => 'daniel_blockchain',
                'email' => 'daniel@example.com',
                'password' => Hash::make('password'),
                'imagen' => 'daniel-avatar.jpg',
                'gender' => 'Male',
                'profession' => 'Blockchain Developer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Elena Morales',
                'username' => 'elena_cloud',
                'email' => 'elena@example.com',
                'password' => Hash::make('password'),
                'imagen' => 'elena-avatar.jpg',
                'gender' => 'Female',
                'profession' => 'Cloud Solutions Architect',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($specificUsers as $userData) {
            $user = User::create($userData);
            $this->command->info("✅ Usuario creado: {$user->name} (@{$user->username}) - {$user->profession}");
        }

        // Crear usuarios adicionales usando factory con profesiones tecnológicas
        $this->command->info('👥 Creando usuarios adicionales con factory...');
        
        // Lista de profesiones tecnológicas para usuarios factory
        $techProfessions = [
            'Software Engineer',
            'Frontend Developer',
            'Backend Developer',
            'QA Automation Engineer',
            'Product Manager',
            'Scrum Master',
            'Database Administrator',
            'Network Engineer',
            'Game Developer',
            'AR/VR Developer',
            'IoT Engineer',
            'Robotics Engineer',
            'Tech Lead',
            'Solutions Architect',
            'Security Analyst',
        ];

        // Crear 15 usuarios adicionales con profesiones tech
        for ($i = 0; $i < 15; $i++) {
            $user = User::factory()->create([
                'profession' => $techProfessions[array_rand($techProfessions)]
            ]);
            $this->command->info("✅ Usuario factory: {$user->name} (@{$user->username}) - {$user->profession}");
        }

        $totalUsers = User::count();
        $this->command->info("🎉 Total de usuarios creados: {$totalUsers}");
    }
}
