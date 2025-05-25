<?php

namespace App\Actions\Post;

use App\Dtos\Post\PostDto;
use App\Enums\PlatformStatusEnum;
use App\Enums\PostStatusEnum;
use App\Models\Post;
use App\Service\ErrorLogService;
use App\Service\PostService;

class PublishPostAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly PostService $postService, private readonly ErrorLogService $errorLogService)
    {
        //
    }


    public function handle(Post $post): void
    {
        foreach ($post->platforms as $platform) {
            $is_published = $post->publish($platform->id);
            if ($is_published) {
                $this->postService->update(PostDto::create(['status' => PostStatusEnum::PUBLISHED]), $post);
                $this->postService->updatePlatformStatus($post, $platform->id, PlatformStatusEnum::SUCCESS);
            } else {
                $this->errorLogService->log('in future updates we will add the error response form the platform');
                $this->postService->updatePlatformStatus($post, $platform->id, PlatformStatusEnum::FALID);
                $this->postService->update(PostDto::create(['status' => PostStatusEnum::DRAFT]), $post);
            }
        }
    }
}
