<?php

namespace App\Actions\Post;

use App\Dtos\Post\PostDto;
use App\Enums\PostStatusEnum;
use App\Helpers\UploadHelper;
use App\Http\Requests\Post\StorePostRequest;
use App\Models\Post;
use App\Service\PostService;
use DateTime;

class CreatePostAction
{

    public function __construct(private readonly PostService $postService) {}

    public function handel(StorePostRequest $request): Post
    {
        $data = $request->validated();
        $postDto = PostDto::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => PostStatusEnum::from($data['status']),
            'scheduled_time' => new DateTime($data['scheduled_time']),
            'user_id' => $request->user()->id,
            'image_url' => $request->hasFile('image')  ? UploadHelper::store($request->file('image'), 'posts') : null,
        ]);

        $this->postService->checkUserScheduleLimit($request->user());

        $post = $this->postService->store($postDto);

        $this->postService->addPostToPlatform($post,$data['platform_id']);

        //TODO add sechedule job

        return $post;
    }
}
