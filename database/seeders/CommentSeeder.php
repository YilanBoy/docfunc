<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $userCount = User::query()->count();
        $postCount = Post::query()->count();
        $commentCounts = 10_000;
        $commentChunk = 1_000;

        $data = [];

        for ($i = 0; $i < $commentCounts; $i++) {
            $data[] = [
                'user_id' => rand(1, $userCount),
                'post_id' => rand(1, $postCount),
                'body' => fake()->sentence,
                'created_at' => fake()->dateTimeThisMonth(now()),
                'updated_at' => now(),
            ];
        }

        $chunks = array_chunk($data, $commentChunk);

        foreach ($chunks as $chunk) {
            Comment::query()->insert($chunk);
        }

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
}
