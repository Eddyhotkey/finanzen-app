<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerDailyNote extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'content',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
