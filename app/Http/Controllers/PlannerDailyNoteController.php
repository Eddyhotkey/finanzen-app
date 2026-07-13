<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SanitizesRichText;
use App\Http\Requests\StorePlannerDailyNoteRequest;

class PlannerDailyNoteController extends Controller
{
    use SanitizesRichText;

    public function store(StorePlannerDailyNoteRequest $request)
    {
        // Not updateOrCreate(['date' => ...], ...): the `date` cast serializes
        // to "Y-m-d H:i:s" on save, but the raw request date string never
        // matches that in the WHERE clause, so it always tried to INSERT and
        // hit the unique(user_id, date) constraint on the second save of a day.
        $note = $request->user()->plannerDailyNotes()
            ->whereDate('date', $request->date)
            ->first() ?? $request->user()->plannerDailyNotes()->make(['date' => $request->date]);

        $note->content = $this->sanitizeRichText($request->content);
        $note->save();

        return redirect()->route('planner.day', $request->date);
    }
}
