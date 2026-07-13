<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SanitizesRichText;
use App\Http\Requests\StorePlannerWeekNoteRequest;

class PlannerWeekNoteController extends Controller
{
    use SanitizesRichText;

    public function store(StorePlannerWeekNoteRequest $request)
    {
        $request->user()->plannerWeekNotes()->updateOrCreate(
            ['year' => $request->year, 'week_number' => $request->week_number],
            ['content' => $this->sanitizeRichText($request->content)]
        );

        return redirect()->route('planner.week', $request->input('redirect_date', now()->toDateString()));
    }
}
