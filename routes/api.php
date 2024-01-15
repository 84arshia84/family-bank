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
    Route::post('add_user', [UserController::class, 'add_user'])->name('user.add');
    Route::get('all_users', [UserController::class, 'all_users'])->name('user.all');
    Route::post('find_user', [UserController::class, 'find_user'])->name('user.find');
    Route::put('update_user/{id}', [UserController::class, 'update_user'])->name('user.update');
    Route::post('user_image/{id}', [UserController::class, 'user_image'])->name('user.image');
    Route::get('find_user/{id}', [UserController::class, 'find_user'])->name('user.find');
    Route::post('update_status/{id}', [UserController::class, 'update_status'])->name('user.update.status');

    Route::get('show_user_info', [UserController::class, 'show_user_info'])->middleware('auth:sanctum')->name('user.show.info');
    Route::post('change_password', [UserController::class, 'change_password'])->middleware('auth:sanctum')->name('user.change.password');
    Route::post('Email_update_for_user', [UserController::class, 'Email_update_for_user'])->middleware('auth:sanctum')->name('user.Email.update');
    Route::post('profile_for_user', [UserController::class, 'profile_for_user'])->middleware('auth:sanctum')->name('user.profile.img');

    Route::get('show_user_details/{user}', [UserController::class, 'show_user_details'])->name('user.show.details');

});
Route::group(['prefix' => 'bank', 'middleware' => 'auth:sanctum'], function () {
    Route::post('add_Bank_account', [BankAccountController::class, 'add_Bank_account'])->name('add.Bank.account');
});
Route::group(['prefix' => 'loan',], function () {
    Route::post('add_loan', [LoanController::class, 'add_loan'])->middleware('auth:sanctum')->name('loan.add');
    Route::post('delete_loan/{id}', [LoanController::class, 'delete_loan'])->middleware('auth:sanctum')->name('loan.delete');
    Route::get('Returning_the_deleted_loan/{id}', [LoanController::class, 'Returning_the_deleted_loan'])->name('Returning.the.deleted.loan');
    Route::get('List_of_loans', [LoanController::class, 'List_of_loans'])->name('List.of.loans');
    Route::put('update_status_loan/{id}', [LoanController::class, 'update_status_loan'])->name('loan.update.status ');
    Route::post('date_of_loan/{id}', [LoanController::class, 'date_of_loan'])->name('loan.date');
    Route::get('Loan_details/{loan}', [LoanController::class, 'Loan_details'])->middleware('auth:sanctum')->name('Loan.details');
    Route::get('all_loans_for_user', [LoanController::class, 'all_loans_for_user'])->middleware('auth:sanctum')->name('all.loans.for.user');

});

Route::group(['prefix' => 'installment'], function () {

    Route::get('find_installment/{id}', [InstallmentController::class, 'find_installment'])->name('installment.find');
    Route::get('all_installment/{user_id}', [InstallmentController::class, 'all_installment'])->name('installment.all');
    Route::get('Installments_paid', [InstallmentController::class, 'Installments_paid'])->middleware('auth:sanctum')->name('Installments.paid');
    Route::get('Deferred_installments', [InstallmentController::class, 'Deferred_installments'])->middleware('auth:sanctum')->name('installment.Deferred');
    //Route::post('store', [InstallmentController::class, 'store'])->name('store');

});
Route::group(['prefix' => 'wallet'], function () {

//find_installment
    Route::post('wallet/{user}', [WalletController::class, 'wallet'])->name('wallet');
    Route::post('Add_money_to_user_account/{user}', [WalletController::class, 'Add_money_to_user_account'])->name('Add.money.to.user.account');
//    Route::post('harvest_wallet/{user}', [WalletController::class, 'harvest_wallet'])->name('harvest_wallet');
    Route::post('WalletBalance', [WalletController::class, 'WalletBalance'])->middleware('auth:sanctum')->name('WalletBalance');

});

Route::group(['prefix' => 'transaction'], function () {


    Route::post('pay/{id}', [TransactionController::class, 'pay'])->name('pay');;
    Route::get('showUserTransactions', [TransactionController::class, 'showUserTransactions'])->name('showUserTransactions');;
    Route::get('showUserTransactionId', [TransactionController::class, 'showUserTransactionId'])->name('showUserTransactionId');;
    Route::post('paySubscription', [TransactionController::class, 'paySubscription'])->middleware('auth:sanctum')->name('paySubscription');;

    Route::get('/callback/{type}/{installment?}', [TransactionController::class, 'callback'])->name('payment.callback')
        ->whereIn('type', ['installment', 'subscription']);

    Route::get('showUserPaidInstallments/{userId}', [TransactionController::class, 'showUserPaidInstallments'])->name('showUserPaidInstallments');
    Route::get('show/{id}', [TransactionController::class, 'show'])->middleware('auth:sanctum')->name('transaction.show');

    Route::post('Bank_receipt_photo', [TransactionController::class, 'Bank_receipt_photo'])->middleware('auth:sanctum')->name('Bank.receipt.photo');
    Route::post('update_status_Bank_receipt_photo/{id}', [TransactionController::class, 'update_status_Bank_receipt_photo'])->name('update.status.Bank.receipt.photo');
    Route::post('updateBankReceipt/{id}', [TransactionController::class, 'updateBankReceipt'])->name('Bank.update.Receipt');
    Route::get('show_transactions_Bank_receipt_photo/{transaction}', [TransactionController::class, 'show_transactions_Bank_receipt_photo'])->name('show.transactions.Bank.receipt.photo');
    Route::get('getTransactionDetails/{transactionId}', [TransactionController::class, 'getTransactionDetails'])->name('getTransactionDetails');
    Route::get('show_all_transaction', [TransactionController::class, 'show_all_transaction'])->name('transaction.show.all');

});

Route::middleware('auth:sanctum')->prefix('notification')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index']);
    Route::get('/user', [\App\Http\Controllers\NotificationController::class, 'indexUserNotifications']);
    Route::get('/{notification}', [\App\Http\Controllers\NotificationController::class, 'show']);
    Route::post('/', [\App\Http\Controllers\NotificationController::class, 'store']);
    Route::put('/{notification}', [\App\Http\Controllers\NotificationController::class, 'update']);
    Route::delete('/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy']);
});
