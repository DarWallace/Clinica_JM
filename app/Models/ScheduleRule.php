<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleRule extends Model
{
    protected $fillable = [
        'service_id',
        'room_id',
        'day_of_week',
        'start_time',
        'end_time',
        'valid_from',
        'valid_until',
    ];

    // Casteamos las fechas y horas para que Laravel las maneje como objetos
    protected $casts = [
       // 'start_time' => 'datetime:H:i',
       // 'end_time' => 'datetime:H:i',
       'day_of_week' => 'integer',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
