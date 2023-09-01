<?php

use App\Livewire\Shared\Comments\CommentCard;
use App\Models\Comment;
use App\Models\User;

use function Pest\Livewire\livewire;

test('the author can delete his comment', function () {
    $comment = Comment::factory()->create();

    Livewire::actingAs(User::find($comment->user_id));

    $bookmark = 'id';

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
        'groupId' => $bookmark,
    ])
        ->call('destroy')
        ->assertDispatched('remove-id-from-group-'.$bookmark)
        ->assertDispatched('update-comment-counts')
        ->assertDispatched('info-badge',
            status: 'success',
            message: '成功刪除留言！',
        );

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('post author can delete other users comment', function () {
    $comment = Comment::factory()->create();

    Livewire::actingAs(User::find($comment->post->user_id));

    $bookmark = 'id';

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
        'groupId' => $bookmark,
    ])
        ->call('destroy')
        ->assertDispatched('remove-id-from-group-'.$bookmark)
        ->assertDispatched('update-comment-counts')
        ->assertDispatched('info-badge',
            status: 'success',
            message: '成功刪除留言！',
        );

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('when a comment is deleted, the post comments will be reduced by one', function () {
    $comment = Comment::factory()->create();

    $this->assertDatabaseHas('posts', ['comment_counts' => 1]);

    Livewire::actingAs(User::find($comment->post->user_id));

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
        'groupId' => 'id',
    ])
        ->call('destroy');

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});
