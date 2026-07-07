<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannedTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'created_transaction_id',
        'amount',
        'due_date',
        'description',
        'repeat_type',
        'repeat_day',
        'repeat_until',
        'parent_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'repeat_until' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function createdTransaction()
    {
        return $this->belongsTo(Transaction::class, 'created_transaction_id');
    }
}
