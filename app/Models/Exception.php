<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exception extends Model
{
    protected $fillable = [
        'specialist_id',
        'applies_to_all',
        'start_datetime',
        'end_datetime',
        'reason',
    ];

    protected $casts = [
        'applies_to_all' => 'boolean',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    public function specialist()
    {
        return $this->belongsTo(User::class, 'specialist_id');
    }

    public function specialists()
    {
        return $this->belongsToMany(
            User::class,
            'exception_specialist',
            'exception_id',
            'specialist_user_id'
        );
    }
}
