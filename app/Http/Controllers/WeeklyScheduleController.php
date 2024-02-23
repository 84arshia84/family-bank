<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WeeklySchedule;
use Auth;
use Illuminate\Http\Request;

class WeeklyScheduleController extends Controller
{
    public function store(Request $request)
    {
        // اطمینان از ورود کاربر
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // اعتبارسنجی داده‌های درخواست
        $validatedData = $request->validate([
            'title' => 'required|string',
            'short_description' => 'required|string',
            'more_details' => 'required|string',
            'date' => 'required|date',
        ]);

        // ایجاد زمانبندی هفتگی جدید
        $weeklySchedule = new WeeklySchedule();
        $weeklySchedule->user_id = $user->id;
        $weeklySchedule->title = $validatedData['title'];
        $weeklySchedule->short_description = $validatedData['short_description'];
        $weeklySchedule->more_details = $validatedData['more_details'];
        $weeklySchedule->date = $validatedData['date'];
        $weeklySchedule->save();

        // بازگشت پاسخ موفق
        return response()->json(['message' => 'Weekly schedule added successfully'], 201);
    }

    public function show($id)
    {
        // اطمینان از ورود کاربر
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // یافتن و نمایش زمانبندی هفتگی
        $weeklySchedule = WeeklySchedule::find($id);
        if (!$weeklySchedule) {
            return response()->json(['message' => 'Weekly schedule not found'], 404);
        }

        // بررسی مالکیت کاربر
        if ($weeklySchedule->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // بازگشت اطلاعات زمانبندی هفتگی
        return response()->json($weeklySchedule, 200);
    }
}
