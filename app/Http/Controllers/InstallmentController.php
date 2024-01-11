<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use App\Models\User;
use Auth;
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

    public function store(Request $request, $loan_id)
    {
        // loan_id
        // loan_cost
        $request->user()->id;
        // پیدا کردن وام با شناسه درخواست
        $loan = Loan::findOrFail($loan_id);
        // گرفتن تاریخ وام از مدل وام
        $date_of_loan = $loan->date_of_loan;
        if ($request->loan_cost <= 10000000) {
            $add = 30;
            for ($i = 1; $i <= 6; $i++) {
                Installment::create([
                    "Price" => $request->prise,
                    // اضافه کردن روزها به تاریخ وامل
                    "date_of_payment" => $date_of_loan->addDay($add),
                    "cost" => $request->cost / 6,
                    "loan_id" => $request->input("loan_id")
                ]);
                $add += 30;
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                Installment::create([
                    "Price" => $request->prise,
                    // اضافه کردن روزها به تاریخ وامل
                    "date_of_payment" => $date_of_loan->addDay(30),
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

    public function Bank_receipt_photo(Request $request, $loan_id)
    {
        $installment = Installment::findOrFail($loan_id);
        $img = $installment->addMedia($request->image)->toMediaCollection('Bank_receipt_photo' . $loan_id);
        return $img;
    }

    public function all_installment($user_id)
    {
        $installment=User::with('loans.installments')->find($user_id);
        return response()->json([
            'data' => $installment
        ]);

    }
    public function Installments_paid()
    {
        // Authenticate the user
        Auth::user();
        $installments = Installment::where('status', 'Installments_paid')  // یافتن وام هایی که وضعیت پرداخت آن ها پرداخت شده است
            ->get();

        // Return the response
        return response()->json([
            'data' => $installments
        ]);
    }


}
