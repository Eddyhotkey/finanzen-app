<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerSpecialPeriod extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'tag',
        'start_date',
        'end_date',
        'color',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
