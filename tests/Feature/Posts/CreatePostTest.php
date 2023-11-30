<?php

use App\Livewire\Pages\Posts\Create;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Services\ContentService;
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
    $body = str()->random(500);
    $privateStatus = (bool) rand(0, 1);

    $tagCollection = Tag::factory()->count(3)->create();

    $tagsJson = $tagCollection
        ->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])
        ->toJson();

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.title', $title)
        ->set('form.category_id', $categoryId)
        ->set('form.tags', $tagsJson)
        ->set('form.body', $body)
        ->set('form.is_private', $privateStatus)
        ->call('store')
        ->assertHasNoErrors();

    $contentService = app(ContentService::class);

    $post = Post::latest()->first();

    $tagIdsArray = $tagCollection
        ->map(fn ($item) => $item->id)
        ->all();

    expect($post)
        ->title->toBe($title)
        ->slug->toBe($contentService->makeSlug($title))
        ->category_id->toBe($categoryId)
        ->excerpt->toBe($contentService->makeExcerpt($body))
        ->body->toBe($body)
        ->is_private->toBe($privateStatus)
        ->and($post->tags->pluck('id')->toArray())->toBe($tagIdsArray);
})->with('defaultCategoryIds');

test('title at least 4 characters', function () {
    $this->actingAs(User::factory()->create());

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.title', str()->random(3))
        ->set('form.category_id', Category::pluck('id')->random())
        ->set('form.body', str()->random(500))
        ->call('store')
        ->assertHasErrors(['title' => 'min:4']);
});

test('title at most 50 characters', function () {
    $this->actingAs(User::factory()->create());

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.title', str()->random(51))
        ->set('form.category_id', Category::pluck('id')->random())
        ->set('form.body', str()->random(500))
        ->call('store')
        ->assertHasErrors(['title' => 'max:100']);
});

test('body at least 500 characters', function () {
    $this->actingAs(User::factory()->create());

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.title', str()->random(4))
        ->set('form.category_id', Category::pluck('id')->random())
        ->set('form.body', str()->random(499))
        ->call('store')
        ->assertHasErrors(['body' => 'min:500']);
});

test('body at most 20000 characters', function () {
    $this->actingAs(User::factory()->create());

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.title', str()->random(4))
        ->set('form.category_id', Category::pluck('id')->random())
        ->set('form.body', str()->random(20001))
        ->call('store')
        ->assertHasErrors(['body' => 'max:20000']);
});

it('can check image type', function () {
    $this->actingAs(User::factory()->create());

    $file = UploadedFile::fake()->create('document.pdf', 512);

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.image', $file)
        ->assertHasErrors('form.image');
});

it('can check image size', function () {
    $this->actingAs(User::factory()->create());

    $file = UploadedFile::fake()->image('image.jpg')->size(1025);

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.image', $file)
        ->assertHasErrors('form.image');
});

it('can upload image', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    $image = UploadedFile::fake()->image('fake_image.jpg');

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.title', str()->random(4))
        ->set('form.category_id', Category::pluck('id')->random())
        // filename will be converted before store to s3
        ->set('form.image', $image)
        ->set('form.body', str()->random(500))
        ->call('store')
        ->assertHasNoErrors()
        ->assertSeeHtml('id="upload-image"');

    expect(Storage::disk('s3')->allFiles())->not->toBeEmpty();
});

it('can\'t upload non image', function () {
    $this->actingAs(User::factory()->create());

    Storage::fake('s3');

    $file = UploadedFile::fake()->create('document.pdf', 512);

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.title', str()->random(4))
        ->set('form.category_id', Category::pluck('id')->random())
        // filename will be converted before store to s3
        ->set('form.image', $file)
        ->set('form.body', str()->random(500))
        ->call('store')
        ->assertHasErrors(['form.image'])
        ->assertDontSeeHtml('id="upload-image"');
});

it('can get auto save key property', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    livewire(Create::class, [
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
        ->map(fn ($tag) => ['id' => $tag->id, 'value' => $tag->name])
        ->toJson(JSON_UNESCAPED_UNICODE);
    $body = str()->random(500);

    livewire(Create::class, [
        'categories' => Category::all(['id', 'name']),
    ])
        ->set('form.title', $title)
        ->set('form.category_id', $categoryId)
        ->set('form.tags', $tags)
        ->set('form.body', $body);

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
