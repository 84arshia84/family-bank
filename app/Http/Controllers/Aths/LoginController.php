<?php

namespace App\Http\Controllers\Aths;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // بررسی وجود کاربر با شماره تلفن
        $user = User::where('phone_number', $request->phone_number)->first();

        // اگر کاربر وجود نداشت یا رمزعبور صحیح نبود
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['رمزعبور صحیح نیست']
            ]);
        }

        // ایجاد توکن برای کاربر
        $token = $user->createToken('api_token')->plainTextToken;

        // برگرداندن پاسخ به کلاینت
        return response()->json([
            'user' => $user->only(['id', 'name', 'phone_number']), // برگرداندن اطلاعات مهم کاربر
            'token' => $token,
        ]);
    }
}
