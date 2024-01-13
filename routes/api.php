<?php

use App\Http\Controllers\Aths\LoginController;
use App\Http\Controllers\Aths\LogoutController;
use App\Http\Controllers\Aths\RegisterController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
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
    Route::post('store', [RegisterController::class, 'store'])->name('store');


    Route::post('login', [LoginController::class, 'login'])->name('login');                // ok

    Route::middleware('auth:sanctum')->post('logout', [LogoutController::class, 'logout'])->name('logout');

    Route::post('find_user', [RegisterController::class, 'find_user'])->name('find_user');


});
Route::group(['prefix' => 'user'], function () {
    Route::post('add_user', [UserController::class, 'add_user'])->name('add');
    Route::get('all_users', [UserController::class, 'all_users'])->name('all');
    Route::post('find_user', [UserController::class, 'find_user'])->name('find');
    Route::put('update_user/{id}', [UserController::class, 'update_user'])->name('update');
    Route::post('user_image/{id}', [UserController::class, 'user_image'])->name('image');
    Route::get('find_user/{id}', [UserController::class, 'find_user'])->name('find');
    Route::post('update_status/{id}', [UserController::class, 'update_status'])->name('update_status');

    Route::get('show_user_info', [UserController::class, 'show_user_info'])->middleware('auth:sanctum')->name('show_user_info');
    Route::post('change_password', [UserController::class, 'change_password'])->middleware('auth:sanctum')->name('change_password');
    Route::post('Email_update_for_user', [UserController::class, 'Email_update_for_user'])->middleware('auth:sanctum')->name('Email_update_for_user');
    Route::post('profile_for_user', [UserController::class, 'profile_for_user'])->middleware('auth:sanctum')->name('profile_for_user');

    Route::get('show_user_details/{user}', [UserController::class, 'show_user_details'])->name('show_user_details');

});
Route::group(['prefix' => 'bank', 'middleware' => 'auth:sanctum'], function () {
    Route::post('add_Bank_account', [BankAccountController::class, 'add_Bank_account'])->name('add_Bank_account');
});
Route::group(['prefix' => 'loan',], function () {
    Route::post('add_loan', [LoanController::class, 'add_loan'])->middleware('auth:sanctum')->name('add_loan');
    Route::post('delete_loan/{id}', [LoanController::class, 'delete_loan'])->middleware('auth:sanctum')->name('delete_loan');
    Route::get('Returning_the_deleted_loan/{id}', [LoanController::class, 'Returning_the_deleted_loan'])->name('Returning_the_deleted_loan');
    Route::get('List_of_loans', [LoanController::class, 'List_of_loans'])->name('List_of_loans');
    Route::put('update_status_loan/{id}', [LoanController::class, 'update_status_loan'])->name('update_status_loan ');
    Route::post('date_of_loan/{id}', [LoanController::class, 'date_of_loan'])->name('date_of_loan');
    Route::get('Loan_details/{loan}', [LoanController::class, 'Loan_details'])->middleware('auth:sanctum')->name('Loan_details');
    Route::get('all_loans_for_user', [LoanController::class, 'all_loans_for_user'])->middleware('auth:sanctum')->name('all_loans_for_user');

});

Route::group(['prefix' => 'installment'], function () {

    Route::get('find_installment/{id}', [InstallmentController::class, 'find_installment'])->name('find_installment');
    Route::get('all_installment/{user_id}', [InstallmentController::class, 'all_installment'])->name('all_installment');
    Route::get('Installments_paid', [InstallmentController::class, 'Installments_paid'])->name('Installments_paid');
    //Route::post('store', [InstallmentController::class, 'store'])->name('store');

});
Route::group(['prefix' => 'wallet'], function () {

//find_installment
    Route::post('wallet/{user}', [WalletController::class, 'wallet'])->name('wallet');
    Route::post('Add_money_to_user_account/{user}', [WalletController::class, 'Add_money_to_user_account'])->name('Add_money_to_user_account');
//    Route::post('harvest_wallet/{user}', [WalletController::class, 'harvest_wallet'])->name('harvest_wallet');
    Route::post('WalletBalance', [WalletController::class, 'WalletBalance'])->middleware('auth:sanctum')->name('WalletBalance');

});

Route::group(['prefix' => 'transaction'], function () {


    Route::post('pay/{id}', [TransactionController::class, 'pay'])->name('pay');;
    Route::get('showUserTransactions', [TransactionController::class, 'showUserTransactions'])->name('showUserTransactions');;
    Route::get('showUserTransactionId', [TransactionController::class, 'showUserTransactionId'])->name('showUserTransactionId');;
    Route::post('paySubscription', [TransactionController::class, 'paySubscription'])->middleware('auth:sanctum')->name('paySubscription');;

    Route::get('/callback/', [TransactionController::class, 'callback'])->name('payment.callback');
    Route::get('/callback/{type}', [TransactionController::class, 'callback'])->name('payment.callback.subscription');

    Route::get('showUserPaidInstallments/{userId}', [TransactionController::class, 'showUserPaidInstallments'])->name('showUserPaidInstallments');
    Route::get('show/{id}', [TransactionController::class, 'show'])->middleware('auth:sanctum')->name('show');

    Route::post('Bank_receipt_photo', [TransactionController::class, 'Bank_receipt_photo'])->middleware('auth:sanctum')->name('Bank_receipt_photo');
    Route::post('update_status_Bank_receipt_photo/{id}', [TransactionController::class, 'update_status_Bank_receipt_photo'])->name('update_status_Bank_receipt_photo');
    Route::post('updateBankReceipt/{id}', [TransactionController::class, 'updateBankReceipt'])->name('updateBankReceipt');
    Route::get('show_transactions_Bank_receipt_photo/{transaction}', [TransactionController::class, 'show_transactions_Bank_receipt_photo'])->name('show_transactions_Bank_receipt_photo');
    Route::get('getTransactionDetails/{transactionId}', [TransactionController::class, 'getTransactionDetails'])->name('getTransactionDetails');

});
