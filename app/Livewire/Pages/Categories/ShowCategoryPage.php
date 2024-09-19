<?php

namespace App\Livewire\Pages\Categories;

use App\Models\Category;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowCategoryPage extends Component
{
    #[Locked]
    public int $categoryId;

    public function render(): View
    {
        $category = Category::find($this->categoryId);

        // because name is optional, we can't use route parameters
        if (! empty($category->name) && $category->name !== request()->name) {
            redirect()->to($category->link_with_name);
        }

        // 傳參變量文章和分類到模板中
        return view(
            'livewire.pages.categories.show-category-page',
            compact('category'),
        )->title($category->name);
    }
}
