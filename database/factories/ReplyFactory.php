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
        // 隨機取一個月內的時間
        $time = $this->faker->dateTimeThisMonth();

        return [
            'content' => $this->faker->sentence(),
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }
}
