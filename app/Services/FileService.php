<?php

namespace App\Services;

use Exception;

class FileService
{
    public static function generateFileName(string $fileExtension): string
    {
        // Create a random string with 12 characters (number + lowercase)
        $randomString = '';
        try {
            // There is a small chance that random_bytes() will throw an error
            $bytes = random_bytes(6);
            $randomString = bin2hex($bytes);
        } catch (Exception) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';

            for ($i = 0; $i < 12; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
        }

        // format: 2023_01_01_10_18_21_63b0ed6d06d5.jpg
        return date('Y_m_d_H_i_s').'_'.$randomString.'.'.strtolower($fileExtension);
    }
}
