<?php

namespace App\Http\Controllers;

use App\Models\PlannerSpecialPeriod;
use App\Models\PlannerYearGoal;
use Carbon\Carbon;
use Inertia\Inertia;

class PlannerYearController extends Controller
{
    public function __invoke(?int $year = null)
    {
        $year ??= now()->year;

        $holidays = $this->nationalHolidays($year);
        $periods = PlannerSpecialPeriod::where('user_id', auth()->id())
            ->where('start_date', '<=', "{$year}-12-31")
            ->where('end_date', '>=', "{$year}-01-01")
            ->get();

        $months = collect(range(1, 12))->map(function ($month) use ($year, $holidays, $periods) {
            $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $gridStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
            $gridEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

            $holidayCount = collect($holidays)
                ->keys()
                ->filter(fn ($date) => $date >= $monthStart->toDateString() && $date <= $monthEnd->toDateString())
                ->count();

            $days = collect();
            $cursor = $gridStart->copy();

            while ($cursor->lte($gridEnd)) {
                $dateStr = $cursor->toDateString();
                $period = $periods->first(fn ($p) => $dateStr >= $p->start_date->toDateString() && $dateStr <= $p->end_date->toDateString());

                $days->push([
                    'date' => $dateStr,
                    'day' => $cursor->day,
                    'inMonth' => $cursor->month === $month,
                    'isToday' => $cursor->isToday(),
                    'isHoliday' => isset($holidays[$dateStr]),
                    'holidayLabel' => $holidays[$dateStr] ?? null,
                    'periodTag' => $period?->tag,
                    'periodColor' => $period?->color,
                ]);

                $cursor->addDay();
            }

            return [
                'month' => $month,
                'date' => $monthStart->toDateString(),
                'holidayCount' => $holidayCount,
                'days' => $days,
            ];
        });

        return Inertia::render('Planner/Year', [
            'year' => $year,
            'months' => $months,
            'periods' => $periods,
            'goals' => PlannerYearGoal::where('user_id', auth()->id())
                ->where('year', $year)
                ->orderBy('category_label')
                ->get()
                ->groupBy('category_label'),
        ]);
    }

    private function nationalHolidays(int $year): array
    {
        $easter = Carbon::createFromTimestamp(easter_date($year, CAL_EASTER_ALWAYS_GREGORIAN));

        return [
            Carbon::create($year, 1, 1)->toDateString() => 'Neujahr',
            $easter->copy()->subDays(2)->toDateString() => 'Karfreitag',
            $easter->copy()->addDay()->toDateString() => 'Ostermontag',
            Carbon::create($year, 5, 1)->toDateString() => 'Tag der Arbeit',
            $easter->copy()->addDays(39)->toDateString() => 'Christi Himmelfahrt',
            $easter->copy()->addDays(50)->toDateString() => 'Pfingstmontag',
            Carbon::create($year, 10, 3)->toDateString() => 'Tag der Deutschen Einheit',
            Carbon::create($year, 12, 25)->toDateString() => '1. Weihnachtsfeiertag',
            Carbon::create($year, 12, 26)->toDateString() => '2. Weihnachtsfeiertag',
        ];
    }
}
