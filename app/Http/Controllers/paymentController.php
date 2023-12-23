<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Evryn\LaravelToman\Facades\Toman;
class paymentController extends Controller

{
    public function pay($id)
    {
        $transaction = InstallmentController::find($id);
        $totalPrice = $transaction->Price;
        $request = Toman::amount($totalPrice)
            ->description('Subscribing to Plan A')
            ->callback(route('payment.callback'))
            ->request();

        if($request->successful()){
            $transaction = Transactoin::create([
                'user_id' => 1,
                'reservation_id' => $id,
                'gateway_result' => ['transactionId' => $request->transactionId()],
                'price' => $totalPrice,
                'status' => 'pending',
            ]);
            return response()->json(['paymentUrl'=> $request->paymentUrl()]);
        }
        else{
            return $request->messages();
        }
    }

    public function callback(CallbackRequest $request)
    {
        $transactoin = Transactoin::where('gateway_result->transactionId', $request->transactionId())->first();
        $reservation = Reservation::find($transactoin->reservation_id);

        $reservation->update(['status' => 'reserved']);

        $payment = $request
            ->amount($reservation->totalPrice)
            ->verify();

        if ($payment->successful()) {

            $referenceId = $payment->referenceId();

            $transactoin->forcefill([
                'gateway_result->reference_id' => $referenceId,
                'status' => 'success',
            ])->save();

            return response()->json([
                'reference_id' => $referenceId,
                'transaction' => $transactoin,
                'reservation' => $reservation
            ]);

        }

        if ($payment->alreadyVerified()) {
            dd('already_verified');
        }

        if ($payment->failed()) {
            $transactoin->forcefill([
                'gateway_result->messages' => $payment->messages(),
                'status' => 'failed',
            ])->save();
            $reservation->update(['status' => 'doing']);
            return response()->json(['transaction' => $transactoin]);
        }
}}
