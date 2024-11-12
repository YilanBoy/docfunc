<?php

namespace App\Enums;

enum CommentOrder: string
{
    case POPULAR = 'popular';

    case LATEST = 'latest';

    case OLDEST = 'oldest';

    public function label(): string
    {
        return match ($this) {
            self::POPULAR => '熱門留言',
            self::LATEST => '由新到舊',
            self::OLDEST => '由舊到新',
        };
    }
}
