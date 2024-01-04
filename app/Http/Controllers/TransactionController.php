<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\Transaction;
use Evryn\LaravelToman\CallbackRequest;
use Evryn\LaravelToman\Facades\Toman;
use Illuminate\Http\Request;

class TransactionController extends Controller

{
    // تابعی برای پرداخت قسط با شناسه داده شده
    public function pay($id)
    {
        // یافتن قسط مربوطه از جدول نصب
        $installment = Installment::find($id);
        // محاسبه قیمت کل قسط
        $totalPrice = $installment->cost;
        // ایجاد درخواست پرداخت با استفاده از تومان
        $request = Toman::amount($totalPrice)
            ->description('پرداخت قسط در صندوق قرض‌الحسنه') // تغییر متن توضیحات
            ->callback(route('payment.callback'))
            ->request();

        // اگر درخواست پرداخت موفق بود
        if ($request->successful()) {
            // ایجاد یک تراکنش جدید با اطلاعات مربوطه
//            dd($totalPrice);
            $transaction = Transaction::create([
                'user_id' => $installment->loan->user_id,
                'installment_id' => $id,
                'gateway_result' => ['transactionId' => $request->transactionId()],
                'Price' => $totalPrice,
                'loan_id' => $installment->loan_id,
                'status' => 'pending',
            ]);
            // برگرداندن آدرس پرداخت به عنوان پاسخ
            return response()->json(['paymentUrl' => $request->paymentUrl()]);
        } else {
            // برگرداندن پیام‌های خطا به عنوان پاسخ
            return $request->messages();
        }
    }

    // تابعی برای بررسی نتیجه پرداخت با استفاده از درخواست بازگشت
    public function callback(CallbackRequest $request)
    {
        // یافتن تراکنش مربوطه از جدول تراکنش‌ها با استفاده از شناسه تراکنش
        $transaction = Transaction::where('gateway_result->transactionId', $request->transactionId())->first();
        // یافتن قسط مربوطه از جدول نصب
        $installment = Installment::find($transaction->installment_id);

        // به‌روزرسانی وضعیت قسط به موفق
        $installment->update(['Payment_status' => 'Paid']);
        // تایید پرداخت با استفاده از تومان
        $payment = $request
            ->amount($installment->cost) // تصحیح متغیر cost
            ->verify();

        // اگر پرداخت موفق بود
        if ($payment->successful()) {

            // گرفتن شناسه ارجاع از پرداخت
            $referenceId = $payment->referenceId();

            // به‌روزرسانی تراکنش با شناسه ارجاع و وضعیت موفق
            $transaction->forceFill([
                'gateway_result->reference_id' => $referenceId,
                'status' => 'success',
            ])->save();

            // برگرداندن شناسه ارجاع، قسط و تراکنش به عنوان پاسخ
            return response()->json([
                'reference_id' => $referenceId,
                'installment' => $installment,
                'transaction' => $transaction
            ]);

        }

        // اگر پرداخت ناموفق بود
        if ($payment->failed()) {
            // به‌روزرسانی تراکنش با پیام‌های خطا و وضعیت ناموفق
            $transaction->forceFill([
                'gateway_result->messages' => $payment->messages(),
                'status' => 'failed',
            ])->save();
            // به‌روزرسانی وضعیت قسط به ناموفق
            $installment->update(['status' => 'failed']); // تغییر به وضعیت failed
            // برگرداندن تراکنش به عنوان پاسخ
            return response()->json(['transaction' => $transaction]);
        }
        if ($payment->alreadyVerified()){
            // به‌روزرسانی تراکنش با پیام‌های خطا و وضعیت ناموفق
            $transaction->forceFill([
                'gateway_result->messages' => $payment->messages(),
                'status' => 'success',
            ])->save();
            // به‌روزرسانی وضعیت قسط به ناموفق
            $installment->update(['status' => 'success']); // تغییر به وضعیت success
            // برگرداندن تراکنش به عنوان پاسخ
            return response()->json(['transaction' => $transaction]);
        }
    }

}
