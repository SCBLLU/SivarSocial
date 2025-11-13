<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Contracts\Console\Kernel;
use App\Models\User;
use App\Models\Task;
use App\Models\PomodoroSession;
use Carbon\Carbon;

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$email = 'mateocanalespinto@gmail.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "Usuario con email $email no encontrado." . PHP_EOL;
    exit(1);
}

$task = $user->tasks()->first();
if (!$task) {
    echo "Usuario encontrado (id={$user->id}), pero no tiene tareas." . PHP_EOL;
    exit(1);
}

$now = Carbon::now();
$duration = 25 * 60; // 25 minutos por defecto

$session = PomodoroSession::create([
    'uuid_cliente' => (string) \Illuminate\Support\Str::uuid(),
    'user_id' => $user->id,
    'task_id' => $task->id,
    'started_at' => $now,
    'ended_at' => (clone $now)->addSeconds($duration),
    'duration_seconds' => $duration,
    'break_type' => 'short',
    'status' => 'completed',
    'synced_at' => now(),
]);

// Incrementar contador en la tarea
$task->incrementPomodoros();

echo "Sesión creada: id={$session->id}, user_id={$session->user_id}, task_id={$session->task_id}, started_at={$session->started_at}" . PHP_EOL;

exit(0);
