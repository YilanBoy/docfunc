<?php

/*
 * CKFinder Configuration File
 *
 * For the official documentation visit http://docs.cksource.com/ckfinder3-php/
 */

/*============================ PHP Error Reporting ====================================*/
// http://docs.cksource.com/ckfinder3-php/debugging.html

// Production
//error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
//ini_set('display_errors', 0);

// Development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

/*============================ General Settings =======================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html

return [
    'loadRoutes' => true,

    'authentication' => '\App\Http\Middleware\CustomCKFinderAuth',

    /*============================ License Key ============================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_licenseKey

    'licenseName' => '',
    'licenseKey' => '',

    /*============================ CKFinder Internal Directory ============================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_privateDir

    'privateDir' => [
        'backend' => 'laravel_cache',
        'tags' => 'ckfinder/tags',
        'cache' => 'ckfinder/cache',
        'thumbs' => 'ckfinder/cache/thumbs',
        'logs' => [
            'backend' => 'laravel_logs',
            'path' => 'ckfinder/logs',
        ],
    ],

    /*============================ Images and Thumbnails ==================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_images

    'images' => [
        'maxWidth' => 1920,
        'maxHeight' => 1080,
        'quality' => 80,
        'sizes' => [
            'small' => ['width' => 480, 'height' => 320, 'quality' => 80],
            'medium' => ['width' => 600, 'height' => 480, 'quality' => 80],
            'large' => ['width' => 800, 'height' => 600, 'quality' => 80],
        ],
    ],

    /*=================================== Backends ========================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_backends

    // The two backends defined below are internal CKFinder backends for cache and logs.
    // Plase do not change these, unless you really want it.
    'backends' => [
        'laravel_cache' => [
            'name' => 'laravel_cache',
            'adapter' => 'local',
            'root' => storage_path('framework/cache'),
        ],
        'laravel_logs' => [
            'name' => 'laravel_logs',
            'adapter' => 'local',
            'root' => storage_path('logs'),
        ],

        // Backends
        'default' => [
            'name' => 'default',
            'adapter' => 'local',
            'baseUrl' => config('app.url') . '/userfiles/',
            'root' => public_path('/userfiles/'),
            'chmodFiles' => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        ],
        'awss3' => [
            'name' => 'awss3',
            'adapter' => 's3',
            'bucket' => env('AWS_BUCKET'),
            'region' => env('AWS_DEFAULT_REGION'),
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'visibility' => 'public',
        ],
    ],

    /*================================ Resource Types =====================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_resourceTypes

    'defaultResourceTypes' => 'Images',

    'resourceTypes' => [
        [
            'name' => 'Files', // Single quotes not allowed.
            'directory' => 'files',
            'maxSize' => 0,
            'allowedExtensions' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
            'deniedExtensions' => '',
            'backend' => 'default',
        ],
        [
            'name' => 'Images',
            // directory 可以在 ImageUploadController 中設定動態目錄
            'directory' => 'images/post',
            'maxSize' => '1M',
            'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
            'deniedExtensions' => '',
            'backend' => 'awss3',
        ],
    ],

    /*================================ Access Control =====================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_roleSessionVar

    'roleSessionVar' => 'CKFinder_UserRole',

    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_accessControl
    'accessControl' => [
        [
            'role' => '*',
            'resourceType' => '*',
            'folder' => '/',

            'FOLDER_VIEW' => true,
            'FOLDER_CREATE' => true,
            'FOLDER_RENAME' => true,
            'FOLDER_DELETE' => true,

            'FILE_VIEW' => true,
            'FILE_UPLOAD' => true,
            'FILE_RENAME' => true,
            'FILE_DELETE' => true,

            'IMAGE_RESIZE' => true,
            'IMAGE_RESIZE_CUSTOM' => true,
        ],
    ],

    /*================================ Other Settings =====================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html

    'overwriteOnUpload' => false,
    'checkDoubleExtension' => true,
    'disallowUnsafeCharacters' => false,
    'secureImageUploads' => true,
    'checkSizeAfterScaling' => true,
    'htmlExtensions' => ['html', 'htm', 'xml', 'js'],
    'hideFolders' => ['.*', 'CVS', '__thumbs'],
    'hideFiles' => ['.*'],
    'forceAscii' => false,
    'xSendfile' => false,

    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_debug
    'debug' => false,

    /*==================================== Plugins ========================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_plugins

    'plugins' => [],

    /*================================ Cache settings =====================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_cache

    'cache' => [
        'imagePreview' => 24 * 3600,
        'thumbnails' => 24 * 3600 * 365,
    ],

    /*============================ Temp Directory settings ================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_tempDirectory

    'tempDirectory' => sys_get_temp_dir(),

    /*============================ Session Cause Performance Issues =======================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_sessionWriteClose

    'sessionWriteClose' => true,

    /*================================= CSRF protection ===================================*/
    // http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_csrfProtection

    'csrfProtection' => true,

    /*============================== End of Configuration =================================*/
];
