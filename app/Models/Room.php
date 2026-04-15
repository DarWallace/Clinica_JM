<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = ['name', 'type', 'capacity'];
    public function scheduleRules(): HasMany
    {
        return $this->hasMany(ScheduleRule::class);
    }
}
