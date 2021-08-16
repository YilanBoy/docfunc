<?php

namespace Tests\Feature;

use App\Http\Livewire\Posts;
use App\Models\Category;
use Livewire\Livewire;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_index_can_be_rendered()
    {
        $user = User::factory()->create();

        $posts = Post::factory(10)->create(
            [
                'category_id' => rand(1, 3),
                'user_id' => $user->id,
            ]
        );

        $this->get(route('posts.index'))
            ->assertStatus(200)
            ->assertSeeLivewire('posts');
    }

    public function test_category_can_filter_posts()
    {
        $user = User::factory()->create();

        $categoryOnePost = Post::factory()->make([
            'title' => 'this post belongs to category one',
            'user_id' => $user->id,
            'category_id' => 1,
        ]);

        $categoryOnePost->save();

        Livewire::test(Posts::class)
            ->set('category', Category::first())
            ->assertViewHas('posts', function ($posts) {
                return $posts->count() === 1;
            });

        Livewire::test(Posts::class)
            ->set('category', Category::find(2))
            ->assertViewHas('posts', function ($posts) {
                return $posts->count() === 0;
            });
    }

    public function test_order_query_string_filters_correctly()
    {
        $user = User::factory()->create();

        $recentlyUpdatedPost = Post::factory()->create([
            'title' => 'this post is updated recently',
            'user_id' => $user->id,
            'created_at' => now()->subDays(20),
            'updated_at' => now(),
        ]);

        $latestPost = Post::factory()->create([
            'title' => 'this post is the latest',
            'user_id' => $user->id,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);

        $mostReplyPost = Post::factory()->create([
            'title' => 'this post has the most replies',
            'user_id' => $user->id,
            'reply_count' => 10,
            'created_at' => now()->subDays(15),
            'updated_at' => now()->subDays(15),
        ]);

        Livewire::withQueryParams(['order' => 'latest'])
            ->test(Posts::class)
            ->assertViewHas('posts', function ($posts) {
                return $posts->first()->title === 'this post is the latest';
            });

        Livewire::withQueryParams(['order' => 'recent'])
            ->test(Posts::class)
            ->assertViewHas('posts', function ($posts) {
                return $posts->first()->title === 'this post is updated recently';
            });


        Livewire::withQueryParams(['order' => 'reply'])
            ->test(Posts::class)
            ->assertViewHas('posts', function ($posts) {
                return $posts->first()->title === 'this post has the most replies';
            });
    }

    public function test_user_can_view_a_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->make();
        $post->user_id = $user->id;
        $post->category_id = 3;
        $post->save();

        $this->get('/posts/' . $post->id)
            ->assertStatus(200)
            ->assertSee($post->title)
            ->assertSee($post->body);
    }
}
