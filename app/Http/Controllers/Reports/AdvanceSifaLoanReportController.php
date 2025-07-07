<?php

namespace App\Http\Controllers\Reports;

use App\Exports\AdvanceSifaLoanExport;
use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasDepartment;
use App\Models\MasSection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdvanceSifaLoanReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:report/advance-sifa-loan-report,view')->only('index');
        $this->middleware('permission:report/advance-sifa-loan-report,create')->only('store');
        $this->middleware('permission:report/advance-sifa-loan-report,edit')->only('update');
        $this->middleware('permission:report/advance-sifa-loan-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();
        $departments = MasDepartment::select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();
        $advancesifaReports = AdvanceApplication::filter($request, false)->whereStatus(4)->paginate(config('global.pagination'))->withQueryString();


        return view('report.advance-sifa-loan-report.index', compact('privileges', 'employee', 'departments', 'sections', 'advancesifaReports'));
    }
    public function show(string $id)
    {
        $advance = AdvanceApplication::with('advanceType')->findOrFail($id);
        $empDetails = empDetails($advance->created_by);
        $repayments = \App\Models\SifaLoanRepayment::where('advance_application_id', $advance->id)->get();

        // Check if the loan is fully paid off
        $paidOffEntry = DB::table('loan_e_m_i_deductions')
            ->where('advance_application_id', $advance->id)
            ->where('is_paid_off', 1) // Adjust this column if you use another logic
            ->latest('updated_at')
            ->first();

        $paidOffMessage = null;
        if ($paidOffEntry) {
            $paidOffMessage = "This loan is paid off on " . \Carbon\Carbon::parse($paidOffEntry->updated_at)->format('F d, Y');
        }

        return view('report.advance-sifa-loan-report.show', compact('advance', 'empDetails', 'repayments','paidOffMessage','paidOffEntry'));
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

    public function exportSifaLoanReport(Request $request)
    {
        // Fetch all approved SIFA advance applications based on filters
        $advancesifaReports = AdvanceApplication::filter($request, false)
            ->whereStatus(4)
            ->get();

        // Calculate total approved amount for all fetched reports
        $totalApprovedAmount = $advancesifaReports->sum('approved_amount');

        // Fetch all repayments for these advance applications
        $repayments = \App\Models\SifaLoanRepayment::whereIn('advance_application_id', $advancesifaReports->pluck('id'))->get();

        // Generate PDF view, pass all necessary data
        $pdf = Pdf::loadView('export-report.sifa-loan-report-pdf', compact('advancesifaReports', 'repayments', 'totalApprovedAmount'))->setPaper('a4', 'landscape');

        // Return the PDF stream
        return $pdf->stream('Advance-SIFA-Loan-Report.pdf');
    }
   public function exportSifaLoanExcel(Request $request)
    {
        return Excel::download(new AdvanceSifaLoanExport($request), 'advance-sifa-loan-report.xlsx');
    }
    
}
