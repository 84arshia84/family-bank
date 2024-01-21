<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    public function add_Bank_account(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'sheba_number' => 'required|string',//
            'kart_number' => 'required|string',
            'bank_account_number' => 'required|string'
        ]);

        $BankAccount = BankAccount::updateOrCreate(
            ['user_id' => $user->id], // شرط برای یافتن یا ایجاد رکورد
            [
                'sheba_number' => $request->sheba_number,
                'kart_number' => $request->kart_number,
                'bank_account_number' => $request->bank_account_number
            ] // مقادیر برای بروزرسانی یا ایجاد رکورد
        );

        return response()->json(["add_user" => $BankAccount]);
    }


}
