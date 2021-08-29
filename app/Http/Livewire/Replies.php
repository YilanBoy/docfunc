<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reply;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;

class Replies extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $post;

    protected $listeners = ['refresh'];

    // 刪除留言
    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);

        $reply->delete();

        $this->post->updateReplyCount();
        $this->emit('updateReplyCount');
    }

    public function refresh()
    {
        // Refresh replies
    }

    public function render()
    {
        $replies = $this->post->replies()
            // 不撈取子留言
            ->whereNull('parent_id')
            ->oldest()
            ->with(['children' => function ($query) {
                $query->oldest();
            }])
            ->paginate(10);

        return view('livewire.replies', ['replies' => $replies]);
    }
}
