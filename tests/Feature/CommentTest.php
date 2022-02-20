<?php

namespace Tests\Feature;

use App\Http\Livewire\Comments\CommentBox;
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
            ->call('store', 0)
            ->assertForbidden();
    }

    public function test_authenticated_user_can_comment()
    {
        $this->actingAs(User::factory()->create());

        $post = Post::factory()->create();

        $content = 'This is a comment';

        Livewire::test(CommentBox::class, ['postId' => $post->id])
            ->set('content', $content)
            ->call('store', 0);

        $this->assertTrue(Comment::where('content', $content)->exists());
    }

    public function test_authenticated_user_can_reply_the_comment()
    {
        $this->actingAs(User::factory()->create());

        $comment = Comment::factory()->create();

        $content = 'This is a reply';

        Livewire::test(CommentBox::class, ['postId' => $comment->post_id])
            ->set('content', $content)
            ->call('store', $comment->id);

        $this->assertTrue(
            Comment::where('content', $content)
                ->where('parent_id', $comment->id)
                ->exists()
        );
    }
}
