<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerMonthNote extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'content',
    ];
}
