<?php

namespace Database\Factories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReplyFactory extends Factory
{
    protected $model = Reply::class;

    public function definition()
    {
        return [
            'content' => $this->faker->sentence,
            'created_at' => $this->faker->dateTimeThisMonth(now()),
            'updated_at' => now(),
        ];
    }
}
