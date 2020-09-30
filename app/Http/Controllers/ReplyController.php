<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Auth;

class ReplyController extends Controller
{
    protected $reply;

    public function __construct(Reply $reply)
    {
        $this->middleware('auth');
        $this->reply = $reply;
    }

    public function store(ReplyRequest $request)
    {
        $this->reply->fill($request->validated());
        $this->reply->user_id = Auth::id();
        $this->reply->save();

        return redirect()->to($this->reply->post->linkWithSlug())->with('success', '成功新增評論！');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);

        $reply->delete();

        return redirect()->to($reply->post->linkWithSlug())->with('success', '成功刪除評論！');
    }
}
