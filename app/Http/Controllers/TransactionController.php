<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Transaction;
use App\Models\User;
use Auth;
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

    public function callback(CallbackRequest $request, $type = 'installment')
    {
        // یافتن تراکنش مربوطه از جدول تراکنش‌ها با استفاده از شناسه تراکنش
        $transaction = Transaction::where('gateway_result->transactionId', $request->transactionId())->first();

        $payment = $request
            ->amount($transaction->Price) // تصحیح متغیر cost
            ->verify();

        // اگر پرداخت موفق بود
        if ($payment->successful()) {

            // تایید پرداخت با استفاده از تومان

            // گرفتن شناسه ارجاع از پرداخت
            $referenceId = $payment->referenceId();

            // به‌روزرسانی تراکنش با شناسه ارجاع و وضعیت موفق
            $transaction->forceFill([
                'gateway_result->reference_id' => $referenceId,
                'status' => 'success',
            ])->save();
            $data = [
                'reference_id' => $referenceId,
                'transaction' => $transaction
            ];
            if ($type === 'installment') {        // یافتن قسط مربوطه از جدول نصب
                $installment = Installment::find($transaction->installment_id);

                // به‌روزرسانی وضعیت قسط به موفق
                $installment->update(['Payment_status' => 'Paid']);
                $data['installment'] = $installment;
            }
            // برگرداندن شناسه ارجاع، قسط و تراکنش به عنوان پاسخ
            return response()->json($data);

        } // اگر پرداخت ناموفق بود
        elseif ($payment->failed()) {
            // به‌روزرسانی تراکنش با پیام‌های خطا و وضعیت ناموفق
            $transaction->forceFill([
                'gateway_result->messages' => $payment->messages(),
                'status' => 'failed',
            ])->save();
            // به‌روزرسانی وضعیت قسط به ناموفق
            if ($type === 'installment') {
                $installment = Installment::find($transaction->installment_id);
                $installment->update(['status' => 'failed']); // تغییر به وضعیت failed
            }            // برگرداندن تراکنش به عنوان پاسخ
            return response()->json(['transaction' => $transaction]);
        } else {
            // به‌روزرسانی تراکنش با پیام‌های خطا و وضعیت ناموفق
            $transaction->forceFill([
                'gateway_result->messages' => $payment->messages(),
                'status' => 'success',
            ])->save();
            // به‌روزرسانی وضعیت قسط به ناموفق
            if ($type === 'installment') {
                $installment = Installment::find($transaction->installment_id);
                $installment->update(['status' => 'failed']); // تغییر به وضعیت failed
            }            // برگرداندن تراکنش به عنوان پاسخ
            return response()->json(['transaction' => $transaction]);
        }
    }

    // تابعی برای بررسی نتیجه پرداخت با استفاده از درخواست بازگشت

    public function paySubscription()
    {
        $totalPrice = 300000;

        $request = Toman::amount($totalPrice)
            ->description('پرداخت قسط در صندوق قرض‌الحسنه') // تغییر متن توضیحات
            ->callback(route('payment.callback.subscription', ['type' => 'subscription']))
            ->request();
        if ($request->successful()) {
            // ایجاد یک تراکنش جدید با اطلاعات مربوطه
//            dd($totalPrice);
            Transaction::create([
                'user_id' => Auth::id(),
                'gateway_result' => ['transactionId' => $request->transactionId()],
                'Price' => $totalPrice,
                'status' => 'pending',
                'type' => 'subscription'
            ]);
            // برگرداندن آدرس پرداخت به عنوان پاسخ
            return response()->json(['paymentUrl' => $request->paymentUrl()]);
        } else {
            // برگرداندن پیام‌های خطا به عنوان پاسخ
            return $request->messages();
        }

    }

    public function showUserTransactions($userId)
    {
        // یافتن کاربر
        $user = User::find($userId);

        // اگر کاربر پیدا شد
        if ($user) {
            // گرفتن تراکنش‌های کاربر
            $transactions = $user->transactions()->latest()->get();

            // ارسال تراکنش‌ها به نمایش
            return response()->json([$transactions]);
        }
        // اگر کاربر پیدا نشد
        return "کاربر مورد نظر یافت نشد.";
    }

    public function Bank_receipt_photo(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png|max:10240',
            'price' => 'required|numeric',
            'date' => 'required|date',
            'tracking_code' => 'required|string',
            'description' => 'nullable|string',

        ]);

        $user = Auth::user();


        $transaction = Transaction::create([
            'user_id' => $user->id,
            'Price' => $request->price,
            'date' => $request->date,
            'tracking_code' => $request->tracking_code,
            'description' => $request->description,
            'status' => 'Pending'
            // سایر فیلدهای مورد نیاز برای تراکنش را نیز اضافه کنید
        ]);
        $img = $transaction->addMedia($request->image)->toMediaCollection('Bank_receipt_photo');
        return $transaction->load('media');

    }

}
