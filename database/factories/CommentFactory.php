<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommentFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'post_id' => Post::factory()->create()->id,
            'content' => $this->faker->sentence,
            'created_at' => $this->faker->dateTimeThisMonth(now()),
            'updated_at' => now(),
        ];
    }
}
