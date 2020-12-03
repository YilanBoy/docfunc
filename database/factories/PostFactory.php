<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph(10),
            'excerpt' => $this->faker->sentence,
            // 隨機取一個月以內，但早於現在的時間
            'created_at' => $this->faker->dateTimeThisMonth(now()),
            'updated_at' => now(),
        ];
    }
}
