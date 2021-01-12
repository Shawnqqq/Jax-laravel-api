<?php

// use Illuminate\Http\Request;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
        'namespace' => 'App\Http\Controllers\Api',
        'middleware' => ['auth:web']
    ], function($router) {
    $router->post('/file/qiniu-token', 'FileController@qiniuUploadToken');
    $router->post('/file/qiniu-store', 'FileController@qiniuFileStore');
    $router->get('/districts/all', 'DestrictController@index');
});
