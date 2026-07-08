<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        return Inertia::render('Accounts/Index', [
            'accounts' => $accounts,
            'totalBalance' => $accounts->where('is_active', true)->sum('initial_balance'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Accounts/Create');
    }

    public function store(StoreAccountRequest $request)
    {
        $request->user()->accounts()->create($request->validated());

        return redirect()->route('accounts.index');
    }

    public function edit(Account $account)
    {
        abort_if($account->user_id !== auth()->id(), 403);

        return Inertia::render('Accounts/Edit', [
            'account' => $account,
        ]);
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        abort_if($account->user_id !== auth()->id(), 403);

        $account->update($request->validated());

        return redirect()->route('accounts.index');
    }

    public function destroy(Account $account)
    {
        abort_if($account->user_id !== auth()->id(), 403);

        $account->delete();

        return redirect()->route('accounts.index');
    }
}
