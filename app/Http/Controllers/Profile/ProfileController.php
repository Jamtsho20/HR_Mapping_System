<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($id, Request $request)
    {
        $employee = User::findOrFail($id); // Use findOrFail to handle cases where the ID is not found

        $employeeId = $employee->employee_id;
        $directory = storage_path('payslips');
        $files = array_diff(scandir($directory), ['.', '..']);

        // Filter files for the given employee ID (no month filter)
        $payslips = array_filter($files, function ($file) use ($employeeId) {
            return preg_match("/\({$employeeId}\)_\d{4}_\d{2}\.pdf$/", $file);  // Matching by employee ID only
        });

        $payslipData = [];
        foreach ($payslips as $payslip) {
            if (preg_match("/\({$employeeId}\)_(\d{4})_(\d{2})\.pdf$/", $payslip, $matches)) {
                $year = $matches[1];  // Extracted year
                $month = $matches[2];  // Extracted month (numeric)

                // Convert month number to human-readable format
                $monthName = Carbon::createFromFormat('Y-m-d', "2025-$month-01")->format('F');
                // Store the payslip data along with year and human-readable month
                $payslipData[] = [
                    'filename' => $payslip,
                    'year' => $year,
                    'month' => $monthName  
                ];
            }
        }
        $payslips = $payslipData;

        return view('user-profile.user-profile.show', compact('employee', 'payslips'));
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

    public function viewPayslip($filename)
    {
        $path = storage_path("payslips/{$filename}");

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return Response::file($path, ['Content-Type' => 'application/pdf']);
    }
}
