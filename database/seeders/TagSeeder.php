<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Random\RandomException;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *
     * @throws RandomException
     */
    public function run(): void
    {
        $tags = Tag::all();

        // 幫每個文章加上 Tag
        Post::all()->each(function ($post) use ($tags) {
            $post->tags()->attach(
                // 隨機取 0 ~ 5  Tag 的 ID
                $tags->random(random_int(0, 5))->pluck('id')->toArray()
            );
        });
    }
}
