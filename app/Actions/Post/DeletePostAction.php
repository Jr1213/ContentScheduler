<?php

namespace App\Actions\Post;

use App\Models\Post;
use App\Service\PostService;

class DeletePostAction
{
    public function __construct(private readonly PostService $postService) {}

    public function handle(Post $post): void
    {
        $this->postService->delete(post: $post);

        if ($post->job_id) $this->postService->deleteJob($post->job_id);
    }
}
