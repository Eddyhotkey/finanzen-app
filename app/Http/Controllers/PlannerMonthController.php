<?php

namespace App\Http\Controllers;

use App\Models\PlannerCategory;
use App\Models\PlannerMonthNote;
use App\Models\PlannerTask;
use Carbon\Carbon;
use Inertia\Inertia;

class PlannerMonthController extends Controller
{
    public function __invoke(?int $year = null, ?int $month = null)
    {
        $year ??= now()->year;
        $month ??= now()->month;

        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $gridStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

        $tasks = PlannerTask::where('user_id', auth()->id())
            ->whereBetween('date', [$gridStart->toDateString(), $gridEnd->toDateString()])
            ->with('category')
            ->get()
            ->groupBy(fn ($task) => $task->date->toDateString());

        $days = collect();
        $cursor = $gridStart->copy();

        while ($cursor->lte($gridEnd)) {
            $dateStr = $cursor->toDateString();
            $dayTasks = $tasks->get($dateStr, collect());

            $days->push([
                'date' => $dateStr,
                'day' => $cursor->day,
                'inMonth' => $cursor->month === $monthStart->month,
                'isToday' => $cursor->isToday(),
                'categories' => $dayTasks->groupBy('planner_category_id')->map(function ($group) {
                    $first = $group->first();

                    return [
                        'name' => $first->category->name,
                        'color' => $first->category->color,
                        'count' => $group->count(),
                    ];
                })->values(),
            ]);

            $cursor->addDay();
        }

        return Inertia::render('Planner/Month', [
            'date' => $monthStart->toDateString(),
            'year' => $year,
            'month' => $month,
            'days' => $days,
            'categories' => PlannerCategory::where('user_id', auth()->id())
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'monthNote' => PlannerMonthNote::where('user_id', auth()->id())
                ->where('year', $year)
                ->where('month', $month)
                ->first(),
        ]);
    }
}
