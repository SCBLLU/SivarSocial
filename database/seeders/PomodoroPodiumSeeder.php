<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\PomodoroSession;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PomodoroPodiumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all()->shuffle();
        if ($users->isEmpty()) {
            $this->command->error('No hay usuarios en la base de datos. Crea usuarios primero.');
            return;
        }

        $totalCreated = 0;

        // Distribución: top / medio / bajo
        $topCount = min(5, $users->count());
        $mediumCount = min(12, max(0, $users->count() - $topCount));
        $restCount = $users->count() - $topCount - $mediumCount;

        $index = 0;
        foreach ($users as $user) {
            if ($index < $topCount) {
                // Top users: muchas sesiones (40-70)
                $sessions = rand(40, 70);
            } elseif ($index < $topCount + $mediumCount) {
                // Medio: sesiones moderadas (12-30)
                $sessions = rand(12, 30);
            } else {
                // Resto: menos sesiones (0-10)
                $sessions = rand(0, 10);
            }

            $taskIds = $user->tasks()->pluck('id')->toArray();

            for ($i = 0; $i < $sessions; $i++) {
                // Distribuir en los últimos 30 días
                $started = Carbon::now()->subDays(rand(0, 30))
                    ->setTime(rand(6, 22), rand(0, 59), 0);
                $duration = rand(1200, 1800); // 20-30 min
                $ended = (clone $started)->addSeconds($duration);

                $taskId = null;
                // Asociar a tarea en el 70% de los casos si el usuario tiene tareas
                if (!empty($taskIds) && rand(1, 100) <= 70) {
                    $taskId = $taskIds[array_rand($taskIds)];
                }

                PomodoroSession::create([
                    'uuid_cliente' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'task_id' => $taskId,
                    'started_at' => $started,
                    'ended_at' => $ended,
                    'duration_seconds' => $duration,
                    'break_type' => (rand(1, 100) <= 70) ? 'short' : 'none',
                    'status' => 'completed',
                    'synced_at' => now(),
                ]);

                if ($taskId) {
                    $task = Task::find($taskId);
                    if ($task) {
                        $task->incrementPomodoros();
                    }
                }

                $totalCreated++;
            }

            $index++;
        }

        $this->command->info("✅ PomodoroPodiumSeeder: creadas $totalCreated sesiones de pomodoro variadas.");
    }
}
