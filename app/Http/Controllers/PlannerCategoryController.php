<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlannerCategoryRequest;
use App\Http\Requests\UpdatePlannerCategoryRequest;
use App\Models\PlannerCategory;

class PlannerCategoryController extends Controller
{
    public function store(StorePlannerCategoryRequest $request)
    {
        $request->user()->plannerCategories()->create($request->validated());

        return redirect()->route('planner.day', $request->input('redirect_date', now()->toDateString()))->with('success', 'Kategorie gespeichert.');
    }

    public function update(UpdatePlannerCategoryRequest $request, PlannerCategory $plannerCategory)
    {
        abort_if($plannerCategory->user_id !== auth()->id(), 403);

        $plannerCategory->update($request->validated());

        return redirect()->route('planner.day', $request->input('redirect_date', now()->toDateString()))->with('success', 'Kategorie aktualisiert.');
    }

    public function destroy(PlannerCategory $plannerCategory)
    {
        abort_if($plannerCategory->user_id !== auth()->id(), 403);

        $plannerCategory->delete();

        return redirect()->route('planner.day', now()->toDateString())->with('success', 'Kategorie gelöscht.');
    }
}
