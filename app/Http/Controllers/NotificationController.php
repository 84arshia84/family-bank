<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json($this->notificationIndex());
    }

    public function storeRequestWithdrawal()
    {
        $user = Role::findByName('admin')->users()->first();
        return response()->json(Notification::create([
            'date' => today()->toDateString(),
            'text' => "اینجانب درخواست برداشت وجه دارم.",
            'user_id' => $user->id
        ]));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
            'text' => 'required|string',
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|integer|exists:users,id'
        ]);
        $notifications = [];
        foreach ($request->user_ids as $user_id) {
            $notifications[] = Notification::create([
                'date' => $request->date,
                'text' => $request->text,
                'user_id' => $user_id
            ]);
        }
        return response()->json($notifications);
    }

    public function storeForAllUsers(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'nullable|date|date_format:Y-m-d',
            'text' => 'required|string',
        ]);
        $notifications = [];
        $users = User::all('id');
        foreach ($users as $user) {
            $notifications [] = [
                'date' => $request->date ?? now()->toDateString(),
                'text' => $request->text,
                'user_id' => $user->id
            ];
        }
        Notification::insert($notifications);
        return response()->json(['message' => 'notifications created successfully']);
    }

    public function show(Notification $notification): JsonResponse
    {

        return response()->json($notification);
    }

    public function update(Notification $notification, Request $request): JsonResponse
    {
        $notification->update($request->validate([
            'date' => 'required|date',
            'text' => 'required|string',
            'user_id' => ['required|integer', Rule::exists('users', 'id')],
        ]));
        return response()->json(Notification::find($notification->id));
    }

    public function destroy(Notification $notification): JsonResponse
    {
        $notification->delete();
        return response()->json(['message' => 'notification deleted successfully']);
    }

    public function indexUserNotifications(): JsonResponse
    {
        return response()->json($this->notificationIndex(auth()->user()));
    }

    protected function notificationIndex(User $user = null)
    {
        $notifications = Notification::query();
        if ($user) {
            $notifications = $notifications->where('user_id', $user->id);
        }
        return $notifications->orderByDesc('created_at')->get();
    }
}
