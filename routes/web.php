<?php

use App\Http\Controllers\users;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/user-registration', [users::class, 'userRegistration']);
Route::post('/user-login', [users::class, 'userLogin']);
Route::post('/send-otp', [users::class, 'sendOtp']);
Route::post('/verify-otp', [users::class, 'verifyOtp']);
Route::post('/password-reset', [users::class, 'restPass'])
->middleware([TokenVerificationMiddleware::class]);
