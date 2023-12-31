<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


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




Route::post('fetch-code', 'App\Http\Controllers\ProductController@areacode');


Route::post('check-number', 'App\Http\Controllers\ProductController@check_number');

Route::get('sms-receive', 'App\Http\Controllers\ProductController@sms_receive');








Route::post('fetch-amount', [ProductController::class, 'amount']);


Route::get('/getareacode','App\Http\Controllers\ProductController@areacode');


Route::group(['middleware' => ['throttle:api']], function (){

    Route::post('create-message','App\Http\Controllers\Api\BulkController@submitRequest')->name('api.create.message');
    Route::post('/set-device-status/{device_id}/{status}','App\Http\Controllers\Api\BulkController@setStatus');
    Route::post('/send-webhook/{device_id}','App\Http\Controllers\Api\BulkController@webHook');
    

});