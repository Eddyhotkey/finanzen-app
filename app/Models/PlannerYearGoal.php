<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerYearGoal extends Model
{
    protected $fillable = [
        'user_id',
        'category_label',
        'title',
        'note',
        'status',
        'progress',
        'year',
    ];

    protected $casts = [
        'progress' => 'integer',
        'year' => 'integer',
    ];
}
