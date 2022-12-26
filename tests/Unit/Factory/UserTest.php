<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

it('can change user_id when creating the post', function () {
    $user = User::factory()->create();
    Post::factory()->userId($user->id)->create();

    expect(Post::latest()->first())->user_id->toBe($user->id);
});
