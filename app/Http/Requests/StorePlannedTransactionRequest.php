<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlannedTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where('user_id', $this->user()->id),
            ],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'due_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
            'repeat_type' => ['required', 'in:none,monthly,yearly'],
            'repeat_day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'repeat_until' => ['nullable', 'date'],
        ];
    }
}
