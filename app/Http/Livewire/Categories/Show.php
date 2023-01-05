<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class Show extends Component
{
    public Category $category;

    public function mount(Category $category)
    {
        $this->category = $category;
    }

    public function render()
    {
        // because name is optional, we can't use route parameters
        if ($this->category->name && $this->category->name !== request()->name) {
            redirect()->to($this->category->link_with_name);
        }

        // 傳參變量文章和分類到模板中
        return view('livewire.categories.show', [
            'title' => $this->category->name,
        ]);
    }
}
