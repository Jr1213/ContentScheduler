<?php

namespace App\Http\Requests\Post;

use App\Enums\PostStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'scheduled_time' => ['nullable', 'date', 'after_or_equal:now', 'date_format:Y-m-d H:i:s'],
            'image' => ['nullable', 'image', 'max:2048'],
            'platform_id' => ['required', 'array'],
            'platform_id.*' => ['required', 'exists:platforms,id'],
        ];
    }
}
