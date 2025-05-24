<?php

namespace App\Dtos\Post;

use App\Enums\PostStatusEnum;
use App\Traits\StaticCreateSelf;
use App\Traits\ToArray;
use DateTime;

class PostDto
{
    use StaticCreateSelf, ToArray;

    public readonly ?int $user_id;

    public readonly ?string $title;

    public readonly ?string $content;

    public readonly ?DateTime $scheduled_time;

    public readonly ?string $image_url;

    public readonly ?PostStatusEnum $status;
}
