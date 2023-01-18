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
        $commentCount = 10_000;
        $commentChunk = 1_000;

        $data = [];

        for ($i = 0; $i < $commentCount; $i++) {
            $data[] = [
                'user_id' => rand(1, $userCount),
                'post_id' => rand(1, $postCount),
                'content' => fake()->sentence,
                'created_at' => fake()->dateTimeThisMonth(now()),
                'updated_at' => now(),
            ];
        }

        $chunks = array_chunk($data, $commentChunk);

        foreach ($chunks as $chunk) {
            Comment::query()->insert($chunk);
        }
    }
}
