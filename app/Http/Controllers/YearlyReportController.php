<?php

namespace App\Http\Controllers;

use App\Models\PlannedTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Inertia\Inertia;

class YearlyReportController extends Controller
{
    public function __invoke(?int $year = null)
    {
        $userId = auth()->id();
        $year = $year ?? now()->year;

        $months = collect();

        for ($month = 1; $month <= 12; $month++) {

            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            $income = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');

            $expenses = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');

            $plannedIncome = PlannedTransaction::where('user_id', $userId)
                ->whereNull('created_transaction_id')
                ->where('type', 'income')
                ->whereBetween('due_date', [$start, $end])
                ->sum('amount');

            $plannedExpenses = PlannedTransaction::where('user_id', $userId)
                ->whereNull('created_transaction_id')
                ->where('type', 'expense')
                ->whereBetween('due_date', [$start, $end])
                ->sum('amount');

            $months->push([
                'month' => $start->translatedFormat('F'),
                'income' => $income,
                'expenses' => $expenses,
                'plannedIncome' => $plannedIncome,
                'plannedExpenses' => $plannedExpenses,
                'balance' => $income - $expenses,
                'forecast' => $income - $expenses + $plannedIncome - $plannedExpenses,
            ]);
        }

        return Inertia::render('Reports/Year', [
            'year' => $year,
            'previousYear' => $year - 1,
            'nextYear' => $year + 1,
            'months' => $months,
        ]);
    }
}
