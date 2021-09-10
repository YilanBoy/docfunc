<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'post_id' => $this->faker->numberBetween(1, 100),
            'content' => $this->faker->sentence,
            'created_at' => $this->faker->dateTimeThisMonth(now()),
            'updated_at' => now(),
        ];
    }
}
