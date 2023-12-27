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
        if ($request->hasFile('img'))
        {
            $user->addMediaFromRequest('img')->toMediaCollection('add_avatar_for_user');
            $user->load('media');
        }
        $user->update($request->all());
        $user->save();
        return response()->json([
            'update_user'
        ]);
    }
//    public function user_image(Request $request,$user_id)
//    {
//        $user=User::findOrFail($user_id);
//        $img = $user->addMedia($request->image)->toMediaCollection('user'.$user_id);
//        return $img;
//    }
//

}

//
//namespace App\Http\Controllers\Aths;
//
//use App\Http\Controllers\Controller;
//use App\Models\User;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Hash;
//use Illuminate\Validation\ValidationException;
//
//class LoginController extends Controller
//{
//    /**
//     * Handle the incoming request.
//     */
//    public function __invoke(Request $request)
//    {
//        $user = User::where('phone_number', $request->phone_number)->first();
//        if (!$user || !Hash::check($request->password, $user->password)) {
//            throw ValidationException::withMessages([
//                'password is not true'
//            ]);
//        }
//        $token = $user->createToken('api_token')->plainTextToken;
//        return response()->json([
//            'user' => $user,
//            'token' => $token,
//        ]);
//    }
//}
//
//
//
//
//
//
//
//
//
//namespace App\Http\Controllers\Aths;
//
//use App\Http\Controllers\Controller;
//use App\Models\User;
//use Illuminate\Http\Request;
//
//class LogoutController extends Controller
//{
//    /**
//     * Handle the incoming request.
//     */
//    public function __invoke(Request $request)
//    {
////        dd($request->user());
//        $request->user()->currentAccessToken()->delete();
//        return response()->json([
//            'message' => 'Logged out.'
//        ]);
//    }
//}

