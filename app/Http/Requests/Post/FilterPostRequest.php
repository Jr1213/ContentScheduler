<?php

namespace App\Http\Requests\Post;

use App\Enums\PostStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class FilterPostRequest extends FormRequest
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
            'status' => ['nullable', 'in:' . implode(',', array_column(PostStatusEnum::cases(), 'value'))],
            'date' => ['nullable', 'date'],
        ];
    }
}
