<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/user-registration',[UserController::class,'userRegistration']);
Route::post('/user-login',[UserController::class,'userLogin']);
Route::post('/send-otp',[UserController::class,'sendOTPCode']);
Route::post('/verify-otp',[UserController::class,'verifyOTP']);
//Token verify
Route::post('/reset-password',[UserController::class,'resetPassword'])->
middleware([TokenVerificationMiddleware::class]);
