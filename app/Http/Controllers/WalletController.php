<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function add_wallet(Request $request, User $user)
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

}

;
