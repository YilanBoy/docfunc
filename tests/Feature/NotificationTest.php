<?php

use App\Http\Livewire\Comments\CommentBox;
use App\Http\Livewire\Notifications\Index as NotificationIndex;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

use function Pest\Faker\faker;
use function Pest\Laravel\get;

uses(LazilyRefreshDatabase::class);

test('receive a notification when a post has a comment', function () {
    $post = Post::factory()->create();

    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(CommentBox::class)
        ->set('postId', $post->id)
        ->set('commentCount', $post->comment_count)
        ->set('content', faker()->realText(100))
        ->call('store');

    $author = User::find($post->user_id);

    expect($author->unreadNotifications->count())
        ->toBe(1);

    $this->actingAs($author);

    // assert user can see notification button has a red ping animation
    get(route('root'))
        ->assertSee('<span class="absolute flex w-3 h-3 -mt-1 -mr-1 top-2 right-2">', false)
        ->assertSee('<span class="absolute inline-flex w-full h-full bg-red-400 rounded-full opacity-75 animate-ping"></span>', false)
        ->assertSee('<span class="relative inline-flex w-3 h-3 bg-red-500 rounded-full"></span>', false);

    get(route('notifications.index'))->assertDontSeeText('沒有消息通知！');
});

test('access the notification page to clear unread notifications', function () {
    $post = Post::factory()->create();

    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(CommentBox::class)
        ->set('postId', $post->id)
        ->set('commentCount', $post->comment_count)
        ->set('content', faker()->realText(100))
        ->call('store');

    $author = User::find($post->user_id);

    $this->actingAs($author);

    Livewire::test(NotificationIndex::class);

    $author->refresh();

    expect($author->unreadNotifications->count())
        ->toBe(0);
});

test('if you reply to your own post, there will be no notification', function () {
    $post = Post::factory()->create();

    $author = User::find($post->user_id);

    $this->actingAs($author);

    Livewire::test(CommentBox::class)
        ->set('postId', $post->id)
        ->set('commentCount', $post->comment_count)
        ->set('content', faker()->realText(100))
        ->call('store');

    expect($author->unreadNotifications->count())
        ->toBe(0);

    get(route('root'))
        ->assertDontSee('<span class="absolute flex w-3 h-3 -mt-1 -mr-1 top-2 right-2">', false)
        ->assertDontSee('<span class="absolute inline-flex w-full h-full bg-red-400 rounded-full opacity-75 animate-ping"></span>', false)
        ->assertDontSee('<span class="relative inline-flex w-3 h-3 bg-red-500 rounded-full"></span>', false);
});