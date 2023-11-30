<?php

namespace App\Livewire\Pages\Posts;

use App\Livewire\Forms\PostForm;
use App\Models\Category;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

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
        if ($this->form->image) {
            $this->form->preview_url = app(FileService::class)
                ->uploadImageToCloud($this->form->image);
        }

        $post = $this->form->createPost();

        $this->form->clearAutoSave($this->autoSaveKey);

        $this->dispatch('info-badge', status: 'success', message: '成功新增文章！');

        $this->redirect($post->link_with_slug, navigate: true);
    }

    #[Title('新增文章')]
    public function render()
    {
        return view('livewire.pages.posts.create');
    }
}
