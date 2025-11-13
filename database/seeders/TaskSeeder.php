<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el primer usuario para crear tareas de ejemplo
        $user = User::first();

        if (!$user) {
            $this->command->error('No hay usuarios en la base de datos. Crea un usuario primero.');
            return;
        }

        $tasks = [
            [
                'uuid_cliente' => (string) Str::uuid(),
                'user_id' => $user->id,
                'title' => 'Estudiar para examen de Cálculo',
                'description' => 'Repasar capítulos 5-7: Integrales y derivadas',
                'status' => 'pending',
                'priority' => 'high',
                'due_date' => now()->addDays(3),
                'estimated_pomodoros' => 4,
                'completed_pomodoros' => 0,
            ],
            [
                'uuid_cliente' => (string) Str::uuid(),
                'user_id' => $user->id,
                'title' => 'Proyecto de Programación Web',
                'description' => 'Desarrollar sistema de ToDo con Pomodoro',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => now()->addWeek(),
                'estimated_pomodoros' => 8,
                'completed_pomodoros' => 3,
            ],
            [
                'uuid_cliente' => (string) Str::uuid(),
                'user_id' => $user->id,
                'title' => 'Leer capítulo 3 de Física',
                'description' => 'Mecánica cuántica - fundamentos básicos',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => now()->addDays(5),
                'estimated_pomodoros' => 2,
                'completed_pomodoros' => 0,
            ],
            [
                'uuid_cliente' => (string) Str::uuid(),
                'user_id' => $user->id,
                'title' => 'Hacer ejercicios de Álgebra',
                'description' => 'Resolver problemas 1-20 del libro',
                'status' => 'completed',
                'priority' => 'medium',
                'due_date' => now()->subDays(1),
                'estimated_pomodoros' => 3,
                'completed_pomodoros' => 3,
                'completed_at' => now()->subHours(5),
            ],
            [
                'uuid_cliente' => (string) Str::uuid(),
                'user_id' => $user->id,
                'title' => 'Investigar sobre Laravel',
                'description' => 'Aprender sobre Eloquent ORM y relaciones',
                'status' => 'in_progress',
                'priority' => 'low',
                'due_date' => now()->addDays(10),
                'estimated_pomodoros' => 5,
                'completed_pomodoros' => 1,
            ],
            [
                'uuid_cliente' => (string) Str::uuid(),
                'user_id' => $user->id,
                'title' => 'Revisar apuntes de Historia',
                'description' => 'Segunda Guerra Mundial - Causas y consecuencias',
                'status' => 'pending',
                'priority' => 'low',
                'due_date' => now()->addDays(7),
                'estimated_pomodoros' => 2,
                'completed_pomodoros' => 0,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }

        $this->command->info('✅ Tareas de ejemplo creadas exitosamente!');
        $this->command->info('   Total: ' . count($tasks) . ' tareas para el usuario: ' . $user->name);
    }
}
