<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reply;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Replies extends Component
{
    use AuthorizesRequests;

    public $post;

    protected $listeners = ['refresh'];

    // 刪除回覆
    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);

        $reply->delete();

        $reply->post->updateReplyCount();
    }

    public function refresh()
    {
        // Refresh replies
    }

    public function render()
    {
        $replies = $this->post->replies()
            // 不撈取子回覆
            ->whereNull('parent_id')
            ->oldest()
            ->with(['children' => function ($query) {
                $query->oldest();
            }])
            ->get();

        return view('livewire.replies', ['replies' => $replies]);
    }
}
