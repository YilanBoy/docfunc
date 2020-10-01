{{-- 網頁錯誤訊息通知 --}}
@if ($errors->count() > 0)
    <div class="alert alert-danger">
        <div class="mt-2"><b>有錯誤發生：</b></div>
        <ul class="mt-2 mb-2">
            @foreach ($errors->all() as $error)
                <li><i class="glyphicon glyphicon-remove"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
