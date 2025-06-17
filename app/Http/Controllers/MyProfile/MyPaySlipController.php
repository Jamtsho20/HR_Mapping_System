<?php

namespace App\Http\Controllers\MyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MyPaySlipController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:my-profile/my-payslip,view')->only('index');
        $this->middleware('permission:my-profile/my-payslip,create')->only('store');
        $this->middleware('permission:my-profile/my-payslip,edit')->only('update');
        $this->middleware('permission:my-profile/my-payslip,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = auth()->user(); // or $request->user();

        $employeeId = $employee->employee_id;
        $directory = storage_path('payslips');
        $payslipData = [];

        if (is_dir($directory)) {
            $files = array_diff(scandir($directory), ['.', '..']);

            // Filter files for the given employee ID
            $payslips = array_filter($files, function ($file) use ($employeeId) {
                return preg_match("/\({$employeeId}\)_\d{4}_\d{2}\.pdf$/", $file);
            });

            // Extract year and month from filename
            foreach ($payslips as $payslip) {
                if (preg_match("/\({$employeeId}\)_(\d{4})_(\d{2})\.pdf$/", $payslip, $matches)) {
                    $year = $matches[1];
                    $month = $matches[2];

                    $monthName = \Carbon\Carbon::createFromFormat('Y-m-d', "2025-$month-01")->format('F');

                    $payslipData[] = [
                        'filename' => $payslip,
                        'year' => $year,
                        'month' => $monthName
                    ];
                }
            }
        }

        return view('my-profile.my-payslip.index', compact('privileges', 'employee', 'payslipData','payslips'));
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
