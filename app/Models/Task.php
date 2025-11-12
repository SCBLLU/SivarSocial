<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid_cliente',
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'completed_at',
        'estimated_pomodoros',
        'completed_pomodoros',
        'synced_at',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'synced_at' => 'datetime',
        'estimated_pomodoros' => 'integer',
        'completed_pomodoros' => 'integer',
    ];

    /**
     * Relación con el usuario dueño de la tarea
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con las sesiones de pomodoro
     */
    public function pomodoroSessions()
    {
        return $this->hasMany(PomodoroSession::class);
    }

    /**
     * Scope para tareas pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para tareas en progreso
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope para tareas completadas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope para ordenar por prioridad
     */
    public function scopeOrderByPriority($query)
    {
        return $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
    }

    /**
     * Scope para ordenar por fecha de vencimiento
     */
    public function scopeOrderByDueDate($query)
    {
        return $query->orderBy('due_date', 'asc');
    }

    /**
     * Marcar tarea como completada
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Incrementar contador de pomodoros completados
     */
    public function incrementPomodoros()
    {
        $this->increment('completed_pomodoros');

        // Actualizar estado a in_progress si está pending
        if ($this->status === 'pending') {
            $this->update(['status' => 'in_progress']);
        }
    }
}
