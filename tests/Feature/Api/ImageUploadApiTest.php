<?php

use App\Models\User;
use App\Services\FileService;
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

    $file = UploadedFile::fake()->image('photo.jpg')->size(100);
    $filename = 'fake_converted_image.jpg';

    mock('alias:'.FileService::class)
        ->shouldReceive('generateImageFileName')
        ->once()
        ->andReturn($filename);

    post(route('images.store'), [
        'upload' => $file,
    ])->assertStatus(200);
});

test('image must smaller than 512 kb', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(513),
    ])->assertStatus(302);
});
