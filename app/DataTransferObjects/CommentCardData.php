<?php

namespace App\DataTransferObjects;

use InvalidArgumentException;

class CommentCardData
{
    /**
     * @var array{'id': int, 'name': string, 'gravatar_url': string}|null
     */
    public ?array $user = null {
        set (?array $user) {
            if ($user !== null) {
                if (! isset($user['id'], $user['name'], $user['gravatar_url'])) {
                    throw new InvalidArgumentException('user must have id, name and gravatar_url');
                }
            }

            $this->user = $user;
        }
    }

    public function __construct(
        public int $id,
        public ?int $userId,
        public string $body,
        public string $convertedBody,
        public string $createdAt,
        public string $updatedAt,
        public int $childrenCount = 0,
        ?array $user = null
    ) {
        $this->user = $user;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'body' => $this->body,
            'converted_body' => $this->convertedBody,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'children_count' => $this->childrenCount,
            'user' => $this->user,
        ];
    }
}
