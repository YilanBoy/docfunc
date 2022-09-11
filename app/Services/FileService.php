<?php

namespace App\Services;

class FileService
{
    public static function generateImageFileName($file): string
    {
        return date('Y_m_d_H_i_s').'_'.uniqid().'.'.$file->getClientOriginalExtension();
    }
}
