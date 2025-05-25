<?php

namespace App\Actions\Post;

use App\Dtos\Post\PostDto;
use App\Helpers\UploadHelper;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Service\PostService;
use DateTime;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UpdatePostAction
{
    public function __construct(private readonly PostService $postService) {}

    public function handle(UpdatePostRequest $request, Post $post): Post
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $scheduledTime = new DateTime($data['scheduled_time'] ?? $post->scheduled_time);

            $postDto = PostDto::create([
                'title' => $data['title'] ?? $post->title,
                'content' => $data['content'] ?? $post->content,
                'scheduled_time' => $scheduledTime,
                'image_url' => $request->hasFile('image')
                    ? UploadHelper::store($request->file('image'), 'posts')
                    : null,
            ]);


            $this->postService->update(postDto: $postDto, post: $post);

            $this->postService->addPostToPlatform($post, $data['platform_id']);

            if (!$this->postService->validatePost($post)) {
                DB::rollBack();
                throw new \Exception('Post validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::commit();

            if ($post->job_id) $this->postService->deleteJob($post->job_id);

            $this->postService->dispatchJob($post, $scheduledTime);

            $lastJob = $this->postService->lastAddedJob();

            if ($lastJob) {
                $this->postService->update(PostDto::create(['job_id' => $lastJob]), $post);
            }

            return $post->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
