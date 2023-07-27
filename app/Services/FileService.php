<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService
{
    public function generateFileName(string $fileExtension): string
    {
        $bytes = random_bytes(6);

        // format: 2023_01_01_10_18_21_63b0ed6d06d5.jpg
        return date('Y_m_d_H_i_s').'_'.bin2hex($bytes).'.'.strtolower($fileExtension);
    }

    public function uploadImageToCloud($image): string
    {
        $imageName = $this->generateFileName($image->getClientOriginalExtension());
        $uploadFilePath = $image->storeAs('preview', $imageName, 's3');

        return Storage::disk('s3')->url($uploadFilePath);
    }
}
