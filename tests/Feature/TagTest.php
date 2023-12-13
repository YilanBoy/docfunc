<?php

use App\Models\Tag;

use function Pest\Laravel\get;

test('guest can visit tag show page', function () {
    $tag = Tag::all()->random();

    get(route('tags.show', $tag->id))
        ->assertSuccessful()
        ->assertSee($tag->name);
});

it('can get all tags')
    ->getJson('api/tags')
    ->assertStatus(200);
