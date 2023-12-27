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
            'amount' => 'required|string|max:100',
            'description' => 'required',
            'user_id' => 'required|exists:users,id',

        ]);
        $loan = Loan::create($request->all());
        return response()->json(['The loan application was registered  ' => $loan
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

    public function List_of_loans()
    {
        $loan = Loan::all();
        return response()->json([
            $loan

        ]);
    }

    public function loan_check(Request $request,$id)
    {
        $load = Loan::find($id);
        $load->update(['status'=>$request->status]);
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
    public function date_of_loan(Request $request, $id)
    {
        // Validate the request input
        $request->validate([
                'date_of_loan'=>'required|date_format:Y-m-d'
        ]);
        $loan=Loan::findOrFail($id);
        $loan->date_of_loan=$request->date_of_loan;
        $loan->save();
        return response()->json(['message' =>$loan]);
//

}}
//// این متد یک وام جدید را با استفاده از اطلاعات درخواست کاربر ایجاد می‌کند
//// و سپس برای آن وام چند قسط نیز ایجاد می‌کند
//public function create_loan(Request $request)
//{
//    // ابتدا اطلاعات درخواست را اعتبارسنجی می‌کنیم
//    $request->validate([
//        'title_of_loan' => 'required|string|max:100',
//        'price' => 'required|numeric|min:1000',
//        'description' => 'required|string',
//        'user_id' => 'required|exists:users,id'
//    ]);
//
//    // سپس یک وام جدید را با متد create ایجاد می‌کنیم
//    $loan = Loan::create($request->all());
//
//    // برای ایجاد اقساط، ابتدا تعداد و مبلغ اقساط را محاسبه می‌کنیم
//    // اگر مبلغ وام بیشتر از 10 میلیون باشد، 12 قسط ایجاد می‌کنیم
//    // در غیر این صورت، 6 قسط ایجاد می‌کنیم
//    if ($request->price > 10000000) {
//        $number_of_installments = 12;
//    } else {
//        $number_of_installments = 6;
//    }
//
//    // مبلغ هر قسط را بر اساس مبلغ وام و تعداد اقساط محاسبه می‌کنیم
//    $installment_price = $request->price / $number_of_installments;
//
//    // حالا برای هر قسط یک رکورد در جدول installments ایجاد می‌کنیم
//    // تاریخ پرداخت هر قسط را 30 روز بعد از قسط قبلی قرار می‌دهیم
//    // اولین قسط را 30 روز بعد از ایجاد وام قرار می‌دهیم
//    for ($i = 1; $i <= $number_of_installments; $i++) {
//        Installment::create([
//            'loan_id' => $loan->id, // ایدی وام را از مدل loan می‌گیریم
//            'user_id' => $request->user_id, // ایدی کاربر را از درخواست می‌گیریم
//            'price' => $installment_price, // مبلغ قسط را از متغیر محاسبه شده می‌گیریم
//            'date_of_payment' => now()->addDays(30 * $i), // تاریخ پرداخت را با استفاده از کلاس Carbon محاسبه می‌کنیم
//            'cost' => 0, // هنوز هیچ هزینه‌ای برای قسط در نظر گرفته نشده است
//        ]);
//    }
//
//    // در نهایت یک پاسخ JSON با مشخصات وام و اقساط ایجاد شده را برمی‌گردانیم
//    return response()->json([
//        'loan' => $loan,
//        'installments' => $loan->installments // از رابطه one-to-many بین مدل‌های loan و installment استفاده می‌کنیم
//    ]);
//}
//
//// این متد یک وام را با استفاده از شناسه آن پیدا می‌کند
//// و اطلاعات آن را به همراه اقساط مربوطه نمایش می‌دهد
//public function show_loan($id)
//{
//    // ابتدا یک وام را با استفاده از متد find پیدا می‌کنیم
//    // اگر وامی با این شناسه وجود نداشته باشد، خطای 404 را برمی‌گردانیم
//    $loan = Loan::findOrFail($id);
//
//    // سپس یک پاسخ JSON با مشخصات وام و اقساط مربوطه را برمی‌گردانیم
//    return response()->json([
//        'loan' => $loan,
//        'installments' => $loan->installments // از رابطه one-to-many بین مدل‌های loan و installment استفاده می‌کنیم
//    ]);
//}
//
//// این متد یک قسط را با استفاده از شناسه آن پیدا می‌کند
//// و اطلاعات آن را به همراه وام و کاربر مربوطه نمایش می‌دهد
//public function show_installment($id)
//{
//    // ابتدا یک قسط را با استفاده از متد find پیدا می‌کنیم
//    // اگر قسطی با این شناسه وجود نداشته باشد، خطای 404 را برمی‌گردانیم
//    $installment = Installment::findOrFail($id);
//
//    // سپس یک پاسخ JSON با مشخصات قسط و وام و کاربر مربوطه را برمی‌گردانیم
//    return response()->json([
//        'installment' => $installment,
//        'loan' => $installment->loan, // از رابطه belongs-to بین مدل‌های installment و loan استفاده می‌کنیم
//        'user' => $installment->user // از رابطه belongs-to بین مدل‌های installment و user استفاده می‌کنیم
//    ]);
//}
