<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$start = Carbon::now()->startOfWeek();
$end = Carbon::now()->endOfWeek();

$rows = DB::table('pomodoro_sessions as p')
    ->join('users as u', 'u.id', '=', 'p.user_id')
    ->select('u.id as user_id', 'u.name', 'u.email', 'u.imagen as imagen', DB::raw('COUNT(*) as pomodoros'))
    ->where('p.status', 'completed')
    ->whereBetween('p.started_at', [$start->toDateTimeString(), $end->toDateTimeString()])
    ->groupBy('u.id', 'u.name', 'u.email', 'u.imagen')
    ->orderByDesc('pomodoros')
    ->limit(10)
    ->get();
// Mapear payload igual que el endpoint
$payload = $rows->map(function ($r) {
    return [
        'id' => $r->user_id,
        'name' => $r->name,
        'email' => $r->email,
        'avatar' => $r->imagen ? url('perfiles/' . $r->imagen) : 'https://www.gravatar.com/avatar/?d=mp&f=y',
        'pomodoros' => (int) $r->pomodoros,
    ];
});

echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
