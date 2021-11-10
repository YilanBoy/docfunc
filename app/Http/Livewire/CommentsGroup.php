<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CommentsGroup extends Component
{
    public $post;
    public $offset;
    public $perPage;

    protected $listeners = ['refresh'];

    public function refresh()
    {
        // Refresh comments
    }

    public function render()
    {
        $comments = $this->post->comments()
            // 不撈取子留言
            ->whereNull('parent_id')
            ->latest()
            ->limit($this->perPage)
            ->offset($this->offset)
            ->with(['children' => function ($query) {
                $query->oldest();
            }])
            ->get();

        return view('livewire.comments-group', ['comments' => $comments]);
    }
}
