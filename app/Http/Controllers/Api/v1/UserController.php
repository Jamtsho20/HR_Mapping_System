<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    protected $rules = [
        'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Allowable formats and max size
    ];

    protected $messages = [
       'profile_pic.required' => 'Profile picture is required.',
    ];
    public function updateProfilePic(Request $request)
    {
        $validator = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }
        $user = $request->user();
        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Handle the new profile picture
        if ($request->hasFile('profile_pic')) {
            // Delete existing profile picture if it exists
            if ($user && $user->profile_pic) {
                unlink($user->profile_pic); // Deletes the old profile pic from storage
            }
            // Upload new profile picture and update the path
            $profilePic = uploadImageToDirectory($request->profile_pic, 'images/users/');
            $user->profile_pic = $profilePic;
            $user->save();

            $userDetail = User::with([
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
            ])->where('email', $user->username)
                ->orWhere('username', $user->username)
                ->first();

            return response()->json([
                'message' => 'Profile picture updated successfully.',
                'user' => $userDetail,
                
            ], 200);
        }

        return response()->json(['message' => 'No profile picture provided.'], 400);
    }
}
