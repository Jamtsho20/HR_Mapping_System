<?php

namespace App\Http\Controllers\Reports;

use App\Exports\ChequeExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChequeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/cheque-report,view')->only('index');
        $this->middleware('permission:report/cheque-report,create')->only('store');
        $this->middleware('permission:report/cheque-report,edit')->only('update');
        $this->middleware('permission:report/cheque-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();
 

        $cheques = FinalPaySlip::whereHas('employee.empJob', function ($query) {
            $query->where('salary_disbursement_mode', 2);
        })->filter($request)->paginate(config('global.pagination'))->withQueryString();



        return view('report.cheque-report.index', compact('privileges', 'cheques', 'employee'));
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
    public function show(string $id)
    {
        //
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

    public function exportCheque(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $cheques = FinalPaySlip::whereHas('employee.empJob', function ($query) {
            $query->where('salary_disbursement_mode', 2);
        })->filter($request)->get();



        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.cheque-report-pdf', compact('cheques'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Cheque-Report.pdf');
    }

    public function exportChequeExcel(Request $request)
    {
        return Excel::download(new ChequeExport($request), 'cheque-report.xlsx');
    }
    public function printCheque(Request $request)
    {
        $cheques = FinalPaySlip::whereHas('employee.empJob', function ($query) {
            $query->where('salary_disbursement_mode', 2);
        })->filter($request)->get();

        $pdf = Pdf::loadView('export-report.cheque-report-pdf', compact('cheques'))->setPaper('a4', 'landscape');;


        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Cheque-Report.pdf');
    }
}
