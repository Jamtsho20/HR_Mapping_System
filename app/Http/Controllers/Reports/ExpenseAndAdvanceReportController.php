<?php

namespace App\Http\Controllers\Reports;

use App\Exports\ExpenseExport;
use App\Http\Controllers\Controller;
use App\Models\ExpenseApplication;
use App\Models\MasDepartment;
use App\Models\MasExpenseType;
use App\Models\MasOffice;
use App\Models\MasRegion;
use App\Models\MasSection;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseAndAdvanceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/expense-and-advance-report,view')->only('index');
        $this->middleware('permission:report/expense-and-advance-report,create')->only('store');
        $this->middleware('permission:report/expense-and-advance-report,edit')->only('update');
        $this->middleware('permission:report/expense-and-advance-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $expenses = MasExpenseType::select('name', 'id')->whereNotIn('id', [3, 4])->get(); //3 and 4 is transfer claim and dsa
        $departments = MasDepartment::select('name', 'id')->get();
        $offices = MasOffice::select('name', 'id')->get();
        $regions = MasRegion::select('name', 'id')->get();
        $employeeLists = employeeList();
        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('roles.id', [7, 8]);  // Fetch users with roles 6 or 7
        })->select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();

        $expenseApplications = ExpenseApplication::with(['audit_logs' => function($query){
            $query->where('status', 3); 
        }])->filter($request, false)->whereStatus(3)->paginate(config('global.pagination'))->withQueryString();
        
        return view('report.expense-and-advance-report.index', compact('privileges', 'expenseApplications', 'regions', 'departments', 'sections', 'expenses', 'employeeLists', 'offices', 'managers'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $expense = ExpenseApplication::findOrfail($id);
        $empDetails = empDetails($expense->created_by);

        return view('report.expense-and-advance-report.show', compact('expense', 'empDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function exportExpense(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $expenses = ExpenseApplication::filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.expense-report-pdf', compact('expenses'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Expense-Report.pdf');
    }
    public function exportExpenseExcel(Request $request)
    {
        return Excel::download(new ExpenseExport($request), 'expense-report.xlsx');
    }

    public function printExpense(Request $request)
    {
        $expenses = ExpenseApplication::filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.expense-report-pdf', compact('expenses'))
            ->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Expense-Report.pdf');
    }
}
