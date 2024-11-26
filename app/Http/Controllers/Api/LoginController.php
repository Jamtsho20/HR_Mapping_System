<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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
            // $user = User::with('empJob')->where('email', $request->username)->orWhere('username', $request->username)->first();
            $user = User::with([
                'empJob.department:id,name,mas_employee_id',      // Only load the department name
                'empJob.department.departmentHead:id,name',
                'empJob.section:id,name',         // Only load the section name
                'empJob.designation:id,name',     // Only load the designation name
                'empJob.grade:id,name',           // Only load the grade name
                'empJob.gradeStep:id,name',       // Only load the grade step name
                'empJob.empType:id,name',         // Only load the employment type name
                'empJob.supervisor:id,name,username', // Only load the supervisor's name
                'empJob.office:id,name',           // Only load the office name
                'roles:id,name' 
            ])->where('email', $request->username)
              ->orWhere('username', $request->username)
              ->first();
          
            // If user found, return as JSON
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            
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

    //sent password reset lint
    public function handleForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => __('passwords.sent')], 200)
                : response()->json(['message' => __('passwords.user')], 400);
    }

    //change password
    public function handleChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password'
        ]);

        try {
            if (Hash::check($request->input('current_password'), $request->user()->password)) {
                $request->user()->update([
                    'password' => bcrypt($request->input('new_password'))
                ]);
                return response()->json([
                    'message' => 'Password has been updated successfully.',
                ], 200);
            }
            return response()->json([
                'message' => 'Your old password did not match our records.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
            ], 500);
        }
    }
}
