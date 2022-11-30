<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\post;

test('guest can\'t upload image', function () {
    Storage::fake('s3');

    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(100),
    ])
        ->assertStatus(302)
        ->assertRedirect(route('login'));

    Storage::disk('s3')->assertDirectoryEmpty('image');
});

test('login user can upload image', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(100),
    ])->assertStatus(200);
});

test('image must smaller than 512 kb', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(513),
    ])->assertStatus(302);
});
