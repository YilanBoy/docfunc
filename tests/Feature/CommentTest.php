<?php

namespace Tests\Feature;

use App\Http\Livewire\Comments\CommentBox;
use App\Http\Livewire\Comments\CommentsGroup;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_comment()
    {
        $post = Post::factory()->create();

        $content = 'This is a comment';

        Livewire::test(CommentBox::class, ['postId' => $post->id])
            ->set('content', $content)
            ->call('store')
            ->assertForbidden();
    }

    public function test_authenticated_user_can_comment()
    {
        $this->actingAs(User::factory()->create());

        $post = Post::factory()->create();

        $content = 'This is a comment';

        Livewire::test(CommentBox::class, ['postId' => $post->id])
            ->set('content', $content)
            ->call('store');

        $this->assertTrue(Comment::where('content', $content)->exists());
    }

    public function test_comment_author_can_delete_own_comment()
    {
        $comment = Comment::factory()->create();

        $this->actingAs(User::find($comment->user_id));

        Livewire::test(CommentsGroup::class, [
            'postId' => $comment->post_id,
            'offset' => 0,
            'perPage' => 10
        ])
            ->call('destroy', $comment->id);

        $this->assertFalse(Comment::where('id', $comment->id)->exists());
    }

    public function test_post_author_can_delete_the_other_user_comment()
    {
        $comment = Comment::factory()->create();

        $this->actingAs(User::find($comment->post->user_id));

        Livewire::test(CommentsGroup::class, [
            'postId' => $comment->post_id,
            'offset' => 0,
            'perPage' => 10
        ])
            ->call('destroy', $comment->id);

        $this->assertFalse(Comment::where('id', $comment->id)->exists());
    }
}
