<?php

use Illuminate\Support\Facades\Http;

use function Pest\Laravel\postJson;

test('twitter\'s oembed api can be called', function () {
    $response = [
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

    Http::fake(['https://publish.twitter.com/oembed*' => Http::response($response)]);

    postJson('/api/oembed/twitter', [
        'url' => 'https://twitter.com/TwitterDev/status/1603823063690199040',
        'theme' => 'dark',
    ])
        ->assertStatus(200)
        ->assertJson($response);
});

test('if the embedded twitter link is an invalid link, return the alternative html content', function () {
    Http::fake(['https://publish.twitter.com/oembed*' => Http::response('Not Found', 404)]);

    postJson('/api/oembed/twitter', ['url' => 'https://twitter.com/TwitterDev/status/123456789', 'theme' => 'dark'])
        ->assertStatus(400)
        ->assertJson(['html' => '<p style="font-size:1.5em;">Twitter 連結發生錯誤... 🥲</p>']);
});
