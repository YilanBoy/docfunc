<?php

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

it('can find all images in the post body', function () {
    $fakeImageNames = [
        '2023_01_01_10_18_21_63b0ed6d06d52.jpg',
        '2022_12_30_22_39_21_63aef81999216.jpg',
        '2022_12_31_10_28_00_63af9e3067169.jpg',
    ];

    $body = <<<EOF
        <div id="fake-post-body">
            <img src="https://fake-url.com/images/{$fakeImageNames[0]}" alt="{$fakeImageNames[0]}" title="" style="">
            <img src="https://fake-url.com/images/{$fakeImageNames[1]}" alt="{$fakeImageNames[1]}" title="" style="">
            <img src="https://fake-url.com/images/{$fakeImageNames[2]}" alt="{$fakeImageNames[2]}" title="" style="">
        </div>
    EOF;

    $post = Post::factory()->create(['body' => $body]);

    expect(PostService::imagesInPost($post))
        ->toBeArray()
        ->not->toBeEmpty()
        ->toBe($fakeImageNames);
});

it('will return empty array if no images in the post body', function () {
    $body = <<<'EOF'
        <div id="fake-post-body">
            <p>There is no image in this body</p>
        </div>
    EOF;

    $post = Post::factory()->create(['body' => $body]);

    expect(PostService::imagesInPost($post))
        ->toBeArray()
        ->toBeEmpty();
});
