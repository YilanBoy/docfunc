<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $userCount = User::query()->count();
        $postCount = 1_000;

        $data = [];

        for ($i = 0; $i < $postCount; $i++) {
            $data[] = [
                'title' => fake()->text(30),
                'body' => fake()->paragraph(10),
                'slug' => fake()->word(),
                'excerpt' => fake()->sentence,
                'category_id' => fake()->numberBetween(1, 3),
                'comment_count' => 0,
                'user_id' => rand(1, $userCount),
                // 隨機取一個月以內，但早於現在的時間
                'created_at' => fake()->dateTimeThisMonth(now()),
                'updated_at' => now(),
            ];
        }

        Post::query()->insert($data);
    }
}
