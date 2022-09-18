<?php

use App\Http\Livewire\Posts\EditForm;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('author can update his post', function ($categoryId) {
    $post = Post::factory()->create();

    $this->actingAs($post->user);

    $newTitle = str()->random(4);
    $newBody = str()->random(500);

    Livewire::test(EditForm::class, ['postId' => $post->id])
        ->set('title', $newTitle)
        ->set('categoryId', $categoryId)
        ->set('body', $newBody)
        ->call('update')
        ->assertHasNoErrors();

    $post->refresh();

    $this->assertEquals($post->title, $newTitle);
    $this->assertEquals($post->category_id, $categoryId);
    $this->assertEquals($post->body, $newBody);
})->with([1, 2, 3]);
