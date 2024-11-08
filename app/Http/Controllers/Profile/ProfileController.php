<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($id)
    {
        // Retrieve the employee by their ID
        $employee = User::findOrFail($id); // Use findOrFail to handle cases where the ID is not found

        // Pass the employee data to the view
        return view('user-profile.user-profile.show', compact('employee'));
    }

    // Method to display the edit form
    public function edit($id)
    {
        $employee = User::findOrFail($id);
        return view('profile.edit', compact('employee'));
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
                if(!$deleteImage){
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
