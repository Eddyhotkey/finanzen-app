<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerWeekNote extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'week_number',
        'content',
    ];
}
