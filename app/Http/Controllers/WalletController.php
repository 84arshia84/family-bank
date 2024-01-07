<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function Add_money_to_user_account(Request $request, User $user)
    {
        $request->validate(['amount' => ['required', 'min:1', 'max:40000000000', 'integer']]);


        $balance = $user->deposit($request->amount); // returns the wallet balance: 200.22
        return response()->json("موجودی حساب شما :" . $balance);


    }

    public function harvest_wallet(Request $request, User $user)
    {
        $request->validate(['amount' => ['required', 'min:1', "max:{$user->balance}", 'integer']]);
        $balance = $user->withdraw($request->amount); // returns the wallet balance: 200.22
        return response()->json("موجودی حساب شما :" . $balance);
    }

    public function WalletBalance()
    {
    $transaction=Transaction::where('user_id',Auth::id())
        ->where('type','subscription')
        ->where('status','success')->sum('Price');
    return response()->json($transaction);
    }

}

;
