<?php

use App\Http\Controllers\Aths\LoginController;
use App\Http\Controllers\Aths\logoutController;
use App\Http\Controllers\Aths\RegisterController;
use App\Http\Controllers\BankAccountController;

use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\LoanController;

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
    Route::post('login', [LoginController::class,'login'])->name('login');                // ok
    Route::middleware('auth:sanctum')->post('logout', [LogoutController::class,'logout'])->name('logout');
    Route::post('create_user', [RegisterController::class, 'create_user'])->name('create_user');
    Route::post('send_sms', [RegisterController::class, 'send_sms'])->name('send_sms');
    Route::post('store',[RegisterController::class,'store'])->name('store');
    Route::post('Add_photo_of_national_card/{id}',[RegisterController::class,'Add_photo_of_national_card'])->name('Add_photo_of_national_card');
    Route::post('find_user',[RegisterController::class,'find_user'])->name('find_user');


});
Route::prefix('users')->group(function () {
    Route::post('add_user', [UserController::class, 'add_user'])->name('add_user');
    Route::get('all_users', [UserController::class, 'all_users'])->name('all_users');
    Route::post('find_user', [UserController::class, 'find_user'])->name('find_user');
    Route::put('update_user/{id}', [UserController::class, 'update_user'])->name('update_user');
    Route::post('add_user', [UserController::class, 'add_user'])->name('add_user');
    Route::post('user_image/{id}', [UserController::class, 'user_image'])->name('user_image');
    Route::get('find_user/{id}',[UserController::class,'find_user'])->name('find_user');
});

Route::prefix('bank')->group(function () {
    Route::post('add_Bank_account', [BankAccountController::class, 'add_Bank_account'])->name('add_Bank_account');
    Route::post('update_Bank_account/{id}', [BankAccountController::class, 'update_Bank_account'])->name('update_Bank_account');

});

Route::prefix('loan')->group(function () {
    Route::post('add_loan', [LoanController::class, 'add_loan'])->name('add_loan');
    Route::post('delete_loan/{id}', [LoanController::class, 'delete_loan'])->name('delete_loan');
    Route::get('Returning_the_deleted_loan/{id}', [LoanController::class, 'Returning_the_deleted_loan'])->name('Returning_the_deleted_loan');
    Route::get('List_of_loans', [LoanController::class, 'List_of_loans'])->name('List_of_loans');
    Route::put('update_status/{id}', [LoanController::class, 'update_status'])->name('update_status');
    Route::post('date_of_loan/{id}', [LoanController::class, 'date_of_loan'])->name('date_of_loan');

});






Route::prefix('installment')->group(function () {
    Route::get('find_installment', [InstallmentController::class, 'find_installment'])->name('find_installment');
    Route::get('store', [InstallmentController::class, 'store'])->name('store');
    Route::post('Bank_receipt_photo/{loan_id}', [InstallmentController::class, 'Bank_receipt_photo'])->name('Bank_receipt_photo');


});

Route::prefix('bank')->group(function () {
    Route::post('add-bank-account', [BankAccountController::class, 'add_Bank_account'])->name('add_Bank_account');
    Route::post('update_Bank_account', [BankAccountController::class, 'add_Bank_account'])->name('add_Bank_account');

});


//find_installment

