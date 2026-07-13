<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlannerYearGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_label' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'status' => ['required', 'in:offen,aktiv,erledigt'],
            'progress' => ['integer', 'min:0', 'max:100'],
            'year' => ['required', 'integer'],
        ];
    }
}
