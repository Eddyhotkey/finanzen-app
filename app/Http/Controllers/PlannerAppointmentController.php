<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlannerAppointmentRequest;
use App\Http\Requests\UpdatePlannerAppointmentRequest;
use App\Models\PlannerAppointment;

class PlannerAppointmentController extends Controller
{
    public function store(StorePlannerAppointmentRequest $request)
    {
        $request->user()->plannerAppointments()->create($request->validated());

        return redirect()->route('planner.day', $request->date)->with('success', 'Termin gespeichert.');
    }

    public function update(UpdatePlannerAppointmentRequest $request, PlannerAppointment $plannerAppointment)
    {
        abort_if($plannerAppointment->user_id !== auth()->id(), 403);

        $plannerAppointment->update([...$request->validated(), 'notified_at' => null]);

        return redirect()->route('planner.day', $request->date)->with('success', 'Termin aktualisiert.');
    }

    public function destroy(PlannerAppointment $plannerAppointment)
    {
        abort_if($plannerAppointment->user_id !== auth()->id(), 403);

        $viewDate = $plannerAppointment->date->toDateString();

        $plannerAppointment->delete();

        return redirect()->route('planner.day', $viewDate)->with('success', 'Termin gelöscht.');
    }
}
