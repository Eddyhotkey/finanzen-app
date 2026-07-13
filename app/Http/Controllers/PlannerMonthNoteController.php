<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SanitizesRichText;
use App\Http\Requests\StorePlannerMonthNoteRequest;

class PlannerMonthNoteController extends Controller
{
    use SanitizesRichText;

    public function store(StorePlannerMonthNoteRequest $request)
    {
        $request->user()->plannerMonthNotes()->updateOrCreate(
            ['year' => $request->year, 'month' => $request->month],
            ['content' => $this->sanitizeRichText($request->content)]
        );

        return redirect()->route('planner.month', [$request->year, $request->month]);
    }
}
