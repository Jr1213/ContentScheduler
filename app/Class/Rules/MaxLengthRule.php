<?php

namespace App\Class\Rules;

use App\Interface\ValidationRule;
use App\Models\Post;

class MaxLengthRule implements ValidationRule
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly Post $post, private readonly int $max, private readonly string $field) {}

    public function validate(): bool
    {
        if (!$this->post[$this->field]) {
            return false;
        }

        return strlen($this->post[$this->field]) <= $this->max;
    }
}
