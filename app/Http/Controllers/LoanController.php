<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{

     public function add_loan(Request $request)
    {
        $request->validate([
            'title_of_loan' => 'required|string|max:100',
            'Price' => 'required|string|max:100',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);
        $loan = Loan::create($request->all());
        return response()->json(['The loan was registered  ' => $loan
        ]);
    }

    public function delete_loan($id)
    {
        $loan = Loan::find($id); // پیدا کردن یک کاربر با شناسه 1
        $loan->delete(); // حذف نرم کاربر
    }

    public function Returning_the_deleted_loan($id)
    {
        $loan = Loan::withTrashed()->find($id); // پیدا کردن یک کاربر با شناسه 1 حتی اگر حذف شده باشد
        $loan->restore(); // بازگرداندن کاربر به حالت قبلی
    }

    public function List_of_loans(Request $request)
    {
        $loan = Loan::all();
        return response()->json([
            $loan
        ]);
    }


}
