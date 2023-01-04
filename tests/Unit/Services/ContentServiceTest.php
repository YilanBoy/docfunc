<?php

use App\Models\Post;
use App\Services\ContentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(fn () => $this->contentService = $this->app->make(ContentService::class));

it('can filter the dangerous tag', function () {
    $body = <<<'EOF'
        <body onload="alert('this a xss attack')">
            <script>alert('this a xss attack');</script>
            <button onclick="alert('this an another xss attack')"></button>
            <!-- a=&\#X41 (UTF-8) -->
            <IMG SRC=j&#X41vascript:alert('test2')>
            <span>This normal tag</span>
        </body>
    EOF;

    expect($this->contentService->htmlPurifier($body))
        ->not->toContain('<body onload="alert(\'this a xss attack\')">')
        ->not->toContain('<script>alert(\'this a xss attack\');</script>')
        ->not->toContain('<IMG SRC=j&#X41vascript:alert(\'test2\')>')
        ->toContain('<span>This normal tag</span>');
});

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

    expect($this->contentService->imagesInContent($post->body))
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

    expect($this->contentService->imagesInContent($post->body))
        ->toBeArray()
        ->toBeEmpty();
});
