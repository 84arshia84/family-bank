<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function store(Request $request)

    {
        $request->validate([
            'amount' => 'required|string|max:100',
            'user_id' => 'required|exists:users,id',

        ]);
        $loan = Loan::create($request->all());
        return response()->json(['The loan application was registered  ' => $loan
        ]);
    }
}
