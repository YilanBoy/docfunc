<?php

namespace App\Livewire;

use App\Livewire\Traits\PostForm;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePostPage extends Component
{
    use PostForm;
    use WithFileUploads;

    public string $autoSaveKey = '';

    public Collection $categories;

    public function mount(): void
    {
        $this->autoSaveKey = 'auto_save_user_'.auth()->id().'_create_post';
        $this->user_id = auth()->id();

        $this->categories = Category::all(['id', 'name']);

        $this->setDataFromAutoSave($this->autoSaveKey);
    }

    public function updatedImage(): void
    {
        $this->validateImage();

        $this->resetValidation('image');
    }

    // when data update, auto save it to redis
    public function updated(): void
    {
        if ($this->autoSaveKey !== '') {
            $this->autoSave($this->autoSaveKey);
        }
    }

    public function store(): void
    {
        $this->validatePost();

        $post = $this->createPost();

        // delete auto save data
        $this->clearAutoSave($this->autoSaveKey);

        $this->dispatch('info-badge', status: 'success', message: '成功新增文章！');

        $this->redirect($post->link_with_slug, navigate: true);
    }

    #[Title('新增文章')]
    public function render()
    {
        return view('livewire.create-post-page');
    }
}
