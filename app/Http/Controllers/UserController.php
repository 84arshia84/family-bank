<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

{
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
            'balance' => $user->balance, // اگر نام مدل کیف پول Wallet است، باید متناسب با آن تغییر کند
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


        public function profile_for_user(Request $request)
        {
            $user = Auth::user();

            // Validate request input and check if the profile image is valid
            $request->validate([
                'profile' => 'required|image|mimes:jpg,png|max:10240',
            ]);

            // Delete existing profile image
            $user->clearMediaCollection('profile');

            // Store the new profile image
            $user->addMediaFromRequest('profile')->toMediaCollection('profile');

            return response()->json([$user->getMedia('profile')]);
        }
        public function show_user_details()
        {
            $users = User::all();

            $userDetails = [];

            foreach ($users as $user) {
                $userDetails[] = [
                    'name' => $user->name,
                    'family' => $user->family,
                    'father_name' => $user->father_name,
                    'phone_number' => $user->phone_number,
                    'national_id' => $user->national_id,
                    'created_at' => $user->created_at,
                    'balance' => $user->balance,
                    'status' => $user->status,
                ];
            }

            return response()->json(['users_info' => $userDetails]);

        }
        public function user_image(Request $request, $id)
        {
            $user = User::find($id);
            $img = $user->addMedia($request->image)->toMediaCollection('add_avatar_for_user');
            return $img;
        }
        public function delete_user($id)
        {
            $user = User::find($id);
            $user->delete();
            return response()->json([
                'delete_user'
            ]);
        }
        public function Returning_the_deleted_user($id)
        {
            $user = User::withTrashed()->find($id);
            $user->restore();
            return response()->json([
                'Returning_the_deleted_user'
            ]);
        }
    public function show_user_and_bank_info ()
    {
        $user = Auth::user();
        $userDetails = [
            'name' => $user->name,
            'family' => $user->family,
            'father_name' => $user->father_name,
            'phone_number' => $user->phone_number,
            'national_id' => $user->national_id,
            'created_at' => $user->created_at,
            'balance' => $user->balance,
            'status' => $user->status,
            'email'=>$user->email
        ];
        $userBankAccount = $user->bankAccount;
        $userProfile = $user->getFirstMediaUrl('profile');
        return response()->json([
            'user_info' => $userDetails,
            'user_bank_account' => $userBankAccount,
            'user_profile' => $userProfile
        ]);
    }
    public function showAdmins()
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        return response()->json($admins);
    }
    public function monthlyUserDeposits()
    {
        $users = User::all();
        $monthlyDeposits = [];
        $totalMonthlyDeposits = [];

        foreach ($users as $user) {
            $transactions = Transaction::where('user_id', $user->id)
                ->where('status', 'success')
                ->get()
                ->groupBy(function($item) {
                    return $item->created_at->format('Y-m');
                });

            $userDeposits = [];
            foreach ($transactions as $month => $transactionGroup) {
                $userDeposits[$month] = $transactionGroup->sum('Price');
                if (!isset($totalMonthlyDeposits[$month])) {
                    $totalMonthlyDeposits[$month] = 0;
                }
                $totalMonthlyDeposits[$month] += $userDeposits[$month];
            }

            $monthlyDeposits[$user->id] = $userDeposits;
        }

        return response()->json([

            'total_deposits' => $totalMonthlyDeposits
        ]);
    }
    public function showAllInactiveUserBalances()
    {
        // Find the user by id
        $user = User::all();

        // Check if the user's status is inactive
        if ($user->status == 'inactive') {
            // Return the user's wallet balance
            return response()->json(['balance' => $user->balance]);
        }

        // If the user's status is not inactive, return an appropriate message
        return response()->json(['message' => 'User status is not inactive']);
    }
}
}
