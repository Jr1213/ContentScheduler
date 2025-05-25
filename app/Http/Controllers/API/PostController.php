<?php

namespace App\Http\Controllers\API;

use App\Actions\Post\CreatePostAction;
use App\Dtos\Post\FilterPostDto;
use App\Enums\PostStatusEnum;
use App\Http\Requests\Post\FilterPostRequest;
use App\Service\PostService;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function __construct(private readonly PostService $postService, private readonly CreatePostAction $createPostAction) {}
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
}
