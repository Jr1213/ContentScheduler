<?php

namespace App\Class\Rules;

use App\Interface\ValidationRule;
use App\Models\Post;

class ImageSizeRule implements ValidationRule
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly Post $post, private readonly int $max)
    {
        //
    }

    public function validate(): bool
    {

        if (! $this->post->image_url || ! file_exists($this->post->image_url)) {
            return false;
        }

        return filesize($this->post->image_url) <= $this->max;
    }
}
