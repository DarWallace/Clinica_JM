<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cite extends Model
{
    protected $fillable = [
        'service_id',
        'room_id',
        'schedule_rule_id',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'cite_id');
    }
}
