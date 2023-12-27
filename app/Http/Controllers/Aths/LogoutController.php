<?php

namespace App\Http\Controllers\Aths;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        {
//        dd($request->user());
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logged out.'
            ]);
        }
    }
}
