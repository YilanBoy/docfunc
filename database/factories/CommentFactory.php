<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition()
    {
        $post = Post::factory()->create();
        $post->increment('comment_counts');

        return [
            'user_id' => User::factory()->create()->id,
            'post_id' => $post->id,
            'body' => fake()->sentence,
            'created_at' => fake()->dateTimeThisMonth(now()),
            'updated_at' => now(),
        ];
    }
}
