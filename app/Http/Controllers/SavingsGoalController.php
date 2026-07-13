<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSavingsGoalRequest;
use App\Http\Requests\UpdateSavingsGoalRequest;
use App\Models\SavingsGoal;
use Inertia\Inertia;

class SavingsGoalController extends Controller
{
    public function index()
    {
        return Inertia::render('SavingsGoals/Index', [
            'goals' => SavingsGoal::where('user_id', auth()->id())
                ->orderByDesc('is_active')
                ->orderBy('target_date')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('SavingsGoals/Create');
    }

    public function store(StoreSavingsGoalRequest $request)
    {
        $request->user()->savingsGoals()->create(
            $request->validated()
        );

        return redirect()->route('savings-goals.index')->with('success', 'Sparziel gespeichert.');
    }

    public function edit(SavingsGoal $savingsGoal)
    {
        abort_if($savingsGoal->user_id !== auth()->id(), 403);

        return Inertia::render('SavingsGoals/Edit', [
            'goal' => $savingsGoal,
        ]);
    }

    public function update(
        UpdateSavingsGoalRequest $request,
        SavingsGoal $savingsGoal
    ) {
        abort_if($savingsGoal->user_id !== auth()->id(), 403);

        $savingsGoal->update(
            $request->validated()
        );

        return redirect()->route('savings-goals.index')->with('success', 'Sparziel aktualisiert.');
    }

    public function destroy(SavingsGoal $savingsGoal)
    {
        abort_if($savingsGoal->user_id !== auth()->id(), 403);

        $savingsGoal->delete();

        return redirect()->route('savings-goals.index')->with('success', 'Sparziel gelöscht.');
    }
}
