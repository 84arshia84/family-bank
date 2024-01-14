<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{


    public function harvest_wallet(Request $request, User $user)
    {
        $request->validate(['amount' => ['required', 'min:1', "max:{$user->balance}", 'integer']]);
        $balance = $user->withdraw($request->amount); // returns the wallet balance: 200.22
        return response()->json("موجودی حساب شما :" . $balance);
    }


    public function Add_money_to_user_account(Request $request, User $user) // افزایش موجودی کیف پول
    {
        $request->validate(['amount' => ['required', 'min:1', 'max:40000000000', 'integer']]);  // اعتبار سنجی درخواست

        $balance = $user->deposit($request->amount); // returns the wallet balance: 200.22
        return response()->json("موجودی حساب شما :" . $balance); // نمایش موجودی کیف پول
    }

    public function WalletBalance()
    {
        $user = Auth::user();
        $transaction = Transaction::where('user_id', $user->id)
            ->where('type', 'subscription')
            ->where('status', 'success')->sum('Price');

        $balance = $user->balance + $transaction;
        $fullName = $user->name . ' ' . $user->family; // بدست آوردن نام و نام خانوادگی کاربر
        return response()->json([
            'balance' => $balance,
            'fullName' => $fullName
        ]);
    }

}

;
