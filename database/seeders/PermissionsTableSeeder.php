<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ایجاد پرمیشن‌ها




        Permission::create(['name' => 'user.add']); // ایجاد کاربر
        Permission::create(['name' => 'user.all']); // لیست کاربران
        Permission::create(['name' => 'user.find']); // جستجوی کاربر
        Permission::create(['name' => 'user.update']); // ویرایش کاربر
        Permission::create(['name' => 'user.image']); // آپلود عکس کاربر

        Permission::create(['name' => 'user.update.status']); // ویرایش وضعیت کاربر
        Permission::create(['name' => 'user.show.info']); // نمایش اطلاعات کاربر
        Permission::create(['name' => 'user.change.password']);    // تغییر رمز عبور کاربر
        Permission::create(['name' => 'user.Email.update']);   // تغییر ایمیل کاربر
        Permission::create(['name' => 'user.show.details']);    // نمایش جزئیات کاربر
        Permission::create(['name' => 'user.profile.img']); // آپلود عکس پروفایل کاربر

        Permission::create(['name' => 'add.Bank.account ']);    // اضافه کردن حساب بانکی

        Permission::create(['name' => 'loan.add']); // اضافه کردن وام
        Permission::create(['name' => 'loan.delete']); // حذف وام
        Permission::create(['name' => 'Returning.the.deleted.loan']);  // بازگردانی وام حذف شده
        Permission::create(['name' => 'List.of.loans']);   // لیست وام‌ها
        Permission::create(['name' => 'loan.update.status']); // ویرایش وضعیت وام
        Permission::create(['name' => 'loan.date']);   // تاریخ وام
        Permission::create(['name' => 'Loan.details']);    // جزئیات وام
        Permission::create(['name' => 'all.loans.for.user']);  // لیست وام‌های کاربر

        Permission::create(['name' => 'installment.find']); // جستجوی قسط
        Permission::create(['name' => 'installment.all']);  // لیست اقساط
        Permission::create(['name' => 'Installments.paid']);    // اقساط پرداخت شده
        Permission::create(['name' => 'installment.Deferred']); // اقساط معوقه

        Permission::create(['name' => 'Add.money.to.user.account']);    // افزایش موجودی کیف پول
        Permission::create(['name' => 'WalletBalance']);    // موجودی کیف پول

        Permission::create(['name' => 'showUserTransactions']);     // لیست تراکنش‌های کاربر
        Permission::create(['name' => 'showUserTransactionId']);    // لیست شناسه تراکنش‌های کاربر
        Permission::create(['name' => 'paySubscription']);  // پرداخت اشتراک
        Permission::create(['name' => 'payment.callback']); // بازگشت از درگاه پرداخت
        Permission::create(['name' => 'showUserPaidInstallments']);     // لیست اقساط پرداخت شده کاربر
        Permission::create(['name' => 'transaction.show']);             // نمایش تراکنش

        Permission::create(['name' => 'Bank.receipt.photo']);        // آپلود عکس رسید بانکی
        Permission::create(['name' => 'update.status.Bank.receipt.photo']); // ویرایش وضعیت عکس رسید بانکی
        Permission::create(['name' => 'show.transactions.Bank.receipt.photo']); // نمایش عکس رسید بانکی
        Permission::create(['name' => 'transaction.show.all']); //  نمایش تراکنش‌ها
        Permission::create(['name' => 'getTransactionDetails']);    // جزئیات تراکنش
        Permission::create(['name' => 'all.loans.for.user.status.accept']);
        Permission::create(['name' => 'all.loans.for.user.status.Pending']);
        Permission::create(['name' => 'displayLoanInformation']);
        Permission::create(['name' => 'Transactions.show.user']);
        Permission::create(['name' => 'user.show.and.bank.info']);

        Permission::create(['name' => 'showPaidLoans']);
        Permission::create(['name' => 'checkAndSetLoanStatus']);
        Permission::create(['name' => 'The.sum.of.users.wallets']);
        Permission::create(['name' => 'TransactionId.show.User']);
        Permission::create(['name' => 'Bank.update.Receipt']);

        // اضافه کردن پرمیشن‌های دیگر
    }
}
