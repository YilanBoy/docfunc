<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CKSource\CKFinderBridge\Controller\CKFinderController;

class ImageUploadController extends CKFinderController
{
    public function __construct()
    {
        // 設定上傳 S3 的目錄
        config(['ckfinder.resourceTypes.1.directory' => 'images/post/' . date('Ym')]);

        $authenticationMiddleware = config('ckfinder.authentication');

        if (!is_callable($authenticationMiddleware)) {
            if (isset($authenticationMiddleware) && is_string($authenticationMiddleware)) {
                $this->middleware($authenticationMiddleware);
            } else {
                $this->middleware(\CKSource\CKFinderBridge\CKFinderMiddleware::class);
            }
        }
    }
}
