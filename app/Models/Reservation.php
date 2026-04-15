<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'cite_id',
        'patient_id',
        'status',
        'payment_status',
    ];

    public function cite(): BelongsTo
    {
        return $this->belongsTo(Cite::class, 'cite_id', 'id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'user_id');
    }
}
