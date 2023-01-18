<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\post;

beforeEach(fn () => Storage::fake('s3'));

test('users who are not logged in cannot upload images', function () {
    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(100),
    ])
        ->assertStatus(302)
        ->assertRedirect(route('login'));

    Storage::disk('s3')->assertDirectoryEmpty('images');
});

test('logged-in users can upload images', function () {
    $this->actingAs(User::factory()->create());

    $file = UploadedFile::fake()->image('photo.jpg')->size(100);

    post(route('images.store'), [
        'upload' => $file,
    ])->assertStatus(200);

    expect(Storage::disk('s3')->allFiles())->not->toBeEmpty();
});

test('the size of the uploaded image must be less than 1024 kb', function () {
    $this->actingAs(User::factory()->create());

    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(1025),
    ])->assertStatus(302);

    Storage::disk('s3')->assertDirectoryEmpty('images');
});
