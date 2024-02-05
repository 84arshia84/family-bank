<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    // Define your methods here
    public function Add_money_to_user_account(Request $request){
        $user = Auth::user(); // گرفتن کاربر فعلی
        $transaction = new Transaction(); // ایجاد یک تراکنش جدید
        $transaction->user_id = $user->id; // اضافه کردن آیدی کاربر به تراکنش
        $transaction->Price = $request->Price; // اضافه کردن مقدار وارد شده توسط کاربر به تراکنش
        $transaction->type = 'subscription'; // اضافه کردن نوع تراکنش به تراکنش
        $transaction->status = 'success'; // اضافه کردن وضعیت تراکنش به تراکنش
        $transaction->save(); // ذخیره تراکنش

        $user->balance = $user->balance + $request->price; // اضافه کردن مبلغ وارد شده توسط کاربر به موجودی کاربر
        $user->save(); // ذخیره کاربر
        return response()->json('success'); // نمایش پیام موفقیت
    }

    public function WalletBalance()
    {
        $user = Auth::user();
        $transaction = Transaction::where('user_id', $user->id) // جمع کردن تراکنش های موفق
            ->where('type', 'subscription') // جمع کردن تراکنش های موفق
            ->where('status', 'success')->sum('Price'); // جمع کردن تراکنش های موفق

        $balance = $user->balance + $transaction;   // محاسبه موجودی کل
        $fullName = $user->name . ' ' . $user->family; // بدست آوردن نام و نام خانوادگی کاربر
        return response()->json([   // نمایش موجودی کیف پول و نام و نام خانوادگی کاربر
            'balance' => $balance,  // نمایش موجودی کیف پول
            'fullName' => $fullName // نمایش نام و نام خانوادگی کاربر
        ]);
    }
    public function all_balance_whit_all_user()
    {
        $users = User::all(); // گرفتن تمام کاربران
        $all_balance = 0; // مقداردهی اولیه متغیر
        foreach ($users as $user) { // حلقه برای گرفتن موجودی تمام کاربران
            $transaction = Transaction::where('user_id', $user->id) // جمع کردن تراکنش های موفق
                ->where('type', 'subscription') // جمع کردن تراکنش های موفق
                ->where('status', 'success')->sum('Price'); // جمع کردن تراکنش های موفق
            $all_balance += $user->balance + $transaction; // محاسبه موجودی کل
        }
        return response()->json($all_balance); // نمایش موجودی کل
    }




}
