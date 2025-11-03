<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function universidades()
    {
        return $this->belongsToMany(Universidad::class, 'carrera_universidades');
    }
}
