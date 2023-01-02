<?php

namespace App\Services;

class FileService
{
    public static function generateImageFileName($file): string
    {
        // format: 2023_01_01_10_18_21_63b0ed6d06d52.jpg
        return date('Y_m_d_H_i_s').'_'.uniqid().'.'.strtolower($file->getClientOriginalExtension());
    }
}
