<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        try {
            $user = User::with('empJob')->where('email', $request->username)->orWhere('username', $request->username)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid username or password.'
                ], 401);
            }

            $token = $user->createToken($request->username)->plainTextToken;
            
            return response()->json([ 
                'message' => 'Authenticated',
                'user' => $user,
                'token' => $token,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                // 'message' => 'Something went wrong. Try again later',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
