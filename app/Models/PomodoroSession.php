<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PomodoroSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid_cliente',
        'user_id',
        'task_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'break_type',
        'status',
        'synced_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'synced_at' => 'datetime',
        'duration_seconds' => 'integer',
    ];

    /**
     * Relación con el usuario dueño de la sesión
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la tarea asociada
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Scope para sesiones en ejecución
     */
    public function scopeRunning($query)
    {
        return $query->where('status', 'running');
    }

    /**
     * Scope para sesiones completadas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope para sesiones de hoy
     */
    public function scopeToday($query)
    {
        return $query->whereDate('started_at', today());
    }

    /**
     * Scope para sesiones de esta semana
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('started_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope para sesiones de este mes
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('started_at', now()->month)
            ->whereYear('started_at', now()->year);
    }

    /**
     * Marcar sesión como completada
     */
    public function complete($actualDurationSeconds)
    {
        $this->update([
            'ended_at' => now(),
            'duration_seconds' => $actualDurationSeconds,
            'status' => 'completed',
        ]);

        // Incrementar contador en la tarea si está asociada
        if ($this->task_id) {
            $this->task->incrementPomodoros();
        }
    }

    /**
     * Marcar sesión como cancelada
     */
    public function cancel()
    {
        $this->update([
            'ended_at' => now(),
            'status' => 'cancelled',
        ]);
    }
}
