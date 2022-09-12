<?php

use App\Http\Livewire\Posts\CreateForm;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

test('guest can\'t visit create post page', function () {
    get(route('posts.create'))
        ->assertStatus(302)
        ->assertRedirect(route('login'));
});

test('authenticated user can create post', function ($categoryId) {
    $this->actingAs(User::factory()->create());

    $title = str()->random(4);
    $randomString = str()->random(500);

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('category_id', $categoryId)
        ->set('body', $randomString)
        ->call('store')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('posts', [
        'title' => $title,
        'category_id' => $categoryId,
        'body' => $randomString,
    ]);
})->with([1, 2, 3]);

test('title at least 4 characters', function () {
    $this->actingAs(User::factory()->create());

    $title = str()->random(3);
    $randomString = str()->random(500);

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('category_id', Category::pluck('id')->random())
        ->set('body', $randomString)
        ->call('store')
        ->assertHasErrors(['title' => 'min:4']);
});

test('category id can\'t be null', function () {
    $this->actingAs(User::factory()->create());

    $title = str()->random(4);
    $randomString = str()->random(500);

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('category_id')
        ->set('body', $randomString)
        ->call('store')
        ->assertHasErrors(['category_id' => 'required']);
});

test('body at least 500 characters', function () {
    $this->actingAs(User::factory()->create());

    $title = str()->random(4);
    $randomString = str()->random(499);

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('category_id', Category::pluck('id')->random())
        ->set('body', $randomString)
        ->call('store')
        ->assertHasErrors(['body' => 'min:500']);
});

it('can upload image', function () {
    $this->actingAs(User::factory()->create());

    $title = str()->random(4);
    $randomString = str()->random(500);

    Storage::fake('s3');

    $file = UploadedFile::fake()->image('photo.jpg');

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('category_id', Category::pluck('id')->random())
        // filename will be converted before store to s3
        ->set('photo', $file)
        ->set('body', $randomString)
        ->call('store')
        ->assertHasNoErrors();

    $post = Post::latest()->first();
    // get the converted filename
    $filename = basename($post->preview_url);

    Storage::disk('s3')->assertExists("preview/{$filename}");
});

it('can\' not upload non image', function () {
    $this->actingAs(User::factory()->create());

    $title = str()->random(4);
    $randomString = str()->random(500);

    Storage::fake('s3');

    $file = UploadedFile::fake()->create('document.pdf', 512);

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('category_id', Category::pluck('id')->random())
        // filename will be converted before store to s3
        ->set('photo', $file)
        ->set('body', $randomString)
        ->call('store')
        ->assertHasErrors(['photo' => 'image']);
});
