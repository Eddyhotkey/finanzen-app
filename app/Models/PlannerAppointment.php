<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerAppointment extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'date',
        'start_time',
        'end_time',
        'notified_at',
    ];

    protected $casts = [
        'date' => 'date',
        'notified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
