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

        for ($i = 0; $i < 1_000; $i++) {
            $data[] = [
                'user_id' => 1,
                'post_id' => 1,
                'body' => fake()->sentence,
                'created_at' => fake()->dateTimeThisMonth(now()),
                'updated_at' => now(),
            ];
        }

        $chunks = array_chunk($data, $commentChunk);

        foreach ($chunks as $chunk) {
            Comment::query()->insert($chunk);
        }

        Post::all()->each(function ($item, $key) {
            $item->update(['comment_counts' => $item->comments()->count()]);
        });
    }
}
