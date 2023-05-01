<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = auth()->user();

        return view('profile', compact('user'));
    }

    public function getChangePassword()
    {
        return view('system-settings.change-password');
    }

    public function postChangePassword(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ];

        $this->validate($request, $rules);
    	if(\Hash::check($request->input('old_password'), $request->user()->password)){
			$request->user()->update([
                'password' => bcrypt($request->input('new_password'))
            ]);
            return redirect('change-password')->with('msg_success', 'Password Successfully updated');
		}
		return redirect('change-password')->with('msg_error', 'Your old password did not match our records');
    }
}
