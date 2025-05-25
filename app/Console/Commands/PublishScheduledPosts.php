<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';
    protected $description = 'Dispatch jobs for posts scheduled to be published now';

    public function handle(): void
    {
        // $posts = Post::where('is_published', false)
        //     ->whereNotNull('published_at')
        //     ->where('published_at', '<=', now())
        //     ->get();

        // foreach ($posts as $post) {
        //     PublishPostJob::dispatch($post);
        //     $this->info("Job dispatched for post ID: {$post->id}");
        // }
    }
}
