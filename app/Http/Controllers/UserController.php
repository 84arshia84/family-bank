<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function add_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'fatherName' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:users',
            'national_id' => 'required',
            'password' => 'required|string|min:6',
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
        $user->update($request->all());
        $user->save();
        return response()->json([
            'update_user'
        ]);
    }
    public function user_image(Request $request,$user_id)
    {
        $user=User::findOrFail($user_id);
        $img = $user->addMedia($request->image)->toMediaCollection('user'.$user_id);
        return $img;
    }


}
