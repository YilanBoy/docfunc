<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
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
        Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

        return response()->json([
            'url' => Storage::disk('s3')->url($filePath),
        ]);
    }
}
