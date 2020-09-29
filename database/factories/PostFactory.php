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
        $sentence = $this->faker->sentence();

        // 隨機取一個月以內的時間
        $updated_at = $this->faker->dateTimeThisMonth();

        // 傳參為生成最大時間不超過，因為創建時間需永遠比更改時間要早
        $created_at = $this->faker->dateTimeThisMonth($updated_at);

        return [
            'title' => $sentence,
            'body' => $this->faker->text(),
            'excerpt' => $sentence,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}
