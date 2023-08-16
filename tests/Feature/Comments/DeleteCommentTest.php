<?php

use App\Http\Livewire\Components\Comments\Comment;
use App\Models\Comment as CommentModel;
use App\Models\User;

use function Pest\Livewire\livewire;

test('the author can delete his comment', function () {
    $comment = CommentModel::factory()->create();

    Livewire::actingAs(User::find($comment->user_id));

    livewire(Comment::class, [
        'postId' => $comment->post_id,
        'commentId' => $comment->id,
        'userId' => $comment->user_id,
        'userGravatarUrl' => get_gravatar($comment->user->email),
        'userName' => $comment->user->name,
        'body' => $comment->body,
        'createdAtDiffForHuman' => $comment->created_at->diffForHumans(),
        'postUserId' => $comment->post->user->id,
    ])
        ->call('destroy', $comment->id)
        ->assertEmitted('updateCommentCounts')
        ->assertEmitted('refreshAllComments');

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('post author can delete other users comment', function () {
    $comment = CommentModel::factory()->create();

    Livewire::actingAs(User::find($comment->post->user_id));

    livewire(Comment::class, [
        'postId' => $comment->post_id,
        'commentId' => $comment->id,
        'userId' => $comment->user_id,
        'userGravatarUrl' => get_gravatar($comment->user->email),
        'userName' => $comment->user->name,
        'body' => $comment->body,
        'createdAtDiffForHuman' => $comment->created_at->diffForHumans(),
        'postUserId' => $comment->post->user->id,
    ])
        ->call('destroy', $comment->id);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('when a comment is deleted, the post comments will be reduced by one', function () {
    $comment = CommentModel::factory()->create();

    $this->assertDatabaseHas('posts', ['comment_counts' => 1]);

    Livewire::actingAs(User::find($comment->post->user_id));

    livewire(Comment::class, [
        'postId' => $comment->post_id,
        'commentId' => $comment->id,
        'userId' => $comment->user_id,
        'userGravatarUrl' => get_gravatar($comment->user->email),
        'userName' => $comment->user->name,
        'body' => $comment->body,
        'createdAtDiffForHuman' => $comment->created_at->diffForHumans(),
        'postUserId' => $comment->post->user->id,
    ])
        ->call('destroy', $comment->id);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});
