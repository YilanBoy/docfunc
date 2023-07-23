<?php

use App\Http\Livewire\Users\Information\PostsGroupByYear;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\get;

test('guest can view user profile', function ($tabQueryString) {
    $user = User::factory()->create();

    get(route('users.index', ['user' => $user->id, 'tab' => $tabQueryString]))
        ->assertStatus(200)
        ->assertSeeLivewire(PostsGroupByYear::class);
})->with([
    'information',
    'posts',
    'comments',
]);

test('user can view own profile', function ($tabQueryString) {
    $user = User::factory()->create();

    $this->actingAs($user);

    get(route('users.index', ['user' => $user->id, 'tab' => $tabQueryString]))
        ->assertStatus(200)
        ->assertSeeLivewire(PostsGroupByYear::class);
})->with([
    'information',
    'posts',
    'comments',
]);

test('user can see soft deleted post in posts tab', function () {
    $post = Post::factory()->create();

    $this->actingAs($post->user);

    $post->delete();

    get(route('users.index', ['user' => $post->user->id, 'tab' => 'posts']))
        ->assertSuccessful()
        ->assertSeeText('已刪除');
});

test('guest can\'t see others soft deleted post in posts tab', function () {
    $post = Post::factory()->create();

    $post->delete();

    get(route('users.index', ['user' => $post->user->id, 'tab' => 'posts']))
        ->assertSuccessful()
        ->assertDontSeeText('文章將於6天後刪除');
});
