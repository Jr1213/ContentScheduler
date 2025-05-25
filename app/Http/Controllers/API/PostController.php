<?php

namespace App\Http\Controllers\API;

use App\Actions\Post\CreatePostAction;
use App\Actions\Post\DeletePostAction;
use App\Actions\Post\UpdatePostAction;
use App\Dtos\Post\FilterPostDto;
use App\Enums\PostStatusEnum;
use App\Http\Requests\Post\FilterPostRequest;
use App\Service\PostService;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function __construct(
        private readonly PostService $postService,
        private readonly CreatePostAction $createPostAction,
        private readonly UpdatePostAction $updatePostAction,
        private readonly DeletePostAction $deletePostAction
    ) {}
    public function index(FilterPostRequest $request): JsonResponse
    {
        try {
            $filters = [
                'status' => PostStatusEnum::tryFrom($request->status) ?? null,
                'date' => $request->date ?? null,
            ];

            $posts = $this->postService->getUserPosts(FilterPostDto::create($filters), request()->user());

            return $this->response(data: $posts, message: 'Posts fetched successfully');
        } catch (Exception $e) {
            return $this->error($e);
        }
    }


    public function store(StorePostRequest $request): JsonResponse
    {
        try {
            $post = $this->createPostAction->handle($request);

            return $this->response(data: [
                'post' => $post
            ], message: 'Post created successfully', status: Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        try {
            $post = $this->updatePostAction->handle($request, $post);

            return $this->response(data: [
                'post' => $post
            ], message: 'post update successfully', status: Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function destroy(Post $post): JsonResponse
    {
        try {
            request()->user()->can('delete', $post);
            $this->deletePostAction->handle($post);
            return $this->response(message: 'Post deleted successfully', status: Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }
}
