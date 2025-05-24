<?php

namespace App\Service;

use App\Dtos\Post\FilterPostDto;
use App\Dtos\Post\PostDto;
use App\Models\Platform;
use App\Models\Post;
use App\Models\PostPlatform;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class PostService
{

    public function getUserPosts(FilterPostDto $filterPostDto, User $user)
    {
        $posts = $user->posts()
            ->orderByDesc('created_at')
            ->when(isset($filterPostDto->status) && !is_null($filterPostDto->status), fn($q) => $q->whereStatus($filterPostDto->status))
            ->when(isset($filterPostDto->date) && !is_null($filterPostDto->date), fn($q) => $q->whereDate('scheduled_time', $filterPostDto->date))
            ->paginate();

        return [
            'items' => $posts->items(),
            'per_page' => $posts->perPage(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'total' => $posts->total(),
        ];
    }

    public function store(PostDto $postDto): Post
    {
        return Post::create($postDto->toArray());
    }

    public function checkUserScheduleLimit(User $user)
    {
        $postCount = $user->posts()
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->count();

        if ($postCount >= (int) config('app.user_limit')) {
            throw new \Exception('You have reached your schedule limit for today.', Response::HTTP_FORBIDDEN);
        }
    }


    public function addPostToPlatform(Post $post, int $platform): void
    {
        $post->platform()->attach([$platform]);
    }
}
