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

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('categoryId', $categoryId)
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

    Livewire::test(CreateForm::class, [
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

    Livewire::test(CreateForm::class, [
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

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('image', $file)
        ->assertHasErrors(['image' => 'max:1024']);
});

it('can upload image', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    $file = UploadedFile::fake()->image('image.jpg');

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', str()->random(4))
        ->set('categoryId', Category::pluck('id')->random())
        // filename will be converted before store to s3
        ->set('image', $file)
        ->set('body', str()->random(500))
        ->call('store')
        ->assertHasNoErrors();

    $post = Post::latest()->first();
    // get the converted filename
    $filename = basename($post->preview_url);

    Storage::disk('s3')->assertExists("preview/{$filename}");
});

it('can\'t upload non image', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    $file = UploadedFile::fake()->create('document.pdf', 512);

    Livewire::test(CreateForm::class, [
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

    Livewire::test(CreateForm::class, [
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

    Livewire::test(CreateForm::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('title', $title)
        ->set('categoryId', $categoryId)
        ->set('tags', $tags)
        ->set('body', $body);

    $this->assertEquals(REDIS_KEY_EXISTS_RETURN_VALUE, Redis::exists('user_'.$user->id.'_post_auto_save'));

    $this->assertEquals(
        [
            'title' => $title,
            'category_id' => (string) $categoryId,
            'tags' => $tags,
            'body' => $body,
        ],
        json_decode(Redis::get($autoSaveKey), true)
    );
});
