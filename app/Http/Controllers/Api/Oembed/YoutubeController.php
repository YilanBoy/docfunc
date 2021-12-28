<?php

namespace App\Http\Controllers\Api\Oembed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YoutubeController extends Controller
{
    /**
     * 取得 Youtube Oembed 資訊
     *
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function __invoke(Request $request): Response|JsonResponse
    {
        $apiUrl = 'https://www.youtube.com/oembed?url=' . $request->url;
        $apiUrl .= '&format=json';
        $apiUrl .= '&maxwidth=720';
        $apiUrl .= '&maxheight=480';

        $response = Http::get($apiUrl);

        return $response->successful()
            ? $response
            : response()->json(['html' => '<p style="font-size:1.5em;">Youtube 影片連結發生錯誤... 🥲</p>']);
    }
}
