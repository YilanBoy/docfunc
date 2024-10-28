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
    const COMMENT_COUNT = 10_000;

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

    protected function updateCommentCount(): void
    {
        // update comment_counts of post table
        $postCommentCountsData = Comment::query()->selectRaw('
            post_id,
            count(id) AS post_comment_counts
        ')
            ->groupBy('post_id')
            ->orderBy('post_id')
            ->get()
            ->toArray();

        foreach ($postCommentCountsData as $data) {
            Post::query()
                ->where('id', $data['post_id'])
                ->update([
                    'comment_counts' => $data['post_comment_counts'],
                ]);
        }
    }

    public function run(): void
    {
        $userCount = User::query()->count();
        $postCount = Post::query()->count();

        foreach ($this->commentGenerator($userCount, $postCount) as $data) {
            Comment::query()->insert($data);
        }

        $this->updateCommentCount();
    }
}
