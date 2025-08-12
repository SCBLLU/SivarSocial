<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'comentario',
    ];

    // para traer los datos del usuario y del post de quien es el comentario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Formato compacto de tiempo transcurrido para comentarios
     */
    public function getCompactTimeAttribute()
    {
        $diff = $this->created_at->diffInSeconds(now());

        if ($diff < 60) {
            return 'ahora';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes == 1 ? '1 min' : $minutes . ' mins';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours == 1 ? '1 hora' : $hours . ' horas';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days == 1 ? '1 día' : $days . ' días';
        } elseif ($diff < 2629746) {
            $weeks = floor($diff / 604800);
            return $weeks == 1 ? '1 semana' : $weeks . ' semanas';
        } elseif ($diff < 31556952) { // Un año en segundos
            $months = floor($diff / 2629746);
            return $months == 1 ? '1 mes' : $months . ' meses';
        } else {
            $years = floor($diff / 31556952);
            return $years == 1 ? '1 año' : $years . ' años';
        }
    }

    /**
     * Formato completo de tiempo transcurrido para comentarios (para tooltips o vistas detalladas)
     */
    public function getFullTimeAttribute()
    {
        $diff = $this->created_at->diffInSeconds(now());

        if ($diff < 60) {
            return 'Comentado ahora mismo';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes == 1 ? 'Comentado hace 1 minuto' : "Comentado hace {$minutes} minutos";
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours == 1 ? 'Comentado hace 1 hora' : "Comentado hace {$hours} horas";
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days == 1 ? 'Comentado hace 1 día' : "Comentado hace {$days} días";
        } elseif ($diff < 2629746) {
            $weeks = floor($diff / 604800);
            return $weeks == 1 ? 'Comentado hace 1 semana' : "Comentado hace {$weeks} semanas";
        } elseif ($diff < 31556952) {
            $months = floor($diff / 2629746);
            return $months == 1 ? 'Comentado hace 1 mes' : "Comentado hace {$months} meses";
        } else {
            $years = floor($diff / 31556952);
            return $years == 1 ? 'Comentado hace 1 año' : "Comentado hace {$years} años";
        }
    }
}
