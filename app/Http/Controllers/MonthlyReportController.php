<?php

namespace App\Http\Controllers;

use App\Models\PlannedTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Inertia\Inertia;

class MonthlyReportController extends Controller
{
    public function __invoke(?int $year = null, ?int $month = null)
    {
        $userId = auth()->id();

        $date = Carbon::create(
            $year ?? now()->year,
            $month ?? now()->month,
            1
        );

        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        $plannedTransactions = PlannedTransaction::with('category')
            ->where('user_id', $userId)
            ->whereNull('created_transaction_id')
            ->whereBetween('due_date', [$start, $end])
            ->orderBy('due_date')
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');

        $plannedIncome = $plannedTransactions->where('type', 'income')->sum('amount');
        $plannedExpenses = $plannedTransactions->where('type', 'expense')->sum('amount');

        return Inertia::render('Reports/Month', [
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
        ]);
    }
}
