<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
//    public function installments(Request $request)
//    {
//        $installments = Loan::find($request->id)->get('amount');
//        if ($installments > "10000000") {
//            $installments / 12;
//        } else {
//             $installments / 6;
//        }
//    }

    public function store(Request $request)
    {
        // loan_id
        // loan_cost
        if ($request->loan_cost >= 10000000) {
            $add = 30;
            for ($i = 1; $i <= 6; $i++) {
                Installment::create([
                    "Price" => $request->prise,
                    "date_of_payment" => Carbon::now()->addDay($add),
                    "cost" => $request->cost / 6,
                    "loan_id" => $request->input("loan_id")
                ]);
                $add += 30;
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                Installment::create([
                    "Price" => $request->prise,
                    "date_of_payment" => Carbon::now()->addDay(30),
                    "cost" => $request->cost / 12,
                    "loan_id" => $request->input("loan_id")
                ]);
            }
        }
    }

}
