<?php

namespace App\Livewire;

use App\Livewire\Forms\PostForm;
use App\Models\Category;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePostPage extends Component
{
    use WithFileUploads;

    #[Rule('nullable')]
    #[Rule('image', message: '圖片格式有誤')]
    #[Rule('max:1024', message: '圖片大小不能超過 1024 KB')]
    public ?object $image = null;

    public PostForm $form;

    public string $autoSaveKey = '';

    public Collection $categories;

    public function mount(): void
    {
        $this->autoSaveKey = 'auto_save_user_'.auth()->id().'_create_post';

        $this->form->user_id = auth()->id();

        $this->categories = Category::all(['id', 'name']);

        $this->form->setDataFromAutoSave($this->autoSaveKey);
    }

    // when data update, auto save it to redis
    public function updated(): void
    {
        $this->form->autoSave($this->autoSaveKey);
    }

    public function store(): void
    {
        $this->form->validatePost();

        // upload image
        if ($this->image) {
            $this->form->preview_url = app(FileService::class)->uploadImageToCloud($this->image);
        }

        $post = $this->form->createPost();

        $this->form->clearAutoSave($this->autoSaveKey);

        $this->dispatch('info-badge', status: 'success', message: '成功新增文章！');

        $this->redirect($post->link_with_slug, navigate: true);
    }

    #[Title('新增文章')]
    public function render()
    {
        return view('livewire.create-post-page');
    }
}
