<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');
        return $this->user()->can('update', $post);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'scheduled_time' => ['nullable', 'date', 'date_format:Y-m-d H:i:s'],
            'image' => ['nullable', 'image', 'max:2048'],
            'platform_id' => ['nullable', 'array'],
            'platform_id.*' => ['nullable', 'exists:platforms,id'],
        ];
    }
}
