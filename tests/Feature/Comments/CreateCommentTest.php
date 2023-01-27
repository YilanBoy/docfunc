<?php

use App\Http\Livewire\Comments\CreateModal;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Faker\faker;

uses(RefreshDatabase::class);

test('non-logged-in users can\'t leave a comment', function () {
    $post = Post::factory()->create();

    Livewire::test(CreateModal::class, ['postId' => $post->id])
        ->set('body', faker()->words(5, true))
        ->call('store')
        ->assertForbidden();
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
        ->call('store')
        ->assertEmitted('closeCreateCommentModal')
        ->assertEmitted('updateCommentCounts')
        ->assertEmitted('refreshAllCommentGroup');

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
