<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'specialist_id', // <--- Añade esto
        'name',
        'description',
        'duration',
        'buffer_minutes',
        'price',
        'type',
        'max_patients',
        'active'
    ];
    public function specialist()
    {
        return $this->belongsTo(\App\Models\Specialist::class, 'specialist_id', 'user_id');
    }
}
