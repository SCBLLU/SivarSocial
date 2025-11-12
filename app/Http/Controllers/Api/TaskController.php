<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Listar todas las tareas del usuario autenticado
     */
    public function index(Request $request)
    {
        $query = $request->user()->tasks();

        // Filtros opcionales
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->boolean('overdue')) {
            $query->where('due_date', '<', now())
                ->whereIn('status', ['pending', 'in_progress']);
        }

        // Ordenamiento
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($sortBy === 'priority') {
            $query->orderByPriority();
        } elseif ($sortBy === 'due_date') {
            $query->orderByDueDate();
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $tasks = $query->with('pomodoroSessions')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $tasks,
        ], 200);
    }

    /**
     * Crear una nueva tarea
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'uuid_cliente' => 'nullable|uuid',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high'])],
            'due_date' => 'nullable|date',
            'estimated_pomodoros' => 'nullable|integer|min:1',
        ]);

        // Generar UUID si no viene del cliente
        if (!isset($validated['uuid_cliente'])) {
            $validated['uuid_cliente'] = (string) Str::uuid();
        }

        $validated['user_id'] = $request->user()->id;

        $task = Task::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tarea creada exitosamente',
            'data' => $task,
        ], 201);
    }

    /**
     * Ver una tarea específica
     */
    public function show(Request $request, Task $task)
    {
        // Verificar que la tarea pertenezca al usuario
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver esta tarea',
            ], 403);
        }

        $task->load('pomodoroSessions');

        return response()->json([
            'success' => true,
            'data' => $task,
        ], 200);
    }

    /**
     * Actualizar una tarea
     */
    public function update(Request $request, Task $task)
    {
        // Verificar que la tarea pertenezca al usuario
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para actualizar esta tarea',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['sometimes', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
            'priority' => ['sometimes', Rule::in(['low', 'medium', 'high'])],
            'due_date' => 'nullable|date',
            'estimated_pomodoros' => 'nullable|integer|min:1',
        ]);

        // Si se marca como completada, agregar timestamp
        if (isset($validated['status']) && $validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        }

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada exitosamente',
            'data' => $task->fresh(),
        ], 200);
    }

    /**
     * Eliminar una tarea
     */
    public function destroy(Request $request, Task $task)
    {
        // Verificar que la tarea pertenezca al usuario
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar esta tarea',
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarea eliminada exitosamente',
        ], 200);
    }

    /**
     * Obtener estadísticas de tareas del usuario
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total' => $user->tasks()->count(),
            'pending' => $user->tasks()->pending()->count(),
            'in_progress' => $user->tasks()->inProgress()->count(),
            'completed' => $user->tasks()->completed()->count(),
            'overdue' => $user->tasks()
                ->where('due_date', '<', now())
                ->whereIn('status', ['pending', 'in_progress'])
                ->count(),
            'total_pomodoros' => $user->pomodoroSessions()->completed()->count(),
            'pomodoros_today' => $user->pomodoroSessions()->completed()->today()->count(),
            'pomodoros_this_week' => $user->pomodoroSessions()->completed()->thisWeek()->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ], 200);
    }
}
