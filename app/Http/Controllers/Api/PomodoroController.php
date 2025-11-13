<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PomodoroSession;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class PomodoroController extends Controller
{
    /**
     * Iniciar una nueva sesión de Pomodoro
     */
    public function start(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'nullable|exists:tasks,id',
            'uuid_cliente' => 'nullable|uuid',
        ]);

        // Generar UUID si no viene del cliente
        $uuid = $validated['uuid_cliente'] ?? (string) Str::uuid();

        // Verificar que la tarea pertenezca al usuario si se proporciona
        if (isset($validated['task_id'])) {
            $task = Task::find($validated['task_id']);
            if ($task && $task->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para usar esta tarea',
                ], 403);
            }
        }

        // Evitar duplicados: si ya existe una sesión running con el mismo uuid_cliente
        $existing = PomodoroSession::where('uuid_cliente', $uuid)
            ->where('status', 'running')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Sesión ya existente',
                'data' => $existing->load('task'),
            ], 200);
        }

        // Verificar si el usuario ya tiene una sesión activa
        $activeSession = PomodoroSession::where('user_id', $request->user()->id)
            ->where('status', 'running')
            ->first();

        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes una sesión de Pomodoro activa',
                'data' => $activeSession->load('task'),
            ], 409);
        }

        // Crear nueva sesión
        $session = PomodoroSession::create([
            'uuid_cliente' => $uuid,
            'user_id' => $request->user()->id,
            'task_id' => $validated['task_id'] ?? null,
            'started_at' => now(),
            'status' => 'running',
        ]);

        // Actualizar estado de la tarea si está asociada
        if ($session->task_id) {
            $task = $session->task;
            if ($task->status === 'pending') {
                $task->update(['status' => 'in_progress']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sesión de Pomodoro iniciada',
            'data' => $session->load('task'),
        ], 201);
    }

    /**
     * Completar una sesión de Pomodoro
     */
    public function complete(Request $request)
    {
        $validated = $request->validate([
            'session_uuid' => 'required|uuid',
            'actual_duration_seconds' => 'required|integer|min:1',
            'break_type' => ['nullable', Rule::in(['none', 'short', 'long'])],
        ]);

        $session = PomodoroSession::where('uuid_cliente', $validated['session_uuid'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'running')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada o ya completada',
            ], 404);
        }

        // Actualizar la sesión
        $session->update([
            'ended_at' => now(),
            'duration_seconds' => $validated['actual_duration_seconds'],
            'break_type' => $validated['break_type'] ?? 'none',
            'status' => 'completed',
        ]);

        // Incrementar contador en la tarea si está asociada
        if ($session->task_id) {
            $session->task->incrementPomodoros();
        }

        // Aquí puedes agregar lógica para enviar notificaciones push
        // $this->sendPomodoroCompletedNotification($session);

        return response()->json([
            'success' => true,
            'message' => 'Sesión de Pomodoro completada',
            'data' => $session->load('task'),
        ], 200);
    }

    /**
     * Cancelar una sesión de Pomodoro
     */
    public function cancel(Request $request)
    {
        $validated = $request->validate([
            'session_uuid' => 'required|uuid',
        ]);

        $session = PomodoroSession::where('uuid_cliente', $validated['session_uuid'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'running')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesión no encontrada o ya finalizada',
            ], 404);
        }

        $session->cancel();

        return response()->json([
            'success' => true,
            'message' => 'Sesión de Pomodoro cancelada',
            'data' => $session,
        ], 200);
    }

    /**
     * Listar sesiones de Pomodoro del usuario
     */
    public function index(Request $request)
    {
        $query = $request->user()->pomodoroSessions();

        // Filtros opcionales
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        if ($request->has('period')) {
            switch ($request->period) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'month':
                    $query->thisMonth();
                    break;
            }
        }

        $sessions = $query->with('task')
            ->orderBy('started_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $sessions,
        ], 200);
    }

    /**
     * Obtener sesión activa del usuario
     */
    public function active(Request $request)
    {
        $session = PomodoroSession::where('user_id', $request->user()->id)
            ->where('status', 'running')
            ->with('task')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'No hay sesión activa',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $session,
        ], 200);
    }

    /**
     * Obtener estadísticas de Pomodoro
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_sessions' => $user->pomodoroSessions()->completed()->count(),
            'today' => $user->pomodoroSessions()->completed()->today()->count(),
            'this_week' => $user->pomodoroSessions()->completed()->thisWeek()->count(),
            'this_month' => $user->pomodoroSessions()->completed()->thisMonth()->count(),
            'total_minutes' => round($user->pomodoroSessions()
                ->completed()
                ->sum('duration_seconds') / 60),
            'average_session_minutes' => round($user->pomodoroSessions()
                ->completed()
                ->avg('duration_seconds') / 60, 2),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ], 200);
    }

    /**
     * Leaderboard: usuarios con más pomodoros en un periodo (week|month|custom)
     */
    public function leaderboard(Request $request)
    {
        $period = $request->query('period', 'week');

        if ($period === 'week') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
        } elseif ($period === 'month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } elseif ($period === 'custom' && $request->has(['from', 'to'])) {
            $start = Carbon::parse($request->query('from'));
            $end = Carbon::parse($request->query('to'));
        } else {
            // por defecto últimos 7 días
            $start = Carbon::now()->subDays(7);
            $end = Carbon::now();
        }

        $rows = DB::table('pomodoro_sessions as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->select('u.id as user_id', 'u.name', 'u.email', 'u.imagen as imagen', DB::raw('COUNT(*) as pomodoros'))
            ->where('p.status', 'completed')
            ->whereBetween('p.started_at', [$start->toDateTimeString(), $end->toDateTimeString()])
            ->groupBy('u.id', 'u.name', 'u.email', 'u.imagen')
            ->orderByDesc('pomodoros')
            ->limit(20)
            ->get();

        $host = request()->getSchemeAndHttpHost();

        $payload = $rows->map(function ($r) use ($host) {
            $url = $r->imagen ? $host . '/perfiles/' . $r->imagen : 'https://www.gravatar.com/avatar/?d=mp&f=y';

            return [
                'id' => $r->user_id,
                'name' => $r->name,
                'email' => $r->email,
                'imagen' => $r->imagen,
                'avatar' => $url,
                'avatar_url' => $url,
                'imagen_url' => $url,
                'pomodoros' => (int) $r->pomodoros,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $payload,
        ], 200);
    }
}
