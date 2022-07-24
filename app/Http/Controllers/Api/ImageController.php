<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function response;

class ImageController extends Controller
{
    /**
     * 在文章中上傳圖片至 AWS S3 的 API
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                // 檔案最大 512 KB
                'max:512'
            ],
        ]);

        $file = $request->file('upload');
        $imageName = date('Y_m_d_H_i_s') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = 'images/' . $imageName;
        Storage::disk('s3')->put($filePath, file_get_contents($file));

        return response()->json(['url' => Storage::disk('s3')->url($filePath)]);
    }
}
