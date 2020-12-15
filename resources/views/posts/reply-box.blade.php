{{-- 評論回覆 --}}
<div class="card shadow mb-4">
    <div class="card-body p-4">
        <form action="{{ route('replies.store') }}" method="POST" accept-charset="UTF-8">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">

            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="content"
                name="content" id="floatingTextarea"
                style="height: 100px"></textarea>
                <label for="floatingTextarea">分享你的評論~</label>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-share"></i> 回覆</button>
            </div>
        </form>
    </div>
</div>

