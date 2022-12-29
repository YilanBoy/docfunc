<?php

use Illuminate\Support\Facades\Http;

use function Pest\Laravel\postJson;

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

    postJson('/api/oembed/youtube', ['url' => 'https://www.youtube.com/watch?v=M3r2XDceM6A'])
        ->assertStatus(200)
        ->assertJson($fakeResponse);
});

test('embed youtube url is not found', function () {
    Http::fake([
        'https://www.youtube.com/oembed*' => Http::response('Not Found', 404),
    ]);

    postJson('/api/oembed/youtube', ['url' => 'https://www.youtube.com/watch?v=ABCDEFGHIJK'])
        ->assertStatus(400)
        ->assertJson(['html' => '<p style="font-size:1.5em;">Youtube 影片連結發生錯誤... 🥲</p>']);
});

test('twitter oembed api could work', function () {
    $expectResponse = [
        'url' => 'https://twitter.com/TwitterDev/status/1603823063690199040',
        'author_name' => 'Twitter Dev',
        'author_url' => 'https://twitter.com/TwitterDev',
        'html' => "<blockquote class=\"twitter-tweet\" data-theme=\"dark\"><p lang=\"en\" dir=\"ltr\">Testing… Testing… Twitter Dev is back online! 📡<br><br>It’s been a time for change here at Twitter Dev but we have an important message to share with you. 🧵</p>&mdash; Twitter Dev (@TwitterDev) <a href=\"https://twitter.com/TwitterDev/status/1603823063690199040?ref_src=twsrc%5Etfw\">December 16, 2022</a></blockquote>\n",
        'width' => 550,
        'height' => null,
        'type' => 'rich',
        'cache_age' => '3153600000',
        'provider_name' => 'Twitter',
        'provider_url' => 'https://twitter.com',
        'version' => '1.0',
    ];

    postJson('/api/oembed/twitter', ['url' => 'https://twitter.com/TwitterDev/status/1603823063690199040'])
        ->assertStatus(200)
        ->assertJson($expectResponse);
});

test('embed twitter url is not found', function () {
    postJson('/api/oembed/twitter', ['url' => 'https://twitter.com/TwitterDev/status/123456789'])
        ->assertStatus(400)
        ->assertJson(['html' => '<p style="font-size:1.5em;">Twitter 連結發生錯誤... 🥲</p>']);
});
