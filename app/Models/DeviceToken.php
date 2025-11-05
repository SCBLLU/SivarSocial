<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_token',
        'platform',
        'last_used_at'
    ];

    protected $casts = [
        'last_used_at' => 'datetime'
    ];

    /**
     * Relación con el modelo User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
