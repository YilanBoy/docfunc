<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\post;

beforeEach(fn () => Storage::fake());

test('users who are not logged in cannot upload images', function () {
    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(100),
    ])
        ->assertStatus(302)
        ->assertRedirect(route('login'));

    Storage::disk()->assertDirectoryEmpty('images');
});

test('logged-in users can upload images', function () {
    loginAsUser();

    $file = UploadedFile::fake()->image('photo.jpg')->size(100);

    post(route('images.store'), [
        'upload' => $file,
    ])->assertStatus(200);

    expect(Storage::disk()->allFiles())->not->toBeEmpty();
});

test('the size of the uploaded image must be less than 1024 kb', function () {
    loginAsUser();

    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(1025),
    ])->assertStatus(302);

    Storage::disk()->assertDirectoryEmpty('images');
});
