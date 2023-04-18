<?php

use App\Http\Controllers\Api\Oembed\TwitterOembedApiController;
use App\Http\Controllers\Api\Oembed\YoutubeOembedApiController;
use App\Http\Controllers\Api\ShowAllTagsController;
use App\Http\Controllers\Api\UploadImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});

// Upload the image to S3
Route::middleware('auth:sanctum')
    ->post('images/upload', UploadImageController::class)
    ->name('images.store');

Route::get('tags', ShowAllTagsController::class)->name('api.tags');

Route::post('oembed/twitter', TwitterOembedApiController::class);
Route::post('oembed/youtube', YoutubeOembedApiController::class);
