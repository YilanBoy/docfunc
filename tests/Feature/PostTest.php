<?php

namespace Tests\Feature;

use App\Http\Livewire\Posts\Posts;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_posts_index_can_be_rendered()
    {
        $user = User::factory()->create();

        Post::factory(10)->create([
            'category_id' => rand(1, 3),
            'user_id' => $user->id,
        ]);

        $this->get(route('posts.index'))
            ->assertStatus(200)
            ->assertSeeLivewire('posts.posts');
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

        Livewire::test(Posts::class, [
            'currentUrl' => '/',
            'categoryId' => 1,
            'tagId' => 0,
        ])->assertViewHas('posts', function ($posts) {
            return $posts->count() === 1;
        });

        Livewire::test(Posts::class, [
            'currentUrl' => '/',
            'categoryId' => 2,
            'tagId' => 0,
        ])->assertViewHas('posts', function ($posts) {
            return $posts->count() === 0;
        });
    }

    public function test_order_query_string_filters_correctly()
    {
        $user = User::factory()->create();

        Post::factory()->create([
            'title' => 'this post is updated recently',
            'user_id' => $user->id,
            'created_at' => now()->subDays(20),
            'updated_at' => now(),
        ]);

        Post::factory()->create([
            'title' => 'this post is the latest',
            'user_id' => $user->id,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);

        Post::factory()->create([
            'title' => 'this post has the most comments',
            'user_id' => $user->id,
            'comment_count' => 10,
            'created_at' => now()->subDays(15),
            'updated_at' => now()->subDays(15),
        ]);

        $queryStringAndTitle = [
            'latest' => 'this post is the latest',
            'recent' => 'this post is updated recently',
            'comment' => 'this post has the most comments',
        ];

        foreach ($queryStringAndTitle as $queryString => $title) {
            Livewire::withQueryParams(['order' => $queryString])
                ->test(Posts::class, [
                    'currentUrl' => '/',
                    'categoryId' => 0,
                    'tagId' => 0,
                ])
                ->assertViewHas('posts', function ($posts) use ($title) {
                    return $posts->first()->title === $title;
                });
        }
    }

    public function test_user_can_view_a_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->make();
        $post->user_id = $user->id;
        $post->category_id = 3;
        $post->save();

        $this->get('/posts/'.$post->id)
            ->assertStatus(200)
            ->assertSee($post->title)
            ->assertSee($post->body);
    }

    public function test_guest_can_not_visit_create_post_page()
    {
        $this->get(route('posts.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_visit_create_post_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('posts.create'))
            ->assertSuccessful();
    }

    // public function test_guest_can_not_create_post()
    // {
    //     $response = $this->post(route('posts.store'), [
    //         'title' => 'This is a test post title',
    //         'category_id' => 1,
    //     ]);
    //
    //     $response->assertStatus(302)
    //         ->assertRedirect(route('login'));
    //
    //     $this->assertDatabaseCount('posts', 0);
    // }

    // public function test_authenticated_user_can_create_post()
    // {
    //     $user = User::factory()->create();
    //
    //     $randomString = $this->faker->sentence(1000);
    //
    //     $response = $this->actingAs($user)
    //         ->post(route('posts.store'), [
    //             'title' => 'This is a test post title',
    //             'category_id' => 1,
    //             'body' => $randomString,
    //         ]);
    //
    //     $latestPost = Post::latest()->first();
    //
    //     $response->assertStatus(302)
    //         ->assertRedirect(route('posts.show', ['post' => $latestPost->id, 'slug' => $latestPost->slug]));
    //
    //     $this->assertDatabaseHas('posts', [
    //         'title' => 'This is a test post title',
    //         'category_id' => 1,
    //         'body' => $randomString,
    //     ]);
    // }

    // public function test_author_can_update_his_post()
    // {
    //     $post = Post::factory()->create();
    //
    //     $this->actingAs($post->user);
    //
    //     $newPostTitle = 'This is a new test post title';
    //     $newCategoryId = 2;
    //     $randomString = $this->faker->sentence(1000);
    //
    //     $response = $this->put(route('posts.update', ['post' => $post->id]), [
    //         'title' => $newPostTitle,
    //         'category_id' => $newCategoryId,
    //         'body' => $randomString,
    //     ]);
    //
    //     $latestPost = Post::latest()->first();
    //
    //     $response->assertStatus(302)
    //         ->assertRedirect(route('posts.show', ['post' => $latestPost->id, 'slug' => $latestPost->slug]));
    //
    //     $this->assertDatabaseHas('posts', [
    //         'title' => $newPostTitle,
    //         'category_id' => $newCategoryId,
    //         'body' => $randomString,
    //     ]);
    // }

    public function test_author_can_soft_delete_own_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'title' => 'This is a test post title',
            'user_id' => $user->id,
            'category_id' => 1,
        ]);

        $this->actingAs($user)
            ->delete(route('posts.destroy', $post->id))
            ->assertStatus(302)
            ->assertRedirect(route('users.index', ['user' => $user->id, 'tab' => 'posts']));

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_author_can_restore_deleted_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'title' => 'This is a test post title',
            'user_id' => $user->id,
            'category_id' => 1,
            'deleted_at' => now(),
        ]);

        $this->assertSoftDeleted('posts', ['id' => $post->id]);

        $this->actingAs($user)
            ->post(route('posts.restore', ['id' => $post->id]))
            ->assertStatus(302)
            ->assertRedirect(route('users.index', ['user' => $user->id, 'tab' => 'posts']));

        $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_author_can_force_delete_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'title' => 'This is a test post title',
            'user_id' => $user->id,
            'category_id' => 1,
        ]);

        $this->assertDatabaseHas('posts', ['id' => $post->id]);

        $this->actingAs($user)
            ->delete(route('posts.forceDelete', ['id' => $post->id]))
            ->assertStatus(302)
            ->assertRedirect(route('users.index', ['user' => $user->id, 'tab' => 'posts']));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_prune_the_stale_post()
    {
        $user = User::factory()->create();

        Post::factory()->create([
            'title' => 'This is a stale post',
            'user_id' => $user->id,
            'category_id' => 1,
            'deleted_at' => now()->subDays(31),
        ]);

        Post::factory()->create([
            'title' => 'This is a normal post',
            'user_id' => $user->id,
            'category_id' => 1,
        ]);

        $this->artisan('model:prune');

        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseHas('posts', [
            'title' => 'This is a normal post',
            'category_id' => 1,
        ]);
    }
}
