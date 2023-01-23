<?php

use App\Http\Livewire\Comments\EditModal;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

    $this->actingAs(User::find($comment->user_id));

    $body = 'new comment';

    Livewire::test(EditModal::class)
        ->call('setEditComment', $comment->id, $commentGroupId)
        ->set('body', $body)
        ->call('update', $comment->id)
        ->assertEmitted('closeEditCommentModal')
        ->assertEmitted('refreshCommentGroup'.$commentGroupId);

    $this->assertDatabaseHas('comments', ['body' => $body]);
});

test('users can\'t update others\' comments', function () {
    $comment = Comment::factory()->create();

    $commentGroupId = 0;

    $this->actingAs(User::factory()->create());

    $body = 'new comment';

    Livewire::test(EditModal::class)
        ->call('setEditComment', $comment->id, $commentGroupId)
        ->set('body', $body)
        ->call('update', $comment->id)
        ->assertForbidden();

    expect(Comment::find($comment->id, ['body']))
        ->body->not->toBe($body);
});

it('can see the comment preview', function () {
    $comment = Comment::factory()->create();

    $commentGroupId = 0;

    $this->actingAs(User::find($comment->user_id));

    $body = '# Title'.PHP_EOL;
    $body .= PHP_EOL;
    $body .= 'This is a **comment**';

    Livewire::test(EditModal::class)
        ->call('setEditComment', $comment->id, $commentGroupId)
        ->set('body', $body)
        ->set('convertToHtml', true)
        ->assertSee('Title', false)
        ->assertSee('<p>This is a <strong>comment</strong></p>', false);
});
