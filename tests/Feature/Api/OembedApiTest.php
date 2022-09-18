<?php

use Illuminate\Support\Facades\Http;

test('youtube oembed api could work', function () {
    $fakeResponse = [
        'title' => 'Amazing Nintendo Facts',
        'author_name' => 'ZackScott',
        'author_url' => 'https://www.youtube.com/c/ZackScott',
        'type' => 'video',
        'height' => 113,
        'width' => 200,
        'version' => '1.0',
        'provider_name' => 'YouTube',
        'provider_url' => 'https://www.youtube.com/',
        'thumbnail_height' => 360,
        'thumbnail_width' => 480,
        'thumbnail_url' => 'https://i.ytimg.com/vi/M3r2XDceM6A/hqdefault.jpg',
        'html' => '<iframe width="200" height="113" src="https://www.youtube.com/embed/M3r2XDceM6A?feature=oembed" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen title="Amazing Nintendo Facts"></iframe>',
    ];

    Http::fake([
        'https://www.youtube.com/oembed*' => Http::response($fakeResponse),
    ]);

    $response = $this->postJson('/api/oembed/youtube', ['url' => 'https://www.youtube.com/watch?v=M3r2XDceM6A']);

    $response
        ->assertStatus(200)
        ->assertJson($fakeResponse);
});

test('embed youtube url is not found', function () {
    Http::fake([
        'https://www.youtube.com/oembed*' => Http::response('Not Found', 404),
    ]);

    $response = $this->postJson('/api/oembed/youtube', ['url' => 'https://www.youtube.com/watch?v=ABCDEFGHIJK']);

    $response
        ->assertStatus(404)
        ->assertJson(['html' => '<p style="font-size:1.5em;">Youtube 影片連結發生錯誤... 🥲</p>']);
});
