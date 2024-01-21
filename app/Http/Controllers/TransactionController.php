<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
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
            ->callback(route('payment.callback', ['type' => 'installment', 'installment' => $id]))
            ->request();

        // اگر درخواست پرداخت موفق بود
        if ($request->successful()) {
            // ایجاد یک تراکنش جدید با اطلاعات مربوطه
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

    public function callback(CallbackRequest $request, $type = 'installment', ?Installment $installment = null) // تابعی برای بررسی نتیجه پرداخت با استفاده از درخواست بازگشت
    {
        // یافتن تراکنش مربوطه از جدول تراکنش‌ها با استفاده از شناسه تراکنش
        $transaction = Transaction::where('gateway_result->transactionId', $request->transactionId())->first();
        $payment = $request
            ->amount($transaction->Price) // تصحیح متغیر cost
            ->verify();

        // اگر پرداخت موفق بود
        if ($payment->successful()) {

            // گرفتن شناسه ارجاع از پرداخت
            $referenceId = $payment->referenceId();

            // به‌روزرسانی تراکنش با شناسه ارجاع و وضعیت موفق
            $transaction->forceFill([
                'gateway_result->reference_id' => $referenceId,
                'status' => 'success',
                'description' => 'پرداخت موفق',
            ])->save();
            $data = [
                'reference_id' => $referenceId,
                'transaction' => $transaction
            ];
            if ($type === 'installment') {        // یافتن قسط مربوطه از جدول نصب
                // به‌روزرسانی وضعیت قسط به موفق
                $installment->Payment_status = 'Paid';
                $installment->status = 'Installments_paid'; // تغییر به وضعیت Installments_paid
                $installment->save();
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
                $installment->update(['status' => 'current_installments']); // تغییر به وضعیت failed
            }            // برگرداندن تراکنش به عنوان پاسخ
            return response()->json(['transaction' => $transaction]);
        } else {
            return response()->json(['transaction' => $transaction]);
        }
    }

    // تابعی برای بررسی نتیجه پرداخت با استفاده از درخواست بازگشت

    public function paySubscription()
    {
        $totalPrice = 300000;

        $request = Toman::amount($totalPrice)
            ->description('پرداخت قسط در صندوق قرض‌الحسنه') // تغییر متن توضیحات
            ->callback(route('payment.callback', ['type' => 'subscription']))
            ->request();
        if ($request->successful()) {
            // ایجاد یک تراکنش جدید با اطلاعات مربوطه
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

    public function showUserTransactions()
    {
        // یافتن کاربر
        $user = Auth::user();

        // اگر کاربر پیدا شد
        if ($user) {
            // گرفتن تراکنش‌های کاربر
            $transactions = $user->transactions()->latest()->get();

            // ارسال تراکنش‌ها به نمایش
            return response()->json($transactions);
        }
        // اگر کاربر پیدا نشد
        return response()->json(['message' => 'کاربر مورد نظر یافت نشد.'], 404);
    }

    public function showUserTransactionId()
    {
        // یافتن کاربر
        $userId = auth()->id();

        // اگر کاربر پیدا شد
        if ($userId) {
            // گرفتن تراکنش‌های کاربر
            $transactions = Transaction::where('user_id', $userId)->latest()->get();

            // ارسال تراکنش‌ها به نمایش
            return response()->json($transactions);
        }

        // اگر کاربر پیدا نشد
        return response()->json(['message' => 'کاربر مورد نظر یافت نشد.'], 404);
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
        $img = $transaction->addMedia($request->image)->toMediaCollection('Bank_receipt_photo'); // اضافه کردن تصویر به تراکنش
        return $transaction->load('media');  // ارسال تراکنش به عنوان پاسخ

    }

    public function update_status_Bank_receipt_photo(Request $request, $id) //روت برای ادمین

    {
        $transaction = Transaction::find($id);
        $transaction->update(['status' => $request->status]); // تغییر به وضعیت failed
        return response()->json(['transaction' => $transaction]);
    }

    public function show_transactions_Bank_receipt_photo(Transaction $transaction) //روت برای ادمین
    {
        return response()->json(['transactions' => $transaction->load('media')]);
    }

    public function updateBankReceipt(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'date' => 'required|date',
            'tracking_code' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // یافتن تراکنش با شناسه مورد نظر
        $transaction = Transaction::find($id);

        // بررسی آیا تراکنش یافت شده است یا خیر
        if ($transaction) {
            // به‌روزرسانی اطلاعات تراکنش به جز عکس رسید بانکی
            $transaction->update([
                'Price' => $request->price,
                'date' => $request->date,
                'tracking_code' => $request->tracking_code,
                'description' => $request->description,
                // سایر فیلدهای مورد نیاز برای تراکنش را نیز به‌روز کنید
            ]);
            $user_wallet = $transaction->user->wallet_balance;
            $user_wallet += $transaction->Price;

            // برگرداندن تراکنش به عنوان پاسخ
            return response()->json($transaction);
        }

        // اگر تراکنش یافت نشد، پیام خطا را برگردانید
        return response()->json(['error' => 'Transaction not found'], 404);
    }

    public function showUserPaidInstallments($userId) // نمایش قسط‌های پرداخت شده
    {
        $user = User::find($userId); // یافتن کاربر

        if ($user) { // اگر کاربر پیدا شد
            $paidInstallments = $user->installments()->where('status', 'Installments_paid')->get(); // گرفتن قسط‌های پرداخت شده
            return response()->json(['paid_installments' => $paidInstallments]); // ارسال قسط‌های پرداخت شده به نمایش
        }

        return "کاربر مورد نظر یافت نشد.";
    }


    public function show($id)
    {
        // احراز هویت کاربر
        $user = Auth::user();

        // یافتن تراکنش مربوطه از جدول تراکنش‌ها
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        // بررسی می‌کنیم که آیا تراکنش یافت شده است یا خیر
        if ($transaction) {
            // اطلاعات تراکنش را به صورت JSON برگردانیم
            return response()->json([
                'transaction_id' => $transaction->id,
                'transaction_date' => $transaction->created_at,
                'tracking_code' => $transaction->gateway_result->reference_id,
                'amount' => $transaction->Price,
                'description' => $transaction->description,
                'user_id' => $transaction->user_id,
            ]);
        } else {
            // در صورت عدم یافت تراکنش یا عدم احراز هویت، پیام خطا را برگردانیم
            return response()->json(['error' => 'Transaction not found'], 404);
        }
    }

    public function index()
    {
        // احراز هویت کاربر
        $user = Auth::user();

        // یافتن تمامی تراکنش‌های کاربر از جدول تراکنش‌ها
        $transactions = Transaction::where('user_id', $user->id)->get();

        // برگرداندن تمامی تراکنش‌ها به صورت JSON
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        // احراز هویت کاربر
        $user = Auth::user();

        // اعتبارسنجی اطلاعات ورودی
        $request->validate([    // اضافه کردن مقادیر مورد نیاز برای تراکنش
            'amount' => 'required|numeric', // اضافه کردن مقدار مورد نیاز برای تراکنش
            'description' => 'nullable|string', // اضافه کردن مقدار مورد نیاز برای تراکنش
        ]);

        // ایجاد یک تراکنش جدید با اطلاعات مربوطه
        $transaction = Transaction::create([    // اضافه کردن مقادیر مورد نیاز برای تراکنش
            'user_id' => $user->id, // اضافه کردن مقدار مورد نیاز برای تراکنش
            'amount' => $request->amount,   // اضافه کردن مقدار مورد نیاز برای تراکنش
            'description' => $request->description,
            // سایر فیلدهای مورد نیاز برای تراکنش را نیز اضافه کنید
        ]);

        // ارسال تراکنش به عنوان پاسخ
        return response()->json($transaction);
    }

    public function getTransactionDetails(Request $request, $transactionId)
    {
        // Retrieve the user ID from the request
        $userId = Auth::id();

        // Retrieve the transaction details
        $transaction = Transaction::where('id', $transactionId)
            ->first();

        // Check if the transaction exists
        if ($transaction) {
            // Retrieve the user details
            $userDetails = $transaction->user->name . ' ' . $transaction->user->surname . ' ' . $transaction->user->family;
            // Prepare the response data
            $responseData = [
                'user_details' => $userDetails,
                'transaction_amount' => $transaction->Price,
                'transaction_date' => $transaction->created_at,
                'referenceId' => $transaction->gateway_result->reference_id,
            ];

            // Check if the bank receipt image exists
            if ($transaction->bank_receipt_image) {
                $responseData['bank_receipt_image'] = $transaction->bank_receipt_image;
            }

            // Return the response
            return response()->json($responseData);
        } else {
            // Return an error response if the transaction doesn't exist
            return response()->json(['error' => 'Transaction not found'], 404);
        }
    }

    public function show_all_transaction()
    {
        $transactions = Transaction::all();

        $transactionData = [];

        foreach ($transactions as $transaction) {
            $user = $transaction->user;
            $userDetails = $user->name . ' ' . $user->surname . ' ' . $user->family;

            $transactionData[] = [
                'user_details' => $userDetails,
                'transaction_id' => $transaction->id,
                'transaction_date' => $transaction->created_at,
                'tracking_code' => $transaction->gateway_result,
                'amount' => $transaction->Price,
                'description' => $transaction->description,
                'user_id' => $transaction->user_id,
            ];
        }

        return response()->json($transactionData);
    }
}
