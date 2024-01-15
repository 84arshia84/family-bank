<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json($this->notificationIndex());
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(Notification::make($request->validate([
            'date' => 'required|date',
            'text' => 'required|string',
            'user_id' => ['required|integer', Rule::exists('users', 'id')],
        ])));
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
