<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use function response;

class ImageController extends Controller
{
    /**
     * 在文章中上傳圖片至 AWS S3 的 API
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => ['required', File::image()->max(512)],
        ]);

        $file = $request->file('upload');
        $imageName = FileService::generateImageFileName($file);
        Storage::disk('s3')->put('images/'.$imageName, file_get_contents($file));

        return response()->json(['url' => Storage::disk('s3')->url('images/'.$imageName)]);
    }
}
