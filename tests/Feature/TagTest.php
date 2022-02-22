<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    public function test_get_posts_with_specific_tag()
    {
        $tag = Tag::all()->random();

        $response = $this->get(route('tags.show', $tag->id));

        $response->assertSuccessful()
            ->assertSee($tag->name);
    }

    public function test_get_tag_list_api()
    {
        $response = $this->get(route('api.tags'));

        $response->assertStatus(200)
            ->assertJsonCount(Tag::count());
    }
}
