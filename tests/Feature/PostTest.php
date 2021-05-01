<?php

namespace Tests\Feature;

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
        $response = $this->get('/');

        $response->assertStatus(200);
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
