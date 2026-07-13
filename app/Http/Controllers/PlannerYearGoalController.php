<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlannerYearGoalRequest;
use App\Http\Requests\UpdatePlannerYearGoalRequest;
use App\Models\PlannerYearGoal;

class PlannerYearGoalController extends Controller
{
    public function store(StorePlannerYearGoalRequest $request)
    {
        $request->user()->plannerYearGoals()->create($request->validated());

        return redirect()->route('planner.year', $request->year)->with('success', 'Ziel gespeichert.');
    }

    public function update(UpdatePlannerYearGoalRequest $request, PlannerYearGoal $plannerYearGoal)
    {
        abort_if($plannerYearGoal->user_id !== auth()->id(), 403);

        $plannerYearGoal->update($request->validated());

        return redirect()->route('planner.year', $plannerYearGoal->year)->with('success', 'Ziel aktualisiert.');
    }

    public function destroy(PlannerYearGoal $plannerYearGoal)
    {
        abort_if($plannerYearGoal->user_id !== auth()->id(), 403);

        $year = $plannerYearGoal->year;

        $plannerYearGoal->delete();

        return redirect()->route('planner.year', $year)->with('success', 'Ziel gelöscht.');
    }

    public function progress(PlannerYearGoal $plannerYearGoal)
    {
        abort_if($plannerYearGoal->user_id !== auth()->id(), 403);

        $delta = request()->integer('delta');

        $plannerYearGoal->update([
            'progress' => max(0, min(100, $plannerYearGoal->progress + $delta)),
        ]);

        return redirect()->route('planner.year', $plannerYearGoal->year);
    }
}
