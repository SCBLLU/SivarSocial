<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Follower extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'follower_id',
    ];
}
