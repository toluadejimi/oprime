<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AUTH\RegisterController;
use App\Http\Controllers\Frontend as FRONTEND;




Auth::routes();
Route::get('/',  			        [FRONTEND\HomeController::class,'index']);

Route::post('/register-user', 		    [FRONTEND\HomeController::class,'create']);



Route::post('/verify-code', 		    [FRONTEND\HomeController::class,'verify_code']);

Route::get('/resend-code', 		    [FRONTEND\HomeController::class,'resend_code']);

Route::get('/verify', 		    [FRONTEND\HomeController::class,'verify']);

Route::get('/change-email', 		    [FRONTEND\HomeController::class,'change_email']);

Route::post('/email-change', 		    [FRONTEND\HomeController::class,'email_change']);







?>