<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function add_Bank_account(Request $request)
    {
        $request->validate
        ([
                'sheba_number' => 'required|string|max:24',
                'kart_number' => 'required|string|max:4',
                'bank_account_number' => 'required|string|max:16']
        );
        $BankAccount =BankAccount::create($request->all());
        return response()->json(["add_user"=>$BankAccount]);
    }


    public function update_Bank_account(Request $request, $id)
    {
        $bank = BankAccount::find($id);
        $bank->update($request->all());
        $bank->save();
        return response()->json(['update Bank account']);
    }

}
