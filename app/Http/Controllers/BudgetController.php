<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use App\Models\Category;
use Inertia\Inertia;

use App\Services\Finance\BudgetService;

class BudgetController extends Controller
{
    public function index(BudgetService $budgetService)
    {
        return Inertia::render('Budgets/Index', [
            'budgets' => $budgetService->overview(auth()->user()),
        ]);
    }

    public function create()
    {
        return Inertia::render('Budgets/Create', [
            'categories' => Category::where('user_id', auth()->id())
                ->where('type', 'expense')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(StoreBudgetRequest $request)
    {
        $request->user()->budgets()->create($request->validated());

        return redirect()->route('budgets.index')->with('success', 'Budget gespeichert.');
    }

    public function edit(Budget $budget)
    {
        abort_if($budget->user_id !== auth()->id(), 403);

        return Inertia::render('Budgets/Edit', [
            'budget' => $budget,
            'categories' => Category::where('user_id', auth()->id())
                ->where('type', 'expense')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        abort_if($budget->user_id !== auth()->id(), 403);

        $budget->update($request->validated());

        return redirect()->route('budgets.index')->with('success', 'Budget aktualisiert.');
    }

    public function destroy(Budget $budget)
    {
        abort_if($budget->user_id !== auth()->id(), 403);

        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget gelöscht.');
    }
}
