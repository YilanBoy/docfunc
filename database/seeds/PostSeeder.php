<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Faker\Generator as Faker;

class PostSeeder extends Seeder
{
    public function run()
    {
        // 所有會員 ID 數組，如：[1,2,3,4]
        $user_ids = User::all()->pluck('id')->toArray();

        // 所有分類 ID 數組，如：[1,2,3,4]
        $category_ids = Category::all()->pluck('id')->toArray();

        // 獲取 Faker 實例
        $faker = app(Faker::class);

        $posts = Post::factory()
            ->times(100)
            ->make()
            ->each(function ($post, $index) use ($user_ids, $category_ids, $faker) {
                // 從會員 ID 數組中隨機取出一個並賦值
                $post->user_id = $faker->randomElement($user_ids);

                // 文章分類，同上
                $post->category_id = $faker->randomElement($category_ids);
            });

        // 將數據集合轉換為數組，並插入到資料庫中
        Post::insert($posts->toArray());
    }

}
