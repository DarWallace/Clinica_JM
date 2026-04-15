<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cite extends Model
{
    protected $fillable = ['service_id', 'room_id', 'schedule_rule_id', 'date', 'start_time', 'end_time', 'status'];

    public function service() { return $this->belongsTo(Service::class); }
}
