<?php

namespace App\Jobs;

use App\Actions\Post\PublishPostAction;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PublishPostJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Post $post)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(PublishPostAction::class)->handle($this->post);
    }
}
