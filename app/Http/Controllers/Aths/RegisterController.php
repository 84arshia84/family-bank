<?php

namespace App\Http\Controllers\Aths;

use App\Http\Controllers\Controller;
use App\Models\TempCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use IPPanel\Client;

class RegisterController extends Controller
{
    public function sendVerificationCode(Request $request)
    {

        $rand = rand(1000, 9999);
        $timValid = Carbon::now()->addMinutes(5);
        $send_sms = TempCode::updateOrCreate([
            'phone_number' => $request->phone_number,
            'verification_cod' => $rand,
            'exp_time' => $timValid,
        ]);
//        $this->send_sms($request, $rand);

        return response()->json(['phone_number']);
    }

    protected function send_sms(Request $request, $rand)
    {
        $client = new Client(env('IP_PANEL_KEY'));
        $patternValues = [
            "rand" => $rand
        ];

        $messageId = $client->sendPattern(
            "qk9hlr3czoqct8t",
            "+983000505",      // originator
            $request->phone_number,  // recipient
            $patternValues,  // pattern values
        );


    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'fatherName' => 'required|string|max:255',
            'phone_number' => 'required|string|max:11|unique:users',
            'national_id' => 'required',
            'password' => 'required|string|min:6',
        ]);
//dd($request);
        $user = User::update($request->all());
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function match(Request $request)
    {
        $Code_confirmation_time = Carbon::now();



        $status = TempCode::where('phone_number', $request->phone_number)->where('verification_cod', $request->verification_cod)->first();
        {
            if ($status != null) {
               return response()->json($Code_confirmation_time ,'phone_number');
            } else {
                return response()->json('کد اشتباه است');
            }
        }

    }
}

