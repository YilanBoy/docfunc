<?php

namespace Database\Factories;

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
            'user_id' => $this->faker->numberBetween(1, 10),
            // 隨機取一個月以內，但早於現在的時間
            'created_at' => $this->faker->dateTimeThisMonth(now()),
            'updated_at' => now(),
        ];
    }
}
