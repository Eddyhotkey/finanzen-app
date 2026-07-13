<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlannerTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'planner_category_id' => [
                'required',
                Rule::exists('planner_categories', 'id')->where('user_id', auth()->id()),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'date' => ['required', 'date'],
            'priority' => ['required', 'in:niedrig,mittel,hoch'],
            'is_done' => ['boolean'],
        ];
    }
}
