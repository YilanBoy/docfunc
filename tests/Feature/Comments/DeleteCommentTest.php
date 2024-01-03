<?php

use App\Livewire\Shared\Comments\CommentGroup;
use App\Models\Comment;
use App\Models\User;

use function Pest\Livewire\livewire;

test('the author can delete his comment', function () {
    $comment = Comment::factory()->create();

    Livewire::actingAs(User::find($comment->user_id));

    livewire(CommentGroup::class, [
        'postId' => $comment->post_id,
        'postAuthorId' => $comment->post->user_id,
        'groupId' => 1,
        'ids' => [$comment->id],
    ])
        ->call('destroy', comment: $comment->id)
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
        'postId' => $comment->post_id,
        'postAuthorId' => $comment->post->user_id,
        'groupId' => 1,
        'ids' => [$comment->id],
    ])
        ->call('destroy', comment: $comment->id)
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
        'postId' => $comment->post_id,
        'postAuthorId' => $comment->post->user_id,
        'groupId' => 1,
        'ids' => [$comment->id],
    ])
        ->call('destroy', comment: $comment->id);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});
