<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\PomodoroSession;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PomodoroSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalCreated = 0;
        $todayCreated = 0;

        // Para cada usuario, generamos entre 5 y 15 sesiones completadas en los últimos 30 días
        $users = User::all();

        foreach ($users as $user) {
            $taskIds = $user->tasks()->pluck('id')->toArray();
            $sessionsToCreate = rand(5, 15);

            for ($i = 0; $i < $sessionsToCreate; $i++) {
                // Fecha aleatoria en los últimos 30 días, entre 6:00 y 22:59
                $started = Carbon::now()->subDays(rand(0, 30))
                    ->setTime(rand(6, 22), rand(0, 59), 0);

                $duration = rand(1200, 1800); // entre 20 y 30 minutos (en segundos)
                $ended = (clone $started)->addSeconds($duration);

                $taskId = null;
                if (!empty($taskIds) && rand(0, 100) < 80) { // 80% de probabilidad de asociarlo a una tarea
                    $taskId = $taskIds[array_rand($taskIds)];
                }

                PomodoroSession::create([
                    'uuid_cliente' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'task_id' => $taskId,
                    'started_at' => $started,
                    'ended_at' => $ended,
                    'duration_seconds' => $duration,
                    'break_type' => (rand(0, 100) < 70) ? 'short' : 'none',
                    'status' => 'completed',
                    'synced_at' => now(),
                ]);

                // Si la sesión está asociada a una tarea, incrementar su contador
                if ($taskId) {
                    $task = Task::find($taskId);
                    if ($task) {
                        $task->incrementPomodoros();
                    }
                }

                $totalCreated++;
            }

            // Además generar algunas sesiones específicamente para HOY
            $todaySessions = rand(0, 5); // entre 0 y 5 sesiones hoy por usuario

            for ($j = 0; $j < $todaySessions; $j++) {
                $startedToday = Carbon::today()->setTime(rand(6, 22), rand(0, 59), 0)->addMinutes(rand(0, 59));
                $durationToday = rand(1200, 1800);
                $endedToday = (clone $startedToday)->addSeconds($durationToday);

                $taskIdToday = null;
                if (!empty($taskIds) && rand(0, 100) < 80) {
                    $taskIdToday = $taskIds[array_rand($taskIds)];
                }

                PomodoroSession::create([
                    'uuid_cliente' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'task_id' => $taskIdToday,
                    'started_at' => $startedToday,
                    'ended_at' => $endedToday,
                    'duration_seconds' => $durationToday,
                    'break_type' => (rand(0, 100) < 70) ? 'short' : 'none',
                    'status' => 'completed',
                    'synced_at' => now(),
                ]);

                if ($taskIdToday) {
                    $task = Task::find($taskIdToday);
                    if ($task) {
                        $task->incrementPomodoros();
                    }
                }

                $totalCreated++;
                $todayCreated++;
            }
        }

        $this->command->info("✅ PomodoroSessionSeeder: creadas $totalCreated sesiones de pomodoro. De ellas $todayCreated son de hoy.");
    }
}
