<?php

namespace App\Http\Controllers;

use App\Models\PlannerAppointment;
use App\Models\PlannerCategory;
use App\Models\PlannerTask;
use App\Models\PlannerWeekNote;
use Carbon\Carbon;
use Inertia\Inertia;

class PlannerWeekController extends Controller
{
    public function __invoke(?string $date = null)
    {
        $day = $date ? Carbon::parse($date) : now();
        $weekStart = $day->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $day->copy()->endOfWeek(Carbon::SUNDAY);

        $tasks = PlannerTask::where('user_id', auth()->id())
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->with('category')
            ->get()
            ->groupBy(fn ($task) => $task->date->toDateString());

        $appointmentCounts = PlannerAppointment::where('user_id', auth()->id())
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->groupBy(fn ($appointment) => $appointment->date->toDateString())
            ->map->count();

        $days = collect(range(0, 6))->map(function ($i) use ($weekStart, $tasks, $appointmentCounts) {
            $date = $weekStart->copy()->addDays($i);
            $dateStr = $date->toDateString();
            $dayTasks = $tasks->get($dateStr, collect());

            $categories = $dayTasks->groupBy('planner_category_id')->map(function ($group) {
                $first = $group->first();

                return [
                    'id' => $first->category->id,
                    'name' => $first->category->name,
                    'color' => $first->category->color,
                    'count' => $group->count(),
                ];
            })->values();

            return [
                'date' => $dateStr,
                'taskCount' => $dayTasks->count(),
                'doneCount' => $dayTasks->where('is_done', true)->count(),
                'appointmentCount' => $appointmentCounts->get($dateStr, 0),
                'categories' => $categories,
                'titles' => $dayTasks->pluck('title')->take(3)->values(),
                'moreTitles' => max($dayTasks->count() - 3, 0),
            ];
        });

        return Inertia::render('Planner/Week', [
            'date' => $day->toDateString(),
            'weekStart' => $weekStart->toDateString(),
            'weekEnd' => $weekEnd->toDateString(),
            'weekNumber' => (int) $weekStart->isoWeek,
            'year' => (int) $weekStart->isoWeekYear,
            'weekNote' => PlannerWeekNote::where('user_id', auth()->id())
                ->where('year', $weekStart->isoWeekYear)
                ->where('week_number', $weekStart->isoWeek)
                ->first(),
            'days' => $days->values(),
            'categories' => PlannerCategory::where('user_id', auth()->id())
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
