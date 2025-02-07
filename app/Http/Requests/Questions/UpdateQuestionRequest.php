<?php

namespace App\Http\Requests\Questions;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:questions'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'single_answer' => ['nullable', 'string'],
            'questions' => ['nullable', 'array', 'min:1'],
            'questions.*.text' => ['required', 'string'],
            'questions.*.correct' => ['required', 'boolean'],
            'coordinates' => ['nullable', 'array'],
            'coordinates.*' => ['numeric'],
        ];
    }
}
