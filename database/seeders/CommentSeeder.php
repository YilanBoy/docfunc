<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Generator;
use Illuminate\Database\Seeder;
use Random\RandomException;

class CommentSeeder extends Seeder
{
    const COMMENT_COUNT = 100_000;

    const CHUNK = 1_000;

    /**
     * @throws RandomException
     */
    protected function commentGenerator(int $userCount, int $postCount): Generator
    {
        for ($i = 1; $i <= self::COMMENT_COUNT; $i++) {
            $data[] = [
                'user_id' => random_int(1, $userCount),
                'post_id' => random_int(1, $postCount),
                'body' => fake()->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($i % self::CHUNK === 0) {
                yield $data;

                $data = [];
            }
        }
    }

    public function run(): void
    {
        $userCount = User::query()->count();
        $postCount = Post::query()->count();

        foreach ($this->commentGenerator($userCount, $postCount) as $data) {
            Comment::query()->insert($data);
        }
    }
}
