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


        return response()->json(['The loan application was registered  ' => $loan
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
        $loan = Loan::all();
        return response()->json([
            $loan

        ]);
    }

    public function loan_check(Request $request, $id)
    {
        $load = Loan::find($id);
        $load->update(['status' => $request->status]);
    }


    public function update_status(Request $request, $id)
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
        $user = Auth::user();
        $loan = $user->loans()->with('installments')->findOrFail($id);

        $lastPaidInstallment = $loan->installments->where('Payment_status', 'Paid')->last();
        $deferredInstallments = $loan->installments->where('status', 'Deferred_installments');

        if ($lastPaidInstallment) {
            $lastPaidInstallmentId = $lastPaidInstallment->id;
        } else {
            $lastPaidInstallmentId = null;
        }

        if ($deferredInstallments->isEmpty()) { // اگر اقساط معوقه وجود نداشت
            $deferredInstallmentId = null;
        } else {   // اگر اقساط معوقه وجود داشت
            $deferredInstallmentId = $deferredInstallments->first()->cost;
        }

        return response()->json([
            'loan_id' => $loan->id,
            'loan_amount' => $loan->amount,
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

    public function index()
    {
        $user = Auth::user();
        $loans = $user->loans()->with('installments')->get([]);
        return response()->json([
            'loans' => $loans
        ]);
    }


}
