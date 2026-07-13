<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerTask extends Model
{
    protected $fillable = [
        'user_id',
        'planner_category_id',
        'title',
        'description',
        'date',
        'priority',
        'is_done',
        'notified_at',
    ];

    protected $casts = [
        'date' => 'date',
        'is_done' => 'boolean',
        'notified_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(PlannerCategory::class, 'planner_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
