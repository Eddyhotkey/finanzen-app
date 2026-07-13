<?php

namespace App\Http\Controllers;

use App\Models\PlannerDailyNote;
use Inertia\Inertia;

class PlannerIdeasController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Planner/Ideas', [
            'date' => now()->toDateString(),
            'notes' => PlannerDailyNote::where('user_id', auth()->id())
                ->whereNotNull('content')
                ->where('content', '!=', '')
                ->orderByDesc('date')
                ->get(),
        ]);
    }
}
