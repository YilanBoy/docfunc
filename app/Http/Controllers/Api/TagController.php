<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $tags = Cache::remember('inputTags', now()->addDay(), function () {
            // 傳過去的格式會長這樣
            // [{"id":"2","value":"C#"},{"id":"5","value":"Dart"}]
            return Tag::all()
                ->map(fn ($tag) => ['id' => $tag->id, 'value' => $tag->name])
                ->all();
        });

        return response()->json($tags, 200);
    }
}
