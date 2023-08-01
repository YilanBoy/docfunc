<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(30),
            'body' => fake()->paragraph(10),
            'is_private' => false,
            'preview_url' => fake()->imageUrl(),
            'slug' => fake()->word(),
            'excerpt' => fake()->sentence,
            'category_id' => fake()->numberBetween(1, 3),
            'comment_counts' => 0,
            'user_id' => User::factory()->create()->id,
            // 隨機取一個月以內，但早於現在的時間
            'created_at' => fake()->dateTimeThisMonth(now()),
            'updated_at' => now(),
        ];
    }

    public function userId(int $userId): static
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'user_id' => $userId,
            ];
        });
    }
}
