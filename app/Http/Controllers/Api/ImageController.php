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
    public function __construct(
        protected FileService $fileService
    ) {
    }

    /**
     * upload the image in the post to AWS S3
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => ['required', File::image()->max(1024)],
        ]);

        $file = $request->file('upload');
        $imageName = $this->fileService->generateFileName($file->getClientOriginalExtension());
        Storage::disk('s3')->put('images/'.$imageName, file_get_contents($file));

        return response()->json(['url' => Storage::disk('s3')->url('images/'.$imageName)]);
    }
}
