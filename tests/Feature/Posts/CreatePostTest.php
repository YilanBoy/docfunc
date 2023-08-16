<?php

use App\Http\Livewire\CreatePostPage;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('guest cannot visit create post page', function () {
    get(route('posts.create'))
        ->assertStatus(302)
        ->assertRedirect(route('login'));
});

test('authenticated user can visit create post page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('posts.create'))
        ->assertSuccessful();
});

test('authenticated user can create post', function ($categoryId) {
    $this->actingAs(User::factory()->create());

    $title = str()->random(4);
    $randomString = str()->random(500);

    livewire(CreatePostPage::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('category_id', $categoryId)
        ->set('body', $randomString)
        ->call('store')
        ->assertHasNoErrors();

    expect(Post::latest()->first())
        ->title->toBe($title)
        ->category_id->toBe($categoryId)
        ->body->toBe($randomString);
})->with('defaultCategoryIds');

test('title at least 4 characters', function () {
    $this->actingAs(User::factory()->create());

    livewire(CreatePostPage::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', str()->random(3))
        ->set('category_id', Category::pluck('id')->random())
        ->set('body', str()->random(500))
        ->call('store')
        ->assertHasErrors(['title' => 'min:4']);
});

test('body at least 500 characters', function () {
    $this->actingAs(User::factory()->create());

    livewire(CreatePostPage::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', str()->random(4))
        ->set('category_id', Category::pluck('id')->random())
        ->set('body', str()->random(499))
        ->call('store')
        ->assertHasErrors(['body' => 'min:500']);
});

it('can check image immediately', function () {
    $this->actingAs(User::factory()->create());

    $file = UploadedFile::fake()->image('image.jpg')->size(1025);

    livewire(CreatePostPage::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('image', $file)
        ->assertHasErrors(['image' => 'max:1024']);
});

it('can upload image', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    $file = UploadedFile::fake()->image('fake_image.jpg');

    livewire(CreatePostPage::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', str()->random(4))
        ->set('category_id', Category::pluck('id')->random())
        // filename will be converted before store to s3
        ->set('image', $file)
        ->set('body', str()->random(500))
        ->call('store')
        ->assertHasNoErrors();

    expect(Storage::disk('s3')->allFiles())->not->toBeEmpty();
});

it('can\'t upload non image', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    $file = UploadedFile::fake()->create('document.pdf', 512);

    livewire(CreatePostPage::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', str()->random(4))
        ->set('category_id', Category::pluck('id')->random())
        // filename will be converted before store to s3
        ->set('image', $file)
        ->set('body', str()->random(500))
        ->call('store')
        ->assertHasErrors(['image' => 'image']);
});

it('can get auto save key property', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    livewire(CreatePostPage::class, [
        'categories' => Category::all(['id', 'name']),
    ])->assertSet('autoSaveKey', 'auto_save_user_'.$user->id.'_create_post');
});

it('can auto save the post to cache', function () {
    $user = User::factory()->create();

    $autoSaveKey = 'auto_save_user_'.$user->id.'_create_post';

    // clean the redis data, like refresh database
    if (Cache::has($autoSaveKey)) {
        Cache::pull($autoSaveKey);
    }

    expect(Cache::has($autoSaveKey))->toBeFalse();

    $this->actingAs($user);

    $title = str()->random(4);
    $categoryId = Category::pluck('id')->random();
    $tags = Tag::inRandomOrder()
        ->limit(5)
        ->get()
        ->map(fn($tag) => ['id' => $tag->id, 'value' => $tag->name])
        ->toJson(JSON_UNESCAPED_UNICODE);
    $body = str()->random(500);

    livewire(CreatePostPage::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('category_id', $categoryId)
        ->set('tags', $tags)
        ->set('body', $body);

    expect(Cache::has($autoSaveKey))
        ->toBeTrue()
        ->and(json_decode(Cache::get($autoSaveKey), true))
        // it will compare the order of the arrangement
        ->toBe([
            'category_id' => $categoryId,
            'is_private' => false, // default value
            'title' => $title,
            'tags' => $tags,
            'body' => $body,
        ]);
});
