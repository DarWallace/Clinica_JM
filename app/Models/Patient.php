<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <--- Importante añadir esto

class Patient extends Model
{
    // 1. Definimos la clave primaria personalizada
    protected $primaryKey = 'user_id';

    // 2. Indicamos que no es autoincremental (el ID viene heredado de User)
    public $incrementing = false;

    protected $fillable = ['user_id', 'born_date', 'medical_history'];

    public function user(): BelongsTo
    {
        // Relacionamos el user_id del paciente con el id del usuario
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
