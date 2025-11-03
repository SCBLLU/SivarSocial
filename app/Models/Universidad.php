<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    protected $table = 'universidades';

    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'carrera_universidades');
    }
}
