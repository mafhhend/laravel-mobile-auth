<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Mafhhend\LaravelMobileAuth\Http\Controllers\AuthController;
use Mafhhend\LaravelMobileAuth\LaravelMobileAuth;

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

Route::middleware("guest")->group(function(){

Route::get("login", [AuthController::class, 'login'])->name("laravel_mobile_auth.login");
Route::get("app-login",fn()=> Redirect::to('/login'))->name('login');

Route::get("password-login", [AuthController::class, 'passwordLogin'])->name("laravel_mobile_auth.password");
Route::post("password-login", [AuthController::class, 'passwordCheck'])->name("laravel_mobile_auth.password.check");

Route::get("otp-login", [AuthController::class, 'otpLogin'])->name("laravel_mobile_auth.otp");

Route::post("otp-login", [AuthController::class, 'otpCheck'])->name("laravel_mobile_auth.otp.check");
 
Route::post("auth", [AuthController::class, 'checkAuth'])->name("laravel_mobile_auth.auth");

});

Route::middleware("auth")->group(function () {
    Route::get("dashboard", [AuthController::class, 'dashboard'])->name("laravel_mobile_auth.dashboard");
    Route::get("logout", [AuthController::class, 'logout'])->name("laravel_mobile_auth.logout");
});
