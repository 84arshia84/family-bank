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
$user->givePermissionTo('loan.add','loan.delete','Loan.details','all.loans.for.user',
    'all.loans.for.user.status.accept','all.loans.for.user.status.Pending','displayLoanInformation',
    'installment.find','installment.all','Installments.paid','installment.Deferred','user.show.info',
    'user.change.password','user.profile.img','user.Email.update','user.profile.img','Transactions.show.user',
    'showUserPaidInstallments','user.show.and.bank.info','paySubscription','transaction.show','WalletBalance',
    'Bank.receipt.photo','add.Bank.account','notification.index.user',
    'notification.show','notification.store','notification.store.for.all.users','notification.store.request.withdrawal',
    'notification.index');
    $admin->givePermissionTo(Permission::all());
    }


}
