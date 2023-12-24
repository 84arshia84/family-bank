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
        $request->user()->id;
        if ($request->loan_cost <= 10000000) {
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

    public function find_installment(Request $request)
    {
        $installment = Installment::find($request->id);
        return response()->json([
            'find_installment' => $installment,
            'installment_image' => $installment->getMedia()
        ]);
    }

        public function Bank_receipt_photo(Request $request,$loan_id)
    {
        $installment = Installment::findOrFail($loan_id);
        $img = $installment->addMedia($request->image)->toMediaCollection('Bank_receipt_photo' . $loan_id);
        return $img;
    }


}
