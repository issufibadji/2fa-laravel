<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TwoFactorController;
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

Route::get('/login', [AuthController::class,'index'])->name('login');
Route::post('/login', [AuthController::class,'loginAction']);
Route::get('/login-2fa', [AuthController::class,'login2fa'])->name('2fa');
Route::post('/login-2fa', [AuthController::class, 'verify'])->name('2fa.verify');

Route::group(['middleware'=>['auth']],function(){
    Route::get('/logout', [AuthController::class,'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::post('/enable-2fa', [TwoFactorController::class,'enable2Fa'])->name('enable-2fa');
    Route::post('/verify-2fa', [TwoFactorController::class,'verify2Fa'])->name('verify-2fa');
});