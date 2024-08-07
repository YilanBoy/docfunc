<?php

use App\Livewire\Shared\Posts\Posts;
use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Faker\fake;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('user can access the home page ', function () {
    get('/')->assertStatus(200);
});

test('posts index can be rendered', function () {
    $user = loginAsUser();

    Post::factory(10)->create([
        'category_id' => rand(1, 3),
        'user_id' => $user->id,
    ]);

    get(route('posts.index'))
        ->assertStatus(200)
        ->assertSeeLivewire(Posts::class);
});

it('will be redirect if slug is not in the url', function () {
    $post = Post::factory()->create([
        'slug' => fake()->word(),
    ]);

    get(route('posts.show', ['post' => $post->id]))
        ->assertRedirect();
});

test('category can filter posts', function () {
    $categoryOnePost = Post::factory()->make([
        'title' => 'this post belongs to category one',
        'category_id' => 1,
    ]);

    $categoryOnePost->save();

    livewire(Posts::class, [
        'categoryId' => 1,
        'tagId' => 0,
    ])->assertViewHas('posts', function ($posts) {
        return $posts->count() === 1;
    });

    livewire(Posts::class, [
        'categoryId' => 2,
        'tagId' => 0,
    ])->assertViewHas('posts', function ($posts) {
        return $posts->count() === 0;
    });
});

test('order query string filters correctly', function (string $queryString, string $title) {
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
        'comment_counts' => 10,
        'created_at' => now()->subDays(15),
        'updated_at' => now()->subDays(15),
    ]);

    Livewire::withQueryParams(['order' => $queryString])
        ->test(Posts::class, [
            'categoryId' => 0,
            'tagId' => 0,
        ])
        ->assertViewHas('posts', function ($posts) use ($title) {
            return $posts->first()->title === $title;
        });
})->with([
    ['latest', 'this post is the latest'],
    ['recent', 'this post is updated recently'],
    ['comment', 'this post has the most comments'],
]);

test('user can change order', function (string $order, string $title) {
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
        'comment_counts' => 10,
        'created_at' => now()->subDays(15),
        'updated_at' => now()->subDays(15),
    ]);

    Livewire::test(Posts::class, ['categoryId' => 0, 'tagId' => 0])
        ->call('changeOrder', $order)
        ->assertViewHas('posts', function ($posts) use ($title) {
            return $posts->first()->title === $title;
        });
})->with([
    ['latest', 'this post is the latest'],
    ['recent', 'this post is updated recently'],
    ['comment', 'this post has the most comments'],
]);

test('user can view a post', function () {
    $user = loginAsUser();

    $post = Post::factory()->make();
    $post->user_id = $user->id;
    $post->category_id = 3;
    $post->save();

    get($post->link_with_slug)
        ->assertStatus(200)
        ->assertSee($post->title)
        ->assertSee($post->body);
});

test('author can visit own private post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->make([
        'user_id' => $user->id,
        'is_private' => true,
    ]);

    $post->save();

    $this->actingAs($user)
        ->get($post->link_with_slug)
        ->assertStatus(200)
        ->assertSee($post->title)
        ->assertSee($post->body);
});

test('the private post don\'t show in home page', function () {
    $post = Post::factory()->make([
        'is_private' => true,
    ]);

    $post->save();

    get(route('root'))
        ->assertStatus(200)
        ->assertDontSee($post->title);

    get(route('posts.index'))
        ->assertStatus(200)
        ->assertDontSee($post->title);
});

test('guest can\'t visit the private post', function () {
    $post = Post::factory()->make([
        'is_private' => true,
    ]);

    $post->save();

    get($post->link_with_slug)
        ->assertStatus(403);
});

test('user can\' visit the owner\'s private post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->make([
        'is_private' => true,
    ]);

    $post->save();

    $this->actingAs($user)
        ->get($post->link_with_slug)
        ->assertStatus(403);
});

it('displays the preview image', function () {
    $post = Post::factory()->create();

    get($post->link_with_slug)
        ->assertStatus(200)
        ->assertSee($post->preview_url);
});

it('displays the default preview, assuming the preview is not set', function () {
    $post = Post::factory()->create([
        'preview_url' => '',
    ]);

    $defaultPreviewUrl = 'https://blobs.docfunc.com/share.jpg';

    get($post->link_with_slug)
        ->assertStatus(200)
        ->assertSee($defaultPreviewUrl);
});

test('not showing the thumbnail on top of the post', function () {
    $post = Post::factory()->create([
        'preview_url' => '',
    ]);

    get($post->link_with_slug)
        ->assertOk()
        ->assertDontSee('post-thumbnail');
});

it('displays the thumbnail on top of the post', function () {
    $post = Post::factory()->create([
        'preview_url' => 'https://example.com/preview.jpg',
    ]);

    get($post->link_with_slug)
        ->assertOk()
        ->assertSee('post-thumbnail')
        ->assertSee($post->preview_url);
});
