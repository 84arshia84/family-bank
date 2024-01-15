<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ایجاد رول‌ها
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);
        // اضافه کردن رول‌های دیگر
$user->givePermissionTo('user.add', 'user.update', 'user.image', 'user.show.info', 'user.change.password', 'user.Email.update', 'user.profile.img',    'add.Bank.account ',    'loan.add', ' loan.delete', ' Returning.the.deleted.loan', ' Loan.details', ' all.loans.for.user', 'installment.find', 'installment.all', 'Installments.paid', 'installment.Deferred', 'Add.money.to.user.account', 'WalletBalance', 'showUserTransactions', 'showUserTransactionId', 'paySubscription', 'payment.callback', 'showUserPaidInstallments', 'transaction.show', 'Bank.receipt.photo', 'update.status.Bank.receipt.photo');
$admin->givePermissionTo(Permission::all());
    }
}
