<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->text(30),
            'body' => $this->faker->paragraph(10),
            'excerpt' => $this->faker->sentence,
            'category_id' => $this->faker->numberBetween(1, 3),
            'comment_count' => 0,
            'user_id' => User::factory()->create()->id,
            // 隨機取一個月以內，但早於現在的時間
            'created_at' => $this->faker->dateTimeThisMonth(now()),
            'updated_at' => now(),
        ];
    }
}
