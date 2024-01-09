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
//        $rand = rand(1000, 9999);
        $rand =1234;
        $timeValid = Carbon::now()->addMinutes(555555);
        $send_sms = TempCode::updateOrCreate([
            'phone_number' => $request->phone_number,
            'verification_cod' => $rand,
            'exp_time' => $timeValid,
        ]);
        $this->send_sms($request, $rand);
        // اینجا باید متغیر 'phone_number' را به عنوان یک آرایه برگردانید
        return response()->json(['phone_number' => $request->phone_number]);    // Change text
    }

    protected function send_sms(Request $request, $rand)
    {
        $client = new Client(env('IP_PANEL_KEY'));
        $patternValues = [
            "rand" => $rand
        ];

        // اینجا باید مقدار 'qk9hlr3czoqct8t' را با کد الگوی پیامک خود جایگزین کنید
        $messageId = $client->sendPattern(
            "qk9hlr3czoqct8t", // باید کد الگوی پیامک خود را وارد کنید
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
            'national_id' => 'required',
            'password' => '',
            'img' => 'required|image|mimes:jpg,png|max:10240',
        ]);
        // اینجا باید پرانتز بسته را از انتهای خط حذف کنید
        $user = User::create($request->merge([
            "password" => null
        ])->except(['phone_number', 'email'])); // حذف پرانتز بسته
        if ($request->hasFile('img'))
        {
            $user->addMediaFromRequest('img')->toMediaCollection('national_card_email');
            $user->load('media');
        }
        // اینجا باید کاربر را با حذف فیلد‌های شماره تلفن و ایمیل برگردانید
        return response()->json(['user' => $user->makeHidden(['phone_number', 'email'])->toArray() // اضافه کردن makeHidden
        ]);

    }

    public function find_user(Request $request)
    {
        $user = User::find($request->id);
        return response()->json([
            'find_user' => $user,
            'national_id_image' => $user->getMedia()
        ]);
    }


    public function Add_password(Request $request, $id)
    {
        $user = User::with('media')->find($id);
        $user->update($request->all());
        $user->save();
        return response()->json([
            $user
        ]);
    }

    public function register(Request $request, $id)
    {

        $user = User::with('media')->find($id);
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
                response()->json(['message' => 'کد تایید شد']);
            } else {
                response()->json(['message' => 'کد اشتباه است']);
            }
        }
    }

}
