<?php

namespace App\Services\Finance;

use App\Models\PlannedTransaction;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class ReportService
{
    public function month(User $user, ?int $year = null, ?int $month = null): array
    {
        $date = Carbon::create(
            $year ?? now()->year,
            $month ?? now()->month,
            1
        );

        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        $transactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        $plannedTransactions = PlannedTransaction::with('category')
            ->where('user_id', $user->id)
            ->whereNull('created_transaction_id')
            ->whereBetween('due_date', [$start, $end])
            ->orderBy('due_date')
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');

        $plannedIncome = $plannedTransactions->where('type', 'income')->sum('amount');
        $plannedExpenses = $plannedTransactions->where('type', 'expense')->sum('amount');

        return [
            'month' => [
                'year' => $date->year,
                'month' => $date->month,
                'label' => $date->translatedFormat('F Y'),
                'previous' => [
                    'year' => $date->copy()->subMonth()->year,
                    'month' => $date->copy()->subMonth()->month,
                ],
                'next' => [
                    'year' => $date->copy()->addMonth()->year,
                    'month' => $date->copy()->addMonth()->month,
                ],
            ],

            'summary' => [
                'income' => $income,
                'expenses' => $expenses,
                'plannedIncome' => $plannedIncome,
                'plannedExpenses' => $plannedExpenses,
                'balance' => $income - $expenses,
                'forecast' => $income - $expenses + $plannedIncome - $plannedExpenses,
            ],

            'transactions' => $transactions,
            'plannedTransactions' => $plannedTransactions,
        ];
    }

    public function year(User $user, ?int $year = null): array
    {
        $year = $year ?? now()->year;

        $months = collect();

        for ($month = 1; $month <= 12; $month++) {
            $monthData = $this->month($user, $year, $month);

            $months->push([
                'month' => $monthData['month']['label'],
                ...$monthData['summary'],
            ]);
        }

        return [
            'year' => $year,
            'previousYear' => $year - 1,
            'nextYear' => $year + 1,
            'months' => $months,
        ];
    }
}
