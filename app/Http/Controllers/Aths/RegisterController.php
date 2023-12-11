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
        $timeValid = Carbon::now()->addMinutes(5);
        $send_sms = TempCode::updateOrCreate([
            'phone_number' => $request->phone_number,
            'verification_cod' => $rand,
            'exp_time' => $timeValid,
        ]);
        $this->send_sms($request, $rand);
        return response()->json(['phone_number']);    // Change text
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
            $patternValues  // pattern values
        );


    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:11|unique:users',
            'national_id' => 'required',
            'password' => '',
        ]);
        $user = User::create($request->all());
        return response()->json(['user' => $user
        ]);

    }

    public function Add_password(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        $user->save();
        return response()->json([
            $user
        ]);
    }

    public function register(Request $request, $id)
    {
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'family' => 'required|string|max:255',
//            'father_name' => 'required|string|max:255',
//            'phone_number' => 'required|string|max:11|unique:users',
//            'national_id' => 'required',
//            'password' => 'required|string|min:6',
//        ]);
//        $user = User::update($request->all());
//        $user = User::update($request->all());
        $user = User::find($id);
        $user->update($request->all());
        $user->save();
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function check(Request $request)
    {
        $Code_confirmation_time = Carbon::now();


        $status = TempCode::where('phone_number', $request->phone_number)->where('verification_cod', $request->verification_cod)->first();
        {
            if ($status != null) {
                return response()->json( 'کد تایید شد');
            } else {
                return response()->json('کد اشتباه است');
            }
        }

    }
}

