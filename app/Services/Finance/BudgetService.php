<?php

namespace App\Services\Finance;

use App\Models\Budget;
use App\Models\PlannedTransaction;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class BudgetService
{
    public function overview(User $user, ?Carbon $date = null)
    {
        $date = $date ?? now();

        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        return Budget::with('category')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($budget) use ($user, $start, $end) {
                $spentQuery = Transaction::where('user_id', $user->id)
                    ->where('type', 'expense')
                    ->whereBetween('date', [$start, $end]);

                $plannedQuery = PlannedTransaction::where('user_id', $user->id)
                    ->whereNull('created_transaction_id')
                    ->where('type', 'expense')
                    ->whereBetween('due_date', [$start, $end]);

                if ($budget->category_id) {
                    $spentQuery->where('category_id', $budget->category_id);
                    $plannedQuery->where('category_id', $budget->category_id);
                }

                $spent = $spentQuery->sum('amount');
                $planned = $plannedQuery->sum('amount');
                $forecast = $spent + $planned;
                $remaining = $budget->amount - $forecast;
                $progress = $budget->amount > 0
                    ? min(100, round(($forecast / $budget->amount) * 100))
                    : 0;

                return [
                    'id' => $budget->id,
                    'category' => $budget->category,
                    'amount' => $budget->amount,
                    'month' => $budget->month,
                    'year' => $budget->year,
                    'spent' => $spent,
                    'planned' => $planned,
                    'forecast' => $forecast,
                    'remaining' => $remaining,
                    'progress' => $progress,
                    'is_over' => $forecast > $budget->amount,
                ];
            });
    }
}
