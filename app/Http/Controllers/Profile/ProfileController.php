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

    // public function updateImage(Request $request, $id)
    // {
    //     $request->validate([
    //         'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation as needed
    //     ]);

    //     $employee = User::findOrFail($id);

    //     // Check if the employee already has a profile picture and delete the old one
    //     if ($employee->profile_pic && file_exists(public_path($employee->profile_pic))) {
    //         unlink(public_path($employee->profile_pic));
    //     }

    //     if ($request->hasFile('profile_pic')) {
    //         $image = $request->file('profile_pic');

    //         // Store the image in `public/images/users`
    //         $imageName = time() . '_' . $image->getClientOriginalName();
    //         $image->move(public_path('images/users'), $imageName);

    //         // Update the path in the database
    //         $employee->profile_pic = '/images/users/' . $imageName;
    //         $employee->save();
    //     }

    //     return redirect()->back()->with('success', 'Profile picture updated successfully.');
    // }

    public function updateImage(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the employee by ID
        $employee = User::findOrFail($id);

        // Use the helper function to update the image
        updateImage($request, $employee, 'profile_pic', 'images/users');

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
}
