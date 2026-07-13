<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlannedTransactionRequest;
use App\Http\Requests\UpdatePlannedTransactionRequest;
use App\Models\Category;
use App\Models\PlannedTransaction;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PlannedTransactionController extends Controller
{
    public function index()
    {
        return Inertia::render('PlannedTransactions/Index', [
            'plannedTransactions' => PlannedTransaction::with('category')
                ->where('user_id', auth()->id())
                ->orderBy('due_date')
                ->orderBy('id')
                ->get(),
        ]);
    }


    public function create()
    {
        return Inertia::render('PlannedTransactions/Create', [
            'categories' => Category::where('user_id', auth()->id())
                ->orderBy('type')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(StorePlannedTransactionRequest $request)
    {
        $request->user()->plannedTransactions()->create(
            $request->validated()
        );

        return redirect()->route('planned-transactions.index')->with('success', 'Geplante Buchung gespeichert.');
    }

    public function edit(PlannedTransaction $plannedTransaction)
    {
        abort_if($plannedTransaction->user_id !== auth()->id(), 403);

        return Inertia::render('PlannedTransactions/Edit', [
            'plannedTransaction' => [
                'id' => $plannedTransaction->id,
                'category_id' => $plannedTransaction->category_id,
                'type' => $plannedTransaction->type,
                'amount' => $plannedTransaction->amount,
                'due_date' => $plannedTransaction->due_date->format('Y-m-d'),
                'description' => $plannedTransaction->description,
                'repeat_type' => $plannedTransaction->repeat_type,
                'repeat_day' => $plannedTransaction->repeat_day,
                'repeat_until' => $plannedTransaction->repeat_until?->format('Y-m-d'),
            ],

            'categories' => Category::where('user_id', auth()->id())
                ->orderBy('type')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(
        UpdatePlannedTransactionRequest $request,
        PlannedTransaction $plannedTransaction
    ) {
        abort_if($plannedTransaction->user_id !== auth()->id(), 403);

        $plannedTransaction->update(
            $request->validated()
        );

        return redirect()->route('planned-transactions.index')->with('success', 'Geplante Buchung aktualisiert.');
    }

    public function destroy(PlannedTransaction $plannedTransaction)
    {
        abort_if($plannedTransaction->user_id !== auth()->id(), 403);

        $plannedTransaction->delete();

        return redirect()->route('planned-transactions.index')->with('success', 'Geplante Buchung gelöscht.');
    }

    public function pay(PlannedTransaction $plannedTransaction)
    {
        abort_if($plannedTransaction->user_id !== auth()->id(), 403);

        if ($plannedTransaction->created_transaction_id) {
            return back();
        }

        DB::transaction(function () use ($plannedTransaction) {
            $transaction = Transaction::create([
                'user_id' => $plannedTransaction->user_id,
                'category_id' => $plannedTransaction->category_id,
                'type' => $plannedTransaction->type,
                'amount' => $plannedTransaction->amount,
                'date' => now()->toDateString(),
                'description' => $plannedTransaction->description,
            ]);

            $plannedTransaction->update([
                'created_transaction_id' => $transaction->id,
            ]);

            if ($plannedTransaction->repeat_type !== 'none') {
                $nextDueDate = match ($plannedTransaction->repeat_type) {
                    'monthly' => $plannedTransaction->due_date->copy()->addMonthNoOverflow(),
                    'yearly' => $plannedTransaction->due_date->copy()->addYearNoOverflow(),
                };

                if (
                    !$plannedTransaction->repeat_until ||
                    $nextDueDate->lte($plannedTransaction->repeat_until)
                ) {
                    PlannedTransaction::create([
                        'user_id' => $plannedTransaction->user_id,
                        'category_id' => $plannedTransaction->category_id,
                        'type' => $plannedTransaction->type,
                        'amount' => $plannedTransaction->amount,
                        'due_date' => $nextDueDate,
                        'description' => $plannedTransaction->description,
                        'repeat_type' => $plannedTransaction->repeat_type,
                        'repeat_day' => $plannedTransaction->repeat_day,
                        'repeat_until' => $plannedTransaction->repeat_until,
                        'parent_id' => $plannedTransaction->parent_id ?? $plannedTransaction->id,
                    ]);
                }
            }
        });

        return redirect()->route('planned-transactions.index')->with('success', 'Als bezahlt markiert.');
    }
}
