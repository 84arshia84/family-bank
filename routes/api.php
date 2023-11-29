<?php

use App\Http\Controllers\Aths\LoginController;
use App\Http\Controllers\Aths\logoutController;
use App\Http\Controllers\Aths\RegisterController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('Auth')->group(function () {

    Route::post('sendVerificationCode', [RegisterController::class, 'sendVerificationCode'])->name('sendVerificationCode');
    Route::post('check', [RegisterController::class, 'check'])->name('check');
    Route::post('Add_password/{id}',[RegisterController::class,'Add_password'])->name('Add_password');
    Route::post('register/{id}', [RegisterController::class, 'register'])->name('register');
    Route::post('login', LoginController::class)->name('login');                // ok
    Route::post('logout', LogoutController::class)->name('logout');
    Route::post('create_user', [RegisterController::class, 'create_user'])->name('create_user');
    Route::post('send_sms', [RegisterController::class, 'send_sms'])->name('send_sms');
    Route::post('store',[RegisterController::class,'store'])->name('store');

});
Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('all_users', [UserController::class, 'all_users'])->name('all_users');
    Route::post('find_user', [UserController::class, 'find_user'])->name('find_user');
    Route::put('update_user/{id}', [UserController::class, 'update_user'])->name('update_user');
    Route::post('add_user', [UserController::class, 'add_user'])->name('add_user');
});


Route::prefix('bank')->group(function () {
    Route::post('add_Bank_account', [BankAccountController::class, 'add_Bank_account'])->name('add_Bank_account');
    Route::post('update_Bank_account/{id}', [BankAccountController::class, 'update_Bank_account'])->name('update_Bank_account');
});
