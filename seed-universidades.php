<?php

/**
 * Script para insertar universidades, carreras y sus relaciones
 * Uso: php seed-universidades.php
 */

// Cargar el autoloader de Laravel
require __DIR__ . '/vendor/autoload.php';

// Cargar la aplicación Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Hacer bootstrap de la aplicación
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Universidad;
use App\Models\Carrera;
use Illuminate\Support\Facades\DB;

echo "====================================\n";
echo "Script de Inserción de Universidades\n";
echo "====================================\n\n";

try {
    DB::beginTransaction();

    // Verificar si ya existen universidades
    $existingCount = Universidad::count();

    if ($existingCount > 0) {
        echo "⚠️  ADVERTENCIA: Ya existen {$existingCount} universidades en la base de datos.\n";
        echo "¿Deseas continuar y agregar más? (s/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (trim(strtolower($line)) !== 's') {
            echo "Operación cancelada.\n";
            exit(0);
        }
        fclose($handle);
    }

    // Definir las universidades de El Salvador
    $universidades = [
        'Universidad de El Salvador (UES)',
        'Universidad Centroamericana José Simeón Cañas (UCA)',
        'Universidad Tecnológica de El Salvador (UTEC)',
        'Universidad Don Bosco (UDB)',
        'Universidad Francisco Gavidia (UFG)',
        'Universidad Dr. José Matías Delgado',
        'Universidad Modular Abierta (UMA)',
        'Universidad Evangélica de El Salvador (UEES)',
        'Universidad Católica de El Salvador (UNICAES)',
        'Universidad Albert Einstein',
        'Universidad Politécnica de El Salvador',
        'Universidad Salvadoreña Alberto Masferrer (USAM)',
        'Universidad Pedagógica de El Salvador (UPES)',
        'Universidad Andrés Bello',
        'Universidad de Oriente (UNIVO)',
        'Universidad Luterana Salvadoreña',
        'Universidad Isaac Newton',
        'Universidad Capitán General Gerardo Barrios',
        'Universidad Nueva San Salvador',
        'ITCA FEPADE',
    ];

    // Definir carreras comunes en El Salvador
    $carreras = [
        // Ingeniería
        'Ingeniería en Sistemas Informáticos',
        'Ingeniería Industrial',
        'Ingeniería Civil',
        'Ingeniería Eléctrica',
        'Ingeniería Mecánica',
        'Ingeniería Química',
        'Ingeniería en Telecomunicaciones',
        'Ingeniería en Ciencias de la Computación',
        'Ingeniería Biomédica',
        'Ingeniería Agronómica',

        // Ciencias de la Salud
        'Medicina',
        'Odontología',
        'Enfermería',
        'Fisioterapia',
        'Nutrición',
        'Farmacia',
        'Laboratorio Clínico',

        // Ciencias Económicas y Empresariales
        'Administración de Empresas',
        'Contaduría Pública',
        'Economía',
        'Mercadeo y Negocios Internacionales',
        'Finanzas',
        'Comercio Internacional',

        // Ciencias Jurídicas y Sociales
        'Licenciatura en Ciencias Jurídicas',
        'Trabajo Social',
        'Relaciones Internacionales',
        'Ciencias Políticas',
        'Sociología',

        // Comunicación y Artes
        'Comunicaciones',
        'Periodismo',
        'Publicidad',
        'Diseño Gráfico',
        'Arquitectura',
        'Artes Plásticas',

        // Educación
        'Licenciatura en Educación',
        'Educación Parvularia',
        'Educación Básica',
        'Idioma Inglés',

        // Ciencias Naturales
        'Biología',
        'Química',
        'Física',
        'Matemática',

        // Psicología y Humanidades
        'Psicología',
        'Filosofía',
        'Historia',
        'Letras',

        // Turismo y Hotelería
        'Administración Turística y Hotelera',
        'Gastronomía',
    ];

    echo "Insertando universidades...\n";
    $universidadesInsertadas = [];

    foreach ($universidades as $nombreUniversidad) {
        $universidad = Universidad::firstOrCreate(
            ['nombre' => $nombreUniversidad]
        );
        $universidadesInsertadas[] = $universidad;
        echo "  ✓ {$nombreUniversidad}\n";
    }

    echo "\nInsertando carreras...\n";
    $carrerasInsertadas = [];

    foreach ($carreras as $nombreCarrera) {
        $carrera = Carrera::firstOrCreate(
            ['nombre' => $nombreCarrera]
        );
        $carrerasInsertadas[] = $carrera;
        echo "  ✓ {$nombreCarrera}\n";
    }

    // Crear relaciones entre universidades y carreras
    echo "\nCreando relaciones universidad-carrera...\n";

    // Definir qué carreras tiene cada universidad (simplificado)
    // En la realidad, cada universidad tiene un conjunto específico de carreras
    $relacionesEspecificas = [
        'Universidad de El Salvador (UES)' => [
            'Ingeniería en Sistemas Informáticos',
            'Ingeniería Industrial',
            'Ingeniería Civil',
            'Ingeniería Eléctrica',
            'Ingeniería Química',
            'Medicina',
            'Odontología',
            'Licenciatura en Ciencias Jurídicas',
            'Economía',
            'Administración de Empresas',
            'Arquitectura',
            'Psicología',
        ],
        'Universidad Centroamericana José Simeón Cañas (UCA)' => [
            'Ingeniería en Sistemas Informáticos',
            'Ingeniería Industrial',
            'Ingeniería Civil',
            'Administración de Empresas',
            'Economía',
            'Licenciatura en Ciencias Jurídicas',
            'Psicología',
            'Comunicaciones',
        ],
        'Universidad Tecnológica de El Salvador (UTEC)' => [
            'Ingeniería en Sistemas Informáticos',
            'Ingeniería en Ciencias de la Computación',
            'Ingeniería Industrial',
            'Arquitectura',
            'Diseño Gráfico',
            'Administración de Empresas',
            'Mercadeo y Negocios Internacionales',
        ],
        'Universidad Don Bosco (UDB)' => [
            'Ingeniería en Sistemas Informáticos',
            'Ingeniería en Ciencias de la Computación',
            'Ingeniería en Telecomunicaciones',
            'Arquitectura',
            'Diseño Gráfico',
            'Administración de Empresas',
        ],
    ];

    $relacionesCount = 0;

    foreach ($universidadesInsertadas as $universidad) {
        // Si la universidad tiene relaciones específicas, usarlas
        if (isset($relacionesEspecificas[$universidad->nombre])) {
            $carrerasDeUniversidad = $relacionesEspecificas[$universidad->nombre];

            foreach ($carrerasDeUniversidad as $nombreCarrera) {
                $carrera = Carrera::where('nombre', $nombreCarrera)->first();
                if ($carrera) {
                    // Verificar si la relación ya existe
                    if (!$universidad->carreras()->where('carrera_id', $carrera->id)->exists()) {
                        $universidad->carreras()->attach($carrera->id);
                        $relacionesCount++;
                    }
                }
            }
        } else {
            // Para las demás universidades, asignar un conjunto aleatorio de carreras
            $carrerasAleatorias = collect($carrerasInsertadas)
                ->random(min(8, count($carrerasInsertadas)))
                ->pluck('id')
                ->toArray();

            foreach ($carrerasAleatorias as $carreraId) {
                if (!$universidad->carreras()->where('carrera_id', $carreraId)->exists()) {
                    $universidad->carreras()->attach($carreraId);
                    $relacionesCount++;
                }
            }
        }

        echo "  ✓ {$universidad->nombre}: " . $universidad->carreras()->count() . " carreras\n";
    }

    DB::commit();

    echo "\n====================================\n";
    echo "✅ PROCESO COMPLETADO EXITOSAMENTE\n";
    echo "====================================\n";
    echo "Universidades insertadas: " . count($universidadesInsertadas) . "\n";
    echo "Carreras insertadas: " . count($carrerasInsertadas) . "\n";
    echo "Relaciones creadas: " . $relacionesCount . "\n";
    echo "\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    exit(1);
}
