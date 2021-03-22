<?php

namespace App\Http\View\Composers;

use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

// Tag Input JSON Format
class TagComposer
{
    public function compose(View $view)
    {
        $inputTags = Cache::remember('inputTags', now()->addDay(), function () {

            $tagsArray = Tag::all()->map(function ($tag) {
                return ['id' => $tag->id, 'value' => $tag->name];
            })->all();

            // 傳過去的格式會長這樣
            // [{"id":"2","value":"C#"},{"id":"5","value":"Dart"}]
            return json_encode($tagsArray, JSON_UNESCAPED_UNICODE);
        });

        $view->with('inputTags', $inputTags);
    }
}
