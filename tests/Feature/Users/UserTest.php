<?php

use App\Http\Livewire\Users\Information\Posts\Posts;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

use function Pest\Laravel\get;

uses(LazilyRefreshDatabase::class);

test('guest can view user profile', function ($tabQueryString) {
    $user = User::factory()->create();

    get(route('users.index', $user->id).'?tab='.$tabQueryString)
        ->assertStatus(200)
        ->assertSeeLivewire(Posts::class);
})->with([
    'information',
    'posts',
    'comments',
]);

test('user can view own profile', function ($tabQueryString) {
    $user = User::factory()->create();

    $this->actingAs($user);

    get(route('users.index', $user->id).'?tab='.$tabQueryString)
        ->assertStatus(200)
        ->assertSeeLivewire(Posts::class);
})->with([
    'information',
    'posts',
    'comments',
]);

test('user can see soft deleted post in posts tab', function () {
    $post = Post::factory()->create();

    $this->actingAs($post->user);

    $post->delete();

    get(route('users.index', $post->user->id).'?tab=posts')
        ->assertSuccessful()
        ->assertSeeText('文章將於6天後刪除');
});

test('guest can\'t see others soft deleted post in posts tab', function () {
    $post = Post::factory()->create();

    $post->delete();

    get(route('users.index', $post->user->id).'?tab=posts')
        ->assertSuccessful()
        ->assertDontSeeText('文章將於6天後刪除');
});
