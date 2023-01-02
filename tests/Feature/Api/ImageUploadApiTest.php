<?php

use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\post;

beforeEach(fn () => Storage::fake('s3'));

test('guest can\'t upload image', function () {
    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(100),
    ])
        ->assertStatus(302)
        ->assertRedirect(route('login'));

    Storage::disk('s3')->assertDirectoryEmpty('images');
});

test('login user can upload image', function () {
    $this->actingAs(User::factory()->create());

    $file = UploadedFile::fake()->image('photo.jpg')->size(100);
    $filename = 'fake_converted_image.jpg';

    mock('alias:'.FileService::class)
        ->shouldReceive('generateImageFileName')
        ->once()
        ->andReturn($filename);

    post(route('images.store'), [
        'upload' => $file,
    ])->assertStatus(200);

    Storage::disk('s3')->assertExists('images/'.$filename);
});

test('image must smaller than 512 kb', function () {
    $this->actingAs(User::factory()->create());

    post(route('images.store'), [
        'upload' => UploadedFile::fake()->image('photo.jpg')->size(513),
    ])->assertStatus(302);

    Storage::disk('s3')->assertDirectoryEmpty('images');
});
