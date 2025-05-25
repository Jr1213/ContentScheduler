<?php

namespace App\Policies;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id && $post->status->value != PostStatusEnum::PUBLISHED->value;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id && $post->status->value != PostStatusEnum::PUBLISHED->value;
    }
}
