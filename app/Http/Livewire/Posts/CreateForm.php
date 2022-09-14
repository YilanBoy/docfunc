<?php

namespace App\Http\Livewire\Posts;

use App\Http\Traits\LivewirePostForm;
use App\Models\Category;
use Illuminate\Support\Facades\Redis;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * @property string $auto_save_key set by getAutoSaveKeyProperty()
 */
class CreateForm extends Component
{
    use LivewirePostForm;
    use WithFileUploads;

    public $categories;

    public string $title = '';

    public ?int $category_id = null;

    public string $tags = '';

    public $photo;

    public string $body = '';

    public function mount()
    {
        $this->categories = Category::all(['id', 'name']);

        if (Redis::exists($this->auto_save_key)) {
            $autoSavePostData = json_decode(Redis::get($this->auto_save_key), true);

            $this->title = $autoSavePostData['title'];
            $this->category_id = (int) $autoSavePostData['category_id'];
            $this->tags = $autoSavePostData['tags'];
            $this->body = $autoSavePostData['body'];
        }
    }

    // computed property
    public function getAutoSaveKeyProperty(): string
    {
        return 'user_'.auth()->id().'_post_auto_save';
    }

    // when data update, auto save it to redis
    public function updated()
    {
        Redis::set($this->auto_save_key, json_encode(
            [
                'title' => $this->title,
                'category_id' => $this->category_id,
                'tags' => $this->tags,
                'body' => $this->body,
            ], JSON_UNESCAPED_UNICODE)
        );

        // set ttl to 7 days
        Redis::expire($this->auto_save_key, 604_800);
    }

    public function updatedPhoto()
    {
        $this->validatePhoto();
    }

    public function store()
    {
        $this->validatePost();

        $post = $this->createPost();

        Redis::del($this->auto_save_key);

        $this->dispatchBrowserEvent('leaveThePage', ['permit' => true]);

        return redirect()
            ->to($post->link_with_slug)
            ->with('alert', ['status' => 'success', 'message' => '成功新增文章！']);
    }

    public function render()
    {
        return view('livewire.posts.create-form');
    }
}
