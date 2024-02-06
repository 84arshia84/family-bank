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

    Route::post('find_user', [RegisterController::class, 'find_user'])->name('find.user');


});
Route::group(['prefix' => 'user'], function () {
    Route::post('add_user', [UserController::class, 'add_user'])->name('user.add');
    Route::post('find_user', [UserController::class, 'find_user'])->name('user.find');
    Route::put('update_user/{id}', [UserController::class, 'update_user'])->name('user.update');
    Route::post('user_image/{id}', [UserController::class, 'user_image'])->name('user.image');
    Route::get('find_user/{id}', [UserController::class, 'find_user'])->name('user.find');


});

Route::group(['prefix' => 'loan',], function () {
    Route::get('Returning_the_deleted_loan/{id}', [LoanController::class, 'Returning_the_deleted_loan'])->name('Returning.the.deleted.loan');
    Route::get('showPaidLoans', [LoanController::class, 'showPaidLoans'])->name('showPaidLoans');

});


Route::group(['prefix' => 'transaction'], function () {


    Route::get('/callback/{type}/{installment?}', [TransactionController::class, 'callback'])->name('payment.callback')
        ->whereIn('type', ['installment', 'subscription']);

    Route::get('showUserPaidInstallments/{userId}', [TransactionController::class, 'showUserPaidInstallments'])->name('showUserPaidInstallments');


});

Route::prefix('notification')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index']);
    Route::get('/user', [\App\Http\Controllers\NotificationController::class, 'indexUserNotifications']);
    Route::get('/{notification}', [\App\Http\Controllers\NotificationController::class, 'show']);
    Route::post('/', [\App\Http\Controllers\NotificationController::class, 'store']);
    Route::post('/all-users', [\App\Http\Controllers\NotificationController::class, 'storeForAllUsers']);
//    Route::put('/{notification}', [\App\Http\Controllers\NotificationController::class, 'update']);
//    Route::delete('/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy']);
});


Route::group(['prefix' => 'admin'], function () {

    Route::post('date_of_loan/{id}', [LoanController::class, 'date_of_loan'])->name('loan.date')->middleware(['auth:sanctum','permission:loan.date']);
    Route::put('update_status_loan/{id}', [LoanController::class, 'update_status_loan'])->name('loan.update.status ')->middleware(['auth:sanctum','permission:loan.update.status']);;
    Route::get('List_of_loans', [LoanController::class, 'List_of_loans'])->name('List.of.loans')->middleware(['auth:sanctum','permission:List.of.loans']);;

    Route::get('getTransactionDetails/{transactionId}', [TransactionController::class, 'getTransactionDetails'])->name('getTransactionDetails')->middleware(['auth:sanctum','permission:getTransactionDetails']);;
    Route::get('showUserTransactionId', [TransactionController::class, 'showUserTransactionId'])->name('TransactionId.show.User')->middleware(['auth:sanctum','permission:TransactionId.show.User']);;

    Route::post('update_status/{id}', [UserController::class, 'update_status'])->name('user.update.status')->middleware(['auth:sanctum','permission:user.update.status']);;
    Route::get('show_user_details', [UserController::class, 'show_user_details'])->name('user.show.details')->middleware(['auth:sanctum','permission:user.show.details']);;
    Route::get('all_users', [UserController::class, 'all_users'])->name('user.all')->middleware(['auth:sanctum','permission:user.all']);;

    Route::post('update_status_Bank_receipt_photo/{id}', [TransactionController::class, 'update_status_Bank_receipt_photo'])->name('update.status.Bank.receipt.photo')->middleware(['auth:sanctum','permission:update.status.Bank.receipt.photo']);;
    Route::post('updateBankReceipt/{id}', [TransactionController::class, 'updateBankReceipt'])->name('Bank.update.Receipt')->middleware(['permission:Bank.update.Receipt']);;

    Route::get('show_transactions_Bank_receipt_photo/{transaction}', [TransactionController::class, 'show_transactions_Bank_receipt_photo'])->name('show.transactions.Bank.receipt.photo')->middleware(['auth:sanctum','permission:show.transactions.Bank.receipt.photo']);;

    Route::post('Add_money_to_user_account/{user}', [WalletController::class, 'Add_money_to_user_account'])->name('Add.money.to.user.account')->middleware(['auth:sanctum','permission:Add.money.to.user.account']);

    Route::get('all_loans_for_user', [LoanController::class, 'all_loans_for_user'])->middleware(['auth:sanctum','permission:all.loans.for.user'])->name('auth:sanctum','all.loans.for.user');

    Route::get('all_users_loan_details', [LoanController::class, 'all_users_loan_details'])->name('all.users.loan.details')->middleware(['auth:sanctum','permission:all.users.loan.details']);

    Route::get('The_sum_of_users_wallets', [TransactionController::class, 'The_sum_of_users_wallets'])->name('The.sum.of.users.wallets')->middleware(['auth:sanctum','permission:The.sum.of.users.wallets']);

    Route::get('checkAndSetLoanStatus', [LoanController::class, 'checkAndSetLoanStatus'])->middleware(['auth:sanctum','permission:checkAndSetLoanStatus'])->name('checkAndSetLoanStatus');

    Route::get('show_all_transaction', [TransactionController::class, 'show_all_transaction'])->name('transaction.show.all')->middleware(['auth:sanctum','permission:transaction.show.all']);;

    Route::post('wallet/{user}', [WalletController::class, 'wallet'])->name('wallet')->middleware(['auth:sanctum','permission:wallet']);//find_installment

    Route::get('show_admins', [UserController::class, 'showAdmins'])->name('user.show.admins');

    Route::get('monthlyUserDeposits', [UserController::class, 'monthlyUserDeposits'])->name('monthlyUserDeposits');

    Route::get('showAllInactiveUserBalances', [UserController::class, 'showAllInactiveUserBalances'])->name('showAllInactiveUserBalances');

});

Route::group(['prefix' => 'user'], function () {

    Route::group(['prefix' => 'bank', 'middleware' => 'auth:sanctum'], function () {
        Route::post('add_Bank_account', [BankAccountController::class, 'add_Bank_account'])->name('add.Bank.account')->middleware(['auth:sanctum','permission:add.Bank.account']);
    });
    Route::group(['prefix' => 'loan',], function () {

        Route::post('add_loan', [LoanController::class, 'add_loan'])->middleware(['auth:sanctum','permission:loan.add'])->name('loan.add');

        Route::post('delete_loan/{id}', [LoanController::class, 'delete_loan'])->middleware(['auth:sanctum','permission:loan.delete'])->name('loan.delete');

        Route::get('Loan_details/{loan}', [LoanController::class, 'Loan_details'])->middleware(['auth:sanctum','permission:Loan.details'])->name('Loan.details');

        Route::get('all_loans_for_user', [LoanController::class, 'all_loans_for_user'])->middleware(['auth:sanctum','permission:all.loans.for.user'])->name('all.loans.for.user');

        Route::get('all_loans_for_user_status_accept', [LoanController::class, 'all_loans_for_user_status_accept'])->name('all.loans.for.user.status.accept')->middleware('permission:all.loans.for.user.status.accept');

        Route::get('all_loans_for_user_status_Pending', [LoanController::class, 'all_loans_for_user_status_Pending'])->name('all.loans.for.user.status.Pending')->middleware('permission:all.loans.for.user.status.Pending');

        Route::get('displayLoanInformation/{id}', [LoanController::class, 'displayLoanInformation'])->name('displayLoanInformation')->middleware('permission:displayLoanInformation');
    });

    Route::group(['prefix' => 'installment'], function () {

        Route::get('find_installment/{id}', [InstallmentController::class, 'find_installment'])->name('installment.find')->middleware('permission:installment.find');

        Route::get('all_installment/{user_id}', [InstallmentController::class, 'all_installment'])->name('installment.all')->middleware('permission:installment.all');;

        Route::get('Installments_paid', [InstallmentController::class, 'Installments_paid'])->middleware(['auth:sanctum','permission:Installments.paid'])->name('Installments.paid');

        Route::get('Deferred_installments', [InstallmentController::class, 'Deferred_installments'])->middleware(['auth:sanctum','permission:installment.Deferred'])->name('installment.Deferred');
    });

    Route::group(['prefix' => 'user'], function () {

        Route::get('show_user_info', [UserController::class, 'show_user_info'])->middleware(['auth:sanctum','permission:user.show.info'])->name('user.show.info');

        Route::post('change_password', [UserController::class, 'change_password'])->middleware(['auth:sanctum','permission:user.change.password'])->name('user.change.password');

        Route::post('Email_update_for_user', [UserController::class, 'Email_update_for_user'])->middleware(['auth:sanctum','permission:user.Email.update'])->name('user.Email.update');

        Route::post('profile_for_user', [UserController::class, 'profile_for_user'])->middleware(['auth:sanctum','permission:user.profile.img'])->name('user.profile.img');

        Route::get('show_user_and_bank_info', [UserController::class, 'show_user_and_bank_info'])->middleware(['auth:sanctum','permission:user.show.and.bank.info'])->name('user.show.and.bank.info');
    });

    Route::group(['prefix' => 'transaction'], function () {

        Route::get('showUserPaidInstallments/{userId}', [TransactionController::class, 'showUserPaidInstallments'])->name('showUserPaidInstallments')->middleware(['permission:Transactions.show.user']);

        Route::post('pay/{id}', [TransactionController::class, 'pay'])->name('pay');;

        Route::get('showUserTransactions', [TransactionController::class, 'showUserTransactions'])->name('Transactions.show.user')->middleware(['permission:Transactions.show.user']);;

        Route::post('paySubscription', [TransactionController::class, 'paySubscription'])->middleware(['auth:sanctum','permission:paySubscription'])->name('paySubscription');;

        Route::get('show/{id}', [TransactionController::class, 'show'])->middleware(['auth:sanctum','permission:transaction.show'])->name('transaction.show');

        Route::post('Bank_receipt_photo', [TransactionController::class, 'Bank_receipt_photo'])->middleware(['auth:sanctum','permission:Bank.receipt.photo'])->name('Bank.receipt.photo');
    });

    Route::group(['prefix' => 'wallet'], function () {


        Route::get('WalletBalance', [WalletController::class, 'WalletBalance'])->middleware(['auth:sanctum','permission:WalletBalance'])->name('WalletBalance');

        Route::post('all_balance_whit_all_user', [WalletController::class, 'all_balance_whit_all_user'])->name('all_balance_whit_all_user');

        Route::post('WalletBalance', [WalletController::class, 'WalletBalance'])->middleware(['auth:sanctum','permission:WalletBalance'])->name('WalletBalance');

    });


});

