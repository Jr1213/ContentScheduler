<?php

namespace App\Dtos\Post;

use App\Enums\PostStatusEnum;
use App\Traits\StaticCreateSelf;
use App\Traits\ToArray;

class FilterPostDto
{
    use StaticCreateSelf,ToArray;


    public readonly ?PostStatusEnum $status;

    public readonly ?string $date;
}
