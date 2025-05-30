<?php

namespace App\Actions\Post;

use App\Dtos\Post\PostDto;
use App\Helpers\UploadHelper;
use App\Http\Requests\Post\StorePostRequest;
use App\Models\Post;
use App\Service\PostService;
use DateTime;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CreatePostAction
{
    public function __construct(private readonly PostService $postService) {}

    public function handle(StorePostRequest $request): Post
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $scheduledTime = new DateTime($data['scheduled_time'] ?? 'now');

            $postDto = PostDto::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'scheduled_time' => $scheduledTime,
                'user_id' => $request->user()->id,
                'image_url' => $request->hasFile('image')
                    ? UploadHelper::store($request->file('image'), 'posts')
                    : null,
            ]);

            $this->postService->checkUserScheduleLimit($request->user());

            $post = $this->postService->store($postDto);

            $this->postService->addPostToPlatform($post, $data['platform_id']);

            if (!$this->postService->validatePost($post)) {
                DB::rollBack();
                throw new \Exception('Post validation failed', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::commit();

            $this->postService->dispatchJob($post, $scheduledTime);

            $lastJob = $this->postService->lastAddedJob();

            if ($lastJob) {
                $this->postService->update(PostDto::create(['job_id' => $lastJob]),$post);
            }

            return $post->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
