<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Generator;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    const POST_COUNT = 1_000;

    const CHUNK = 100;

    protected function postGenerator(int $userCount): Generator
    {
        for ($i = 1; $i <= self::POST_COUNT; $i++) {
            $data[] = [
                'title' => fake()->text(30),
                'body' => fake()->paragraph(10),
                'slug' => fake()->word(),
                'excerpt' => fake()->sentence,
                'category_id' => fake()->numberBetween(1, 3),
                'comment_counts' => 0,
                'user_id' => rand(1, $userCount),
                'created_at' => fake()->dateTimeBetween(startDate: '-3 years'),
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

        foreach ($this->postGenerator($userCount) as $data) {
            Post::query()->insert($data);
        }
    }
}
