<?php

namespace App;

enum PostStatusEnum: string
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case PUBLISHED= 'published';
}
