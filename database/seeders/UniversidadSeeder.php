<?php

namespace Database\Seeders;

use App\Models\Universidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creando universidades...');
        // Insertando universidades a la base de datos
        $universidad= [
            ['nombre' => 'Universidad de El Salvador'],
            ['nombre' => 'Universidad Centroamericana José Simeón Cañas'],
            ['nombre' => 'Universidad Tecnológica de El Salvador'],
            ['nombre' => 'Universidad Don Bosco'],
            ['nombre' => 'Universidad Gerardo Barrios'],
            ['nombre' => 'Universidad Francisco Gavidia'],
            ['nombre' => 'ITCA-FEPADE'],
            ['nombre' => 'Universidad Doctor José Matías Delgado'],
            ['nombre' => 'Universidad Dr. Andrés Bello'],
            ['nombre' => 'Universidad Salvadoreña Alberto Masferrer'],
        ];

        foreach ($universidad as $universidadData) {
            Universidad::create($universidadData);
        }

        $totaluniversidad = Universidad::count();
        $this->command->info("Total de carreras creadas: {$totaluniversidad}");
    }
}
