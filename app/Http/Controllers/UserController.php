<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function add_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'fatherName' => 'required|string|max:255',
            'phone_number' => 'required|string|nullable|max:255|unique:users',
            'national_id' => 'required',
            'password' => 'required|string|min:6',
            'img' => 'required|image|mimes:jpg,png|max:10240',
        ]);
        $user = User::create($request->all());
        return response()->json([
            'add_user' => $user
        ]);
    }

    public function all_users()
    {
        $users = User::all();
        return response()->json([
            'data' => $users
        ]);
    }

    public function find_user(Request $request)
    {
        $user = User::find($request->id);
        return response()->json([
            'find_user' => $user,
            'user_image' => $user->getMedia()
        ]);

    }

    public function update_user(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->hasFile('img')) {
            $user->addMediaFromRequest('img')->toMediaCollection('add_avatar_for_user');
            $user->load('media');
        }
        $user->update($request->all());
        $user->save();
        return response()->json([
            'update_user'
        ]);
    }


    public function update_status(Request $request, $id)
    {
        // Validate the request input
        $request->validate([
            'status' => 'required'
        ]);
        // Find the order by id
        $user = User::findOrFail($id);

        // Update the status field
        $user->status = $request->status;
        $user->save();

        // Return a success message or redirect to another page
        return response()->json(['message' => 'user status updated successfully']);
    }

    public function show_user_info()
    {
        $user=Auth::user();

        // اطلاعات مورد نیاز کاربر
        $userDetails = [
            'name' => $user->name,
            'family' => $user->family,
            'created_at' => $user->created_at,
            'wallet_balance' => $user->balance, // اگر نام مدل کیف پول Wallet است، باید متناسب با آن تغییر کند
            'status' => $user->status,
        ];

        return response()->json(['user_info' => $userDetails]);
    }
    public function change_password(Request $request)
    {
        $user = Auth::user();

        // Validate request input
        $request->validate([
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Check if the entered current password matches the user's actual password
        if (!password_verify($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 401);
        }

        // Update user's password with the new one
        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }
    public function Email_update_for_user(Request $request)
    {
        $user = Auth::user(); // یافتن کاربر فعلی

        // Validate request input and check if the email is unique in the users table except the current user id
        $request->validate([ // اعتبارسنجی درخواست و بررسی اینکه ایمیل در جدول کاربران به جز شناسه کاربر فعلی یکتا باشد
            'email' => 'required|string|email|max:255|unique:users', // اضافه کردن unique:users
        ]);

        // Update user's password with the new one
        $user->email = $request->email; // به‌روزرسانی ایمیل کاربر
        $user->save(); // ذخیره کاربر

        return response()->json(['message' => 'Email updated successfully']); // برگرداندن پیام موفقیت‌آمیز
    }


}

