<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reply;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Replies extends Component
{
    use AuthorizesRequests;

    public $post;

    public $content;

    protected $rules = [
        'content' => ['required', 'min:2', 'max:400'],
    ];

    protected $messages = [
        'content.required' => '請填寫回覆內容',
        'content.min' => '回覆內容至少 2 個字元',
        'content.max' => '回覆內容至多 400 個字元',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $this->validate();

        Reply::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->user()->id,
            'content' => $this->content,
        ]);

        $this->content = '';
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);

        $reply->delete();
    }

    public function render()
    {
        return view('livewire.replies', [
            'replies' => $this->post->replies()->latest()->with('user', 'post')->get(),
        ]);
    }

}
