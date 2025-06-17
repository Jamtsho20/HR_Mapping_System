<?php

namespace App\Http\Controllers\MyProfile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MyProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:my-profile/my-profile,view')->only('index');
        $this->middleware('permission:my-profile/my-profile,create')->only('store');
        $this->middleware('permission:my-profile/my-profile,edit')->only('update');
        $this->middleware('permission:my-profile/my-profile,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = auth()->user();
        return view('my-profile.my-profile.index', compact('privileges','employee'));
    }


    public function updateImage(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the employee by ID
        $user = User::findOrFail($id);
        if ($request->profile_pic) {
            // Delete existing profile pic if it exists
            if ($user && $user->profile_pic) {
                $deleteImage = delete_image($user->profile_pic);
                if (!$deleteImage) {
                    return redirect()->back()->with('msg_error', 'Profile picture couldnot be updated, please try again later.');
                }
            }
            // Upload new profile picture and update the path
            $profilePic = uploadImageToDirectory($request->profile_pic, 'images/users/');
            $user->profile_pic = $profilePic;
            $user->save();
        }

        // Redirect back with a success message
        return redirect()->back()->with('msg_success', 'Profile picture updated successfully.');
    }
}
