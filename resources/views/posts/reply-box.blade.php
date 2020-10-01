{{-- 評論回覆 --}}
@include('shared.error')

<div class="reply-box overflow-auto">
    <form action="{{ route('replies.store') }}" method="POST" accept-charset="UTF-8">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <div class="form-group">
            <textarea class="form-control" rows="3" placeholder="分享你的評論~" name="content"></textarea>
        </div>
        <button type="submit" class="btn btn-primary float-right"><i class="fas fa-share mr-2"></i>回覆</button>
    </form>
</div>
<hr>
