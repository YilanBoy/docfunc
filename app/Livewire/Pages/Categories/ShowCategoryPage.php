<?php

namespace App\Livewire\Pages\Categories;

use App\Models\Category;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowCategoryPage extends Component
{
    /**
     * @var Category $category
     *
     * use implicit biding, get id from url and set category model
     */
    #[Locked]
    public Category $category;

    public function render(): View
    {
        // because name is optional, we can't use route parameters
        if (! empty($this->category->name) && $this->category->name !== request()->name) {
            redirect()->to($this->category->link_with_name);
        }

        // 傳參變量文章和分類到模板中
        return view('livewire.pages.categories.show-category-page')
            ->title($this->category->name);
    }
}
