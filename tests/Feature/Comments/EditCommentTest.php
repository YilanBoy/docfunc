<?php

use App\Livewire\Shared\Comments\CommentCard;
use App\Livewire\Shared\Comments\EditCommentModal;
use App\Models\Comment;
use App\Models\User;

use function Pest\Livewire\livewire;

test('editing modal can load the data of the comment', function () {
    $comment = Comment::factory()->create();

    livewire(EditCommentModal::class)
        ->call('setEditComment', $comment->id)
        ->assertSet('commentId', $comment->id)
        ->assertSet('body', $comment->body)
        ->assertDispatched('edit-comment-was-set');
});

test('logged-in users can update their comments', function () {
    $oldBody = 'old comment';

    $comment = Comment::factory()->create(['body' => $oldBody]);

    $this->assertDatabaseHas('comments', ['body' => $oldBody]);

    loginAsUser($comment->user_id);

    $body = 'new comment';

    livewire(EditCommentModal::class)
        ->call('setEditComment', $comment->id)
        ->set('body', $body)
        ->call('update', $comment->id)
        ->assertDispatched('close-edit-comment-modal')
        ->assertDispatched('comment-updated.'.$comment->id);

    $this->assertDatabaseHas('comments', ['body' => $body]);
});

test('the updated message must be at least 5 characters long', function () {
    $oldBody = 'old comment';

    $comment = Comment::factory()->create(['body' => $oldBody]);

    $this->assertDatabaseHas('comments', ['body' => $oldBody]);

    loginAsUser($comment->user_id);

    $body = str()->random(4);

    livewire(EditCommentModal::class)
        ->call('setEditComment', $comment->id)
        ->set('body', $body)
        ->call('update', $comment->id)
        ->assertHasErrors(['body' => 'min:5'])
        ->assertSeeHtml('<p class="mt-1 text-sm text-red-400">留言內容至少 5 個字元</p>');

    $this->assertDatabaseHas('comments', ['body' => $oldBody]);
});

test('the updated message must be less than 2000 characters', function () {
    $oldBody = 'old comment';

    $comment = Comment::factory()->create(['body' => $oldBody]);

    $this->assertDatabaseHas('comments', ['body' => $oldBody]);

    loginAsUser($comment->user_id);

    $body = str()->random(2001);

    livewire(EditCommentModal::class)
        ->call('setEditComment', $comment->id)
        ->set('body', $body)
        ->call('update', $comment->id)
        ->assertHasErrors(['body' => 'max:2000'])
        ->assertSeeHtml('<p class="mt-1 text-sm text-red-400">留言內容至多 2000 個字元</p>');

    $this->assertDatabaseHas('comments', ['body' => $oldBody]);
});

test('users can\'t update others\' comments', function () {
    $comment = Comment::factory()->create();

    $offset = 0;

    loginAsUser();

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
