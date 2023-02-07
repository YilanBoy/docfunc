<?php

use App\Http\Livewire\Comments\CreateModal;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('non-logged-in users can leave a anonymous comment', function () {
    $post = Post::factory()->create();

    $body = <<<'MARKDOWN'
    # Title

    This is a **comment**

    Show a list

    - item 1
    - item 2
    - item 3
    MARKDOWN;

    Livewire::test(CreateModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store')
        ->assertEmitted('closeCreateCommentModal')
        ->assertEmitted('updateCommentCounts')
        ->assertEmitted('refreshAllCommentGroup');

    $this->assertDatabaseHas('comments', [
        'body' => $body,
    ]);
});

test('logged-in users can leave a comment', function () {
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

    Livewire::test(CreateModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store');

    $this->assertDatabaseHas('comments', [
        'body' => $body,
    ]);
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

    Livewire::test(CreateModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->set('convertToHtml', true)
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

    Livewire::test(CreateModal::class, ['postId' => $post->id])
        ->set('body', 'Hello World!')
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store');

    $this->assertDatabaseHas('posts', ['comment_counts' => 1]);
});
