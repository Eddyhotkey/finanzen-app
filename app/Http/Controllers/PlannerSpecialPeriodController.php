<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlannerSpecialPeriodRequest;
use App\Http\Requests\UpdatePlannerSpecialPeriodRequest;
use App\Models\PlannerSpecialPeriod;
use Carbon\Carbon;

class PlannerSpecialPeriodController extends Controller
{
    public function store(StorePlannerSpecialPeriodRequest $request)
    {
        $request->user()->plannerSpecialPeriods()->create($request->validated());

        return redirect()->route('planner.year', $this->redirectYear($request))->with('success', 'Eintrag gespeichert.');
    }

    public function update(UpdatePlannerSpecialPeriodRequest $request, PlannerSpecialPeriod $plannerSpecialPeriod)
    {
        abort_if($plannerSpecialPeriod->user_id !== auth()->id(), 403);

        $plannerSpecialPeriod->update($request->validated());

        return redirect()->route('planner.year', $this->redirectYear($request))->with('success', 'Eintrag aktualisiert.');
    }

    public function destroy(PlannerSpecialPeriod $plannerSpecialPeriod)
    {
        abort_if($plannerSpecialPeriod->user_id !== auth()->id(), 403);

        $year = $plannerSpecialPeriod->start_date->year;

        $plannerSpecialPeriod->delete();

        return redirect()->route('planner.year', $year)->with('success', 'Eintrag gelöscht.');
    }

    private function redirectYear($request): int
    {
        if ($request->input('redirect_year')) {
            return (int) $request->input('redirect_year');
        }

        return Carbon::parse($request->input('start_date'))->year;
    }
}
