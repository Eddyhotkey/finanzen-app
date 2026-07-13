<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlannerTaskRequest;
use App\Http\Requests\UpdatePlannerTaskRequest;
use App\Models\PlannerTask;
use Carbon\Carbon;

class PlannerTaskController extends Controller
{
    public function store(StorePlannerTaskRequest $request)
    {
        $request->user()->plannerTasks()->create($request->validated());

        if ($request->input('redirect_view') === 'month') {
            $month = Carbon::parse($request->input('redirect_date', $request->date));

            return redirect()->route('planner.month', [$month->year, $month->month])->with('success', 'Aufgabe gespeichert.');
        }

        if ($request->input('redirect_view') === 'week') {
            return redirect()->route('planner.week', $request->input('redirect_date', $request->date))->with('success', 'Aufgabe gespeichert.');
        }

        return redirect()->route('planner.day', $request->date)->with('success', 'Aufgabe gespeichert.');
    }

    public function update(UpdatePlannerTaskRequest $request, PlannerTask $plannerTask)
    {
        abort_if($plannerTask->user_id !== auth()->id(), 403);

        $plannerTask->update([...$request->validated(), 'notified_at' => null]);

        return redirect()->route('planner.day', $request->date);
    }

    public function destroy(PlannerTask $plannerTask)
    {
        abort_if($plannerTask->user_id !== auth()->id(), 403);

        $viewDate = $plannerTask->date->toDateString();

        $plannerTask->delete();

        return redirect()->route('planner.day', $viewDate)->with('success', 'Aufgabe gelöscht.');
    }

    public function defer(PlannerTask $plannerTask)
    {
        abort_if($plannerTask->user_id !== auth()->id(), 403);

        $viewDate = $plannerTask->date->toDateString();

        $plannerTask->update([
            'date' => $plannerTask->date->addDay(),
            'notified_at' => null,
        ]);

        return redirect()->route('planner.day', $viewDate);
    }
}
