<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
   public function cite()
{
    return $this->belongsTo(Cite::class, 'cite_id');
}

public function patient()
{
    return $this->belongsTo(Patient::class, 'patient_id');
}
}
