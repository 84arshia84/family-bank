<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{

    public function add_loan(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'title_of_loan' => 'required|string|max:100',
            'amount' => 'required|string|max:100',
            'description' => 'required',
        ]);

        $data['user_id'] = $user->id;
        $loan = Loan::create($data); // شرط برای یافتن یا ایجاد رکورد


        return response()->json([$loan
        ]);
    }

    public function date_of_loan(Request $request, $id)
    {
        // Validate the request input
        $request->validate([
            'date_of_loan' => 'required|date_format:Y-m-d',
            'count' => 'required|integer'
        ]);
        $loan = Loan::findOrFail($id);
        $loan->date_of_loan = $request->date_of_loan;
        $loan->save();
        // loan_id
        // loan_cost
        // پیدا کردن وام با شناسه درخواست
        // گرفتن تاریخ وام از مدل وام
        $date_of_loan = $loan->date_of_loan;
        $add = 30;
        for ($i = 1; $i <= $request->count; $i++) {
            Installment::create([
                // اضافه کردن روزها به تاریخ وامل
                "date_of_payment" => $date_of_loan->addDay($add),
                "cost" => $loan->amount / $request->count,
                "loan_id" => $loan->id
            ]);
            $add += 30;
        }
        return response()->json(['message' => $loan]);
//

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


    public function List_of_loans()
    {
        $loans = Loan::with('user')->get();

        $loanData = [];
        foreach ($loans as $loan) {
            $loanData[] = [
                'id' => $loan->id,
                'title_of_loan' => $loan->title_of_loan,
                'amount' => $loan->amount,
                'description' => $loan->description,
                'name' => $loan->user->name,
                'family' => $loan->user->family,
                'father_name' => $loan->user->father_name,
                'national_id' => $loan->user->national_id,
                'status' => $loan->user->status


            ];
        }

        return response()->json($loanData);
    }



    public function update_status_loan(Request $request, $id)
    {
        // Validate the request input
        $request->validate([
            'status' => 'required|in:accept,reject'
        ]);
        // Find the order by id
        $loan = Loan::findOrFail($id);

        // Update the status field
        $loan->status = $request->status;
        $loan->save();

        // Return a success message or redirect to another page
        return response()->json(['message' => 'Loan status updated successfully']);
    }

    public function Loan_details($id)
    {
        $user = Auth::user(); // پیدا کردن یک کاربر با شناسه 1
        $loan = $user->loans()->with('installments')->findOrFail($id); // پیدا کردن یک کاربر با شناسه 1

        $lastPaidInstallment = $loan->installments->where('Payment_status', 'Paid')->last(); // پیدا کردن آخرین قسط پرداخت شده
        $deferredInstallments = $loan->installments->where('status', 'Deferred_installments');  // پیدا کردن اقساط معوقه

        if ($lastPaidInstallment) { // اگر آخرین قسط پرداخت شده وجود داشت
            $lastPaidInstallmentId = $lastPaidInstallment->id; // شناسه آخرین قسط پرداخت شده
        } else { // اگر آخرین قسط پرداخت شده وجود نداشت
            $lastPaidInstallmentId = null;  // شناسه آخرین قسط پرداخت شده
        }

        if ($deferredInstallments->isEmpty()) { // اگر اقساط معوقه وجود نداشت
            $deferredInstallmentId = null; // شناسه اقساط معوقه
        } else {   // اگر اقساط معوقه وجود داشت
            $deferredInstallmentId = $deferredInstallments->first()->cost; // شناسه اقساط معوقه
        }

        return response()->json([
            'loan_id' => $loan->id,
            'loan_amount' => $loan->amount,
             'date_of-loan' => $loan->date_of_loan, // تاریخ وام
            'Installment_amount_every_month'=> $loan->amount / $loan->installments->count(), // مبلغ هر قسط
            'last_paid_installment_id' => $lastPaidInstallmentId,
            'last_paid_installment_cost' => $lastPaidInstallment ? $lastPaidInstallment->cost : null,
            'deferred_installment_id' => $deferredInstallmentId,
        ]);
    }

    public function update_loan(Request $request, $id)
    {
        $loan = Loan::find($id);
        $loan->update($request->all());
        return response()->json([
            'message' => 'Loan updated successfully',
            'loan' => $loan
        ]);
    }
    public function all_loans_for_user()
    {
        $user = Auth::user();
        $loans = $user->loans()->with('installments')->get();

        $loanData = [];
        foreach ($loans as $loan) {
            $loanData[] = [
                'id' => $loan->id,
                'title_of_loan' => $loan->title_of_loan,
                'amount' => $loan->amount,
                'description' => $loan->description,
                'name' => $loan->user->name,
                'family' => $loan->user->family,
                'father_name' => $loan->user->father_name,
                'national_id' => $loan->user->national_id,
                'status' => $loan->user->status,
                'installments' => $loan->installments->map(function ($installment) {
                    return [
                        'id' => $installment->id,
                        'date_of_payment' => $installment->date_of_payment,
                        'cost' => $installment->cost,
                        'Payment_status' => $installment->Payment_status,
                        'status' => $installment->status,
                    ];
                })
            ];
        }

        return response()->json($loanData);
    }

}
