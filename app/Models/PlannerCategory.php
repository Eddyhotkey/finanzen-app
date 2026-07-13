<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerCategory extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'color',
        'sort_order',
    ];

    public function tasks()
    {
        return $this->hasMany(PlannerTask::class);
    }
}
