<?php

namespace App\Http\Controllers\Aths;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'fatherName' => 'required|string|max:255',
            'phone_number' => 'required|string|max:11|unique:users',
            'national_id' => 'required',
            'password' => 'required|string|min:6',
        ]);
        $rand= rand(1000,9999);

        $timValid=Carbon::now()->addMinute(5);
        $user = User::create($request->phone_number);
        return response()->json();
    }
}
