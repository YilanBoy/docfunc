<?php

use App\Http\Livewire\Posts\CreateForm;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redis;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

const REDIS_KEY_EXISTS_RETURN_VALUE = 1;

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

    livewire(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('categoryId', $categoryId)
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

    livewire(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', str()->random(3))
        ->set('categoryId', Category::pluck('id')->random())
        ->set('body', str()->random(500))
        ->call('store')
        ->assertHasErrors(['title' => 'min:4']);
});

test('body at least 500 characters', function () {
    $this->actingAs(User::factory()->create());

    livewire(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', str()->random(4))
        ->set('categoryId', Category::pluck('id')->random())
        ->set('body', str()->random(499))
        ->call('store')
        ->assertHasErrors(['body' => 'min:500']);
});

it('can check image immediately', function () {
    $this->actingAs(User::factory()->create());

    $file = UploadedFile::fake()->image('image.jpg')->size(1025);

    livewire(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('image', $file)
        ->assertHasErrors(['image' => 'max:1024']);
});

it('can upload image', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    $file = UploadedFile::fake()->image('fake_image.jpg');

    livewire(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', str()->random(4))
        ->set('categoryId', Category::pluck('id')->random())
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

    livewire(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', str()->random(4))
        ->set('categoryId', Category::pluck('id')->random())
        // filename will be converted before store to s3
        ->set('image', $file)
        ->set('body', str()->random(500))
        ->call('store')
        ->assertHasErrors(['image' => 'image']);
});

it('can get auto save key property', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    livewire(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])->assertSet('auto_save_key', 'user_'.$user->id.'_post_auto_save');
});

it('can auto save the post to redis', function () {
    $user = User::factory()->create();

    $autoSaveKey = 'user_'.$user->id.'_post_auto_save';

    // clean the redis data, like refresh database
    if (Redis::exists($autoSaveKey)) {
        Redis::del($autoSaveKey);
    }

    $this->actingAs($user);

    $title = str()->random(4);
    $categoryId = Category::pluck('id')->random();
    $tags = Tag::inRandomOrder()
        ->limit(5)
        ->get()
        ->map(fn ($tag) => ['id' => $tag->id, 'value' => $tag->name])
        ->toJson(JSON_UNESCAPED_UNICODE);
    $body = str()->random(500);

    livewire(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('categoryId', $categoryId)
        ->set('tags', $tags)
        ->set('body', $body);

    expect(Redis::exists('user_'.$user->id.'_post_auto_save'))
        ->toBe(REDIS_KEY_EXISTS_RETURN_VALUE)
        ->and(json_decode(Redis::get($autoSaveKey), true))
        ->toBe([
            'title' => $title,
            'category_id' => $categoryId,
            'tags' => $tags,
            'body' => $body,
        ]);
});
