<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\su_ad;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superUser = [
            'name' => 'Administrador',
            'email' => 'admin@sivarsocial.com',
            'password' => Hash::make('SuperSecure2025!'),
            'password_verific_modify' => Hash::make('VerifSecure2025!'),
            'is_admin' => true,
            'imagen' => 'img/usuario.svg',
            'profession' => 'Administrador del Sistema',
            'last_login' => now(),
        ];

        su_ad::create($superUser);

        $this->command->info('Super usuario creado exitosamente.');
    }
}
