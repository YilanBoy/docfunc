<?php

namespace App\Enums;

enum CommentOrder: string
{
    case LATEST = 'latest';

    case OLDEST = 'oldest';

    case POPULAR = 'popular';
}
