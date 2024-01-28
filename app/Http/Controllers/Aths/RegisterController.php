<?php

namespace App\Http\Controllers\Aths;

use App\Http\Controllers\Controller;
use App\Models\TempCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use IPPanel\Client;
use function Symfony\Component\String\u;

class RegisterController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $rand = rand(1000, 9999);
        $request->validate([
            'phone_number' => 'required|regex:/(09)[0-9]{9}/|unique:users',
        ]);
        $timeValid = Carbon::now()->addMinutes(555555);
        $send_sms = TempCode::updateOrCreate([
            'phone_number' => $request->phone_number,
            'verification_cod' => $rand,
            'exp_time' => $timeValid,
        ]);
        $this->send_sms($request->phone_number, $rand);
        // اینجا باید متغیر 'phone_number' را به عنوان یک آرایه برگردانید
        return response()->json(['phone_number' => $request->phone_number]);    // Change text
    }

    protected function send_sms($phone_number, $rand)
    {
        $client = new Client(env('IP_PANEL_KEY'));
        $patternValues = [
            "rand" => $rand
        ];
        $messageId = $client->sendPattern(
            "qk9hlr3czoqct8t", // باید کد الگوی پیامک خود را وارد کنید
            "+983000505",      // originator
            $phone_number,  // recipient
            $patternValues  // pattern values
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255|unique:users',
            'img' => 'required|image|mimes:jpg,png|max:10240',
            'phone_number' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);
        // اینجا باید پرانتز بسته را از انتهای خط حذف کنید
        $user = User::create($data);
        if ($request->hasFile('img')) {
            $user->addMediaFromRequest('img')->toMediaCollection('national_card_imag');
        }
        $user->assignRole('user');
        // اینجا باید کاربر را با حذف فیلد‌های شماره تلفن و ایمیل برگردانید
        return response()->json(['user' => $user, 'token' => $user->createToken('auth_token')->plainTextToken]);

    }

    public function find_user(Request $request)
    {
        $user = User::find($request->id);
        return response()->json([
            'find_user' => $user,
            'national_id_image' => $user->getMedia()
        ]);
    }


    public function check(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/(09)[0-9]{9}/',
            'verification_code' => 'required',
        ]);
        $status = TempCode::where('phone_number', $request->phone_number)->where('verification_cod', $request->verification_code)->exists();
        {
            if ($status) {
                return response()->json(['message' => 'کد تایید شد']);
            } else {
                return response()->json(['message' => 'کد اشتباه است']);
            }
        }
    }

}
