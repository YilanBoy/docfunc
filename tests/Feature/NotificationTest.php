<?php

use App\Livewire\Pages\Notifications\Index;
use App\Livewire\Shared\Comments\CreateCommentModal;
use App\Models\Post;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('you will receive a notification when there is a comment on your post', function () {
    $post = Post::factory()->create();

    $user = User::factory()->create();

    $this->actingAs($user);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', fake()->realText(100))
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store');

    $author = User::find($post->user_id);

    expect($author->unreadNotifications->count())
        ->toBe(1);

    $this->actingAs($author);

    // assert user can see notification button has a red ping animation
    get(route('root'))
        ->assertDontSee(<<<'HTML'
            <span class="absolute flex w-3 h-3 -mt-1 -mr-1 top-2 right-2">
                <span class="absolute inline-flex w-full h-full bg-red-400 rounded-full opacity-75 animate-ping"></span>
                <span class="relative inline-flex w-3 h-3 bg-red-500 rounded-full"></span>
            </span>
        HTML, false);

    get(route('notifications.index'))->assertDontSeeText('沒有消息通知！');
});

test('you can clear unread notifications if you visit the notification page', function () {
    $post = Post::factory()->create();

    $user = User::factory()->create();

    $this->actingAs($user);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', fake()->realText(100))
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store');

    $author = User::find($post->user_id);

    $this->actingAs($author);

    livewire(Index::class);

    $author->refresh();

    expect($author->unreadNotifications->count())
        ->toBe(0);
});

test('if you reply to your own post, there will be no notification', function () {
    $post = Post::factory()->create();

    $author = User::find($post->user_id);

    $this->actingAs($author);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', fake()->realText(100))
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store');

    expect($author->unreadNotifications->count())
        ->toBe(0);

    get(route('root'))
        ->assertDontSee(<<<'HTML'
            <span class="absolute flex w-3 h-3 -mt-1 -mr-1 top-2 right-2">
                <span class="absolute inline-flex w-full h-full bg-red-400 rounded-full opacity-75 animate-ping"></span>
                <span class="relative inline-flex w-3 h-3 bg-red-500 rounded-full"></span>
            </span>
        HTML, false);
});
