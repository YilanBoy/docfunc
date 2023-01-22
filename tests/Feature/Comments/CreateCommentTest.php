<?php

use App\Http\Livewire\Comments\CreateModal;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Faker\faker;

uses(RefreshDatabase::class);

test('non-logged-in users can\'t leave a comment', function () {
    $post = Post::factory()->create();

    Livewire::test(CreateModal::class, ['postId' => $post->id])
        ->set('body', faker()->words(5, true))
        ->call('store')
        ->assertForbidden();
});

test('logged-in users can leave a comment', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $body = '# Title'.PHP_EOL;
    $body .= PHP_EOL;
    $body .= 'This is a **comment**';

    Livewire::test(CreateModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->call('store');

    $this->assertDatabaseHas('comments', [
        'body' => $body,
    ]);
});

it('can see the comment preview', function () {
    $this->actingAs(User::factory()->create());

    $post = Post::factory()->create();

    $body = '# Title'.PHP_EOL;
    $body .= PHP_EOL;
    $body .= 'This is a **comment**';

    Livewire::test(CreateModal::class, ['postId' => $post->id])
        ->set('body', $body)
        ->set('convertToHtml', true)
        ->assertSee('<h1>Title</h1>', false)
        ->assertSee('<p>This is a <strong>comment</strong></p>', false);
});
