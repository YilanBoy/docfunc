<?php

use App\Livewire\Shared\Comments\CommentGroup;
use App\Models\Comment;
use App\Models\User;

use function Pest\Livewire\livewire;

test('the author can delete his comment', function () {
    $comment = Comment::factory()->create();

    Livewire::actingAs(User::find($comment->user_id));

    livewire(CommentGroup::class, [
        'maxLayer' => 2,
        'currentLayer' => 1,
        'postId' => $comment->post_id,
        'postAuthorId' => $comment->post->user_id,
        'commentGroupName' => 1,
        'commentIds' => [$comment->id],
    ])
        ->call('destroy', commentId: $comment->id)
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

    livewire(CommentGroup::class, [
        'maxLayer' => 2,
        'currentLayer' => 1,
        'postId' => $comment->post_id,
        'postAuthorId' => $comment->post->user_id,
        'commentGroupName' => 1,
        'commentIds' => [$comment->id],
    ])
        ->call('destroy', commentId: $comment->id)
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

    livewire(CommentGroup::class, [
        'maxLayer' => 2,
        'currentLayer' => 1,
        'postId' => $comment->post_id,
        'postAuthorId' => $comment->post->user_id,
        'commentGroupName' => 1,
        'commentIds' => [$comment->id],
    ])
        ->call('destroy', commentId: $comment->id);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

it('will show alert when user want to delete the deleted comment', function () {
    $comment = Comment::factory()->create();
    $commentId = $comment->id;
    $postId = $comment->post_id;
    $postAuthorId = $comment->post->user_id;

    $comment->delete();

    livewire(CommentGroup::class, [
        'maxLayer' => 2,
        'currentLayer' => 1,
        'postId' => $postId,
        'postAuthorId' => $postAuthorId,
        'commentGroupName' => 1,
        'commentIds' => [$commentId],
    ])
        ->call('destroy', commentId: $commentId)
        ->assertDispatched('info-badge', status: 'danger', message: '該留言已被刪除！');
});
