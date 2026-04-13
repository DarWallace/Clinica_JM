<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specialist extends Model
{
   // 1. Dile a Laravel que la PK no es 'id'
    protected $primaryKey = 'user_id';

    // 2. Si no es autoincremental (porque viene de users), desactívalo
    public $incrementing = false;

    protected $fillable = ['user_id', 'speciality', 'active'];

    public function user(): BelongsTo
    {
        // El orden es: Modelo, llave_foranea, llave_local
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
