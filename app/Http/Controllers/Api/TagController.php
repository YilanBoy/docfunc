<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    /**
     * 取得標籤列表
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $tags = Cache::remember('inputTags', now()->addDay(), function () {
            // 傳過去的格式會長這樣
            // [{"id":"2","value":"C#"},{"id":"5","value":"Dart"}]
            return Tag::all()
                ->map(fn ($tag) => ['id' => $tag->id, 'value' => $tag->name])
                ->all();
        });

        return response()->json($tags);
    }
}
