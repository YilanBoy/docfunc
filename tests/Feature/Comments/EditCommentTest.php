<?php

use App\Livewire\Shared\Comments\CommentCard;
use App\Livewire\Shared\Comments\EditCommentModal;
use App\Models\Comment;
use App\Models\User;

use function Pest\Livewire\livewire;

test('editing modal can load the data of the comment', function () {
    $comment = Comment::factory()->create();

    $offset = 0;

    livewire(EditCommentModal::class)
        ->call('setEditComment', $comment->id, $offset)
        ->assertSet('commentId', $comment->id)
        ->assertSet('body', $comment->body)
        ->assertDispatched('edit-comment-was-set');
});

test('logged-in users can update their comments', function () {
    $oldBody = 'old comment';

    $comment = Comment::factory()->create(['body' => $oldBody]);

    $offset = 0;

    $this->assertDatabaseHas('comments', ['body' => $oldBody]);

    Livewire::actingAs(User::find($comment->user_id));

    $body = 'new comment';

    livewire(EditCommentModal::class)
        ->call('setEditComment', $comment->id, $offset)
        ->set('body', $body)
        ->call('update', $comment->id)
        ->assertDispatched('close-edit-comment-modal')
        ->assertDispatched('comment-updated.'.$comment->id);

    $this->assertDatabaseHas('comments', ['body' => $body]);
});

test('users can\'t update others\' comments', function () {
    $comment = Comment::factory()->create();

    $offset = 0;

    Livewire::actingAs(User::factory()->create());

    $body = 'new comment';

    livewire(EditCommentModal::class)
        ->call('setEditComment', $comment->id, $offset)
        ->set('body', $body)
        ->call('update')
        ->assertForbidden();

    expect(Comment::find($comment->id, ['body']))
        ->body->not->toBe($body);
});

it('can see the comment preview', function () {
    $comment = Comment::factory()->create();

    $offset = 0;

    Livewire::actingAs(User::find($comment->user_id));

    $body = <<<'MARKDOWN'
    # Title

    This is a **comment**

    Show a list

    - item 1
    - item 2
    - item 3
    MARKDOWN;

    livewire(EditCommentModal::class)
        ->call('setEditComment', $comment->id, $offset)
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

it('will display the word "edited" on top of it if it has been edited', function () {
    $comment = Comment::factory()->create();

    // update the updated_at comment
    $comment->touch();

    livewire(CommentCard::class, [
        'postId' => $comment->post_id,
        'postAuthorId' => $comment->post->user_id,
        'commentId' => $comment->id,
        'userId' => $comment->user_id,
        'userGravatarUrl' => get_gravatar($comment->user->email),
        'userName' => $comment->user->name,
        'body' => $comment->body,
        'createdAt' => $comment->created_at,
        'isEdited' => $comment->created_at->ne($comment->updated_at),
        'bookmark' => 'id',
    ])->assertSee(<<<'HTML'
        <span class="text-gray-400">(已編輯)</span>
    HTML, false);
});
