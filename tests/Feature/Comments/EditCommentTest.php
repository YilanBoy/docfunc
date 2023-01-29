<?php

use App\Http\Livewire\Comments\EditModal;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// fake google recaptcha API
beforeEach(function () {
    $fakeResponse = [
        'success' => true,
        'score' => 1,
    ];

    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response($fakeResponse),
    ]);
});

test('editing modal can load the data of the comment', function () {
    $comment = Comment::factory()->create();

    $commentGroupId = 0;

    Livewire::test(EditModal::class)
        ->call('setEditComment', $comment->id, $commentGroupId)
        ->assertSet('groupId', $commentGroupId)
        ->assertSet('commentId', $comment->id)
        ->assertSet('body', $comment->body)
        ->assertEmitted('editCommentWasSet');
});

test('logged-in users can update their comments', function () {
    $oldBody = 'old comment';

    $comment = Comment::factory()->create(['body' => $oldBody]);

    $commentGroupId = 0;

    $this->assertDatabaseHas('comments', ['body' => $oldBody]);

    Livewire::actingAs(User::find($comment->user_id));

    $body = 'new comment';

    Livewire::test(EditModal::class)
        ->call('setEditComment', $comment->id, $commentGroupId)
        ->set('body', $body)
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('update')
        ->assertEmitted('closeEditCommentModal')
        ->assertEmitted('refreshCommentGroup'.$commentGroupId);

    $this->assertDatabaseHas('comments', ['body' => $body]);
});

test('users can\'t update others\' comments', function () {
    $comment = Comment::factory()->create();

    $commentGroupId = 0;

    Livewire::actingAs(User::factory()->create());

    $body = 'new comment';

    Livewire::test(EditModal::class)
        ->call('setEditComment', $comment->id, $commentGroupId)
        ->set('body', $body)
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('update')
        ->assertForbidden();

    expect(Comment::find($comment->id, ['body']))
        ->body->not->toBe($body);
});

it('can see the comment preview', function () {
    $comment = Comment::factory()->create();

    $commentGroupId = 0;

    Livewire::actingAs(User::find($comment->user_id));

    $body = <<<'MARKDOWN'
    # Title

    This is a **comment**

    Show a list

    - item 1
    - item 2
    - item 3
    MARKDOWN;

    Livewire::test(EditModal::class)
        ->call('setEditComment', $comment->id, $commentGroupId)
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
