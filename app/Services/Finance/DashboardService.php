<?php

namespace App\Services\Finance;

use App\Models\PlannedTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function __construct(private BudgetService $budgetService)
    {
        //
    }

    public function build(User $user): array
    {
        $userId = $user->id;

        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$start, $end])
            ->sum('amount');

        $expenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$start, $end])
            ->sum('amount');

        // Kumulierter Saldo über alle Monate hinweg (wie ein echtes Konto),
        // im Gegensatz zu $income/$expenses, die nur den laufenden Monat zeigen.
        $allTimeIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        $allTimeExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        $plannedExpenses = PlannedTransaction::where('user_id', $userId)
            ->whereNull('created_transaction_id')
            ->where('type', 'expense')
            ->whereBetween('due_date', [$start, $end])
            ->sum('amount');

        $plannedIncome = PlannedTransaction::where('user_id', $userId)
            ->whereNull('created_transaction_id')
            ->where('type', 'income')
            ->whereBetween('due_date', [$start, $end])
            ->sum('amount');

        // Only the still-unspent, un-planned portion of each budget (never
        // negative — an over-budget category is already reflected in
        // $expenses above, so it must not also reduce the forecast twice).
        $budgetReserve = $this->budgetService->overview($user)
            ->sum(fn ($budget) => max(0, $budget['remaining']));

        $balance = $allTimeIncome - $allTimeExpenses;
        $forecast = $balance + $plannedIncome - $plannedExpenses - $budgetReserve;

        return [
            'month' => now()->translatedFormat('F Y'),

            'summary' => [
                'income' => $income,
                'expenses' => $expenses,
                'balance' => $balance,
                'plannedExpenses' => $plannedExpenses,
                'plannedIncome' => $plannedIncome,
                'budgetReserve' => $budgetReserve,
                'forecast' => $forecast,
            ],

            'latestTransactions' => Transaction::with('category')
                ->where('user_id', $userId)
                ->latest('date')
                ->latest('id')
                ->limit(5)
                ->get(),

            'topCategories' => Transaction::query()
                ->select('category_id', DB::raw('SUM(amount) as total'))
                ->with('category')
                ->where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$start, $end])
                ->groupBy('category_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),

            'nextPlannedTransactions' => PlannedTransaction::with('category')
                ->where('user_id', $userId)
                ->whereNull('created_transaction_id')
                ->whereBetween('due_date', [$start, $end])
                ->orderBy('due_date')
                ->limit(5)
                ->get(),
        ];
    }
}
