<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = Tag::all();

        // 幫每個文章加上 Tag
        Post::all()->each(function ($post) use ($tags) {
            $post->tags()->attach(
                // 隨機取 0 ~ 5  Tag 的 ID
                $tags->random(rand(0, 5))->pluck('id')->toArray()
            );
        });
    }
}
