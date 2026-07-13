<?php

namespace App\Http\Controllers;

use App\Models\PlannerAppointment;
use App\Models\PlannerCategory;
use App\Models\PlannerDailyNote;
use App\Models\PlannerTask;
use Carbon\Carbon;
use Inertia\Inertia;

class PlannerDayController extends Controller
{
    public function __invoke(?string $date = null)
    {
        $day = $date ? Carbon::parse($date) : now();

        return Inertia::render('Planner/Day', [
            'date' => $day->toDateString(),
            'categories' => PlannerCategory::where('user_id', auth()->id())
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'tasks' => PlannerTask::where('user_id', auth()->id())
                ->whereDate('date', $day)
                ->get(),
            'appointments' => PlannerAppointment::where('user_id', auth()->id())
                ->whereDate('date', $day)
                ->orderBy('start_time')
                ->get(),
            'note' => PlannerDailyNote::where('user_id', auth()->id())
                ->whereDate('date', $day)
                ->first(),
        ]);
    }
}
