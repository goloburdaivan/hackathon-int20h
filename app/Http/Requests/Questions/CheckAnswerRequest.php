<?php

namespace App\Http\Requests\Questions;

use Illuminate\Foundation\Http\FormRequest;

class CheckAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => ['integer', 'exists:rooms,id'],
            'single_answer' => ['nullable', 'string'],
            'question' => ['nullable', 'boolean'],
            'coordinates' => ['nullable', 'array'],
        ];
    }
}
