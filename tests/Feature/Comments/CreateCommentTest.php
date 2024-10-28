<?php

use App\Livewire\Shared\Comments\CreateCommentModal;
use App\Models\Post;
use App\Models\User;

use function Pest\Faker\fake;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('non-logged-in users can leave a anonymous comment', function () {
    $post = Post::factory()->create();

    $body = fake()->words(5, true);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertDispatched('refresh-root-new-group')
        ->assertDispatched('close-create-comment-modal')
        ->assertDispatched('update-comment-counts')
        ->assertDispatched('info-badge',
            status: 'success',
            message: '成功新增留言！',
        );

    $this->assertDatabaseHas('comments', [
        'body' => $body,
    ]);

    get($post->link_with_slug)
        ->assertSee($body);
});

test('logged-in users can leave a comment', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $body = fake()->words(5, true);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertDispatched('refresh-root-new-group')
        ->assertDispatched('close-create-comment-modal')
        ->assertDispatched('update-comment-counts')
        ->assertDispatched('info-badge',
            status: 'success',
            message: '成功新增留言！',
        );

    $this->assertDatabaseHas('comments', [
        'body' => $body,
    ]);

    get($post->link_with_slug)
        ->assertSee($body);
});

test('the message must be at least 5 characters long', function () {
    $post = Post::factory()->create();

    $body = str()->random(4);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['body' => 'min:5'])
        ->assertSeeHtml('<p class="mt-1 text-sm text-red-400">留言內容至少 5 個字元</p>');
});

test('the message must be less than 2000 characters', function () {
    $post = Post::factory()->create();

    $body = str()->random(2001);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['body' => 'max:2000'])
        ->assertSeeHtml('<p class="mt-1 text-sm text-red-400">留言內容至多 2000 個字元</p>');
});

test('the message must have the captcha token', function () {
    $post = Post::factory()->create();

    $body = fake()->words(5, true);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->call('store')
        ->assertHasErrors()
        ->assertSeeHtml('<p class="mt-1 text-sm text-red-400">未完成驗證</p>');
});

it('can see the comment preview', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $body = <<<'MARKDOWN'
    # Title

    This is a **comment**

    Show a list

    - item 1
    - item 2
    - item 3
    MARKDOWN;

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('captchaToken', 'fake-captcha-response')
        ->set('previewIsEnabled', true)
        ->assertSeeHtmlInOrder([
            '<p>Title</p>',
            '<p>This is a <strong>comment</strong></p>',
            '<p>Show a list</p>',
            '<ul>',
            '<li>item 1</li>',
            '<li>item 2</li>',
            '<li>item 3</li>',
            '</ul>',
        ]);
});

test('when a new comment is added, the post comments will be increased by one', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $this->assertDatabaseHas('posts', ['comment_counts' => 0]);

    livewire(CreateCommentModal::class, ['postId' => $post->id])
        ->set('body', 'Hello World!')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store');

    $this->assertDatabaseHas('posts', ['comment_counts' => 1]);
});
