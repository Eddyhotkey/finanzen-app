<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlannerWeekNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'integer'],
            'week_number' => ['required', 'integer', 'min:1', 'max:53'],
            'content' => ['nullable', 'string'],
        ];
    }
}
