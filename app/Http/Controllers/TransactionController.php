<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        return Inertia::render('Transactions/Index', [
            'transactions' => Transaction::with('category')
                ->where('user_id', auth()->id())
                ->latest('date')
                ->latest('id')
                ->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Transactions/Create', [
            'categories' => Category::where('user_id', auth()->id())
                ->orderBy('type')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        $request->user()->transactions()->create($request->validated());

        return redirect()->route('transactions.index');
    }

    public function edit(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);

        return Inertia::render('Transactions/Edit', [
            'transaction' => $transaction,
            'categories' => Category::where('user_id', auth()->id())
                ->orderBy('type')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);

        $transaction->update($request->validated());

        return redirect()->route('transactions.index');
    }

    public function destroy(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);

        $transaction->delete();

        return redirect()->route('transactions.index');
    }
}
