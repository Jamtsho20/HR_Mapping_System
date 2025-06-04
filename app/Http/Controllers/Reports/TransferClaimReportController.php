<?php

namespace App\Http\Controllers\Reports;

use App\Exports\TransferClaimExport;
use App\Http\Controllers\Controller;
use App\Models\MasDepartment;
use App\Models\MasOffice;
use App\Models\MasRegion;
use App\Models\MasSection;
use App\Models\MasTransferClaim;
use App\Models\MasTransferType;
use App\Models\TransferClaimApplication;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransferClaimReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/transfer-claim-report,view')->only('index');
        $this->middleware('permission:report/transfer-claim-report,create')->only('store');
        $this->middleware('permission:report/transfer-claim-report,edit')->only('update');
        $this->middleware('permission:report/transfer-claim-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        // dd($request->all());
        $privileges = $request->instance();
        $departments = MasDepartment::select('name', 'id')->get();
        $offices = MasOffice::select('name', 'id')->get();
        $regions = MasRegion::select('name', 'id')->get();
        $employeeLists = employeeList();
        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('roles.id', [7, 8]);  // Fetch users with roles 6 or 7
        })->select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();
        $claimTypes = MasTransferClaim::whereStatus(1)->get(['id', 'name']);
        $trasferClaims = TransferClaimApplication::with(['audit_logs' => function($query){
            $query->where('status', 3); 
        }])->where('status', 3)->filter($request, false)->paginate(config('global.pagination'))->withQueryString();

        return view('report.transfer-claim-report.index', compact('privileges', 'trasferClaims', 'regions', 'departments', 'sections', 'employeeLists', 'offices', 'managers', 'claimTypes'));
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
    public function exportTransferClaim(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $trasferClaims = TransferClaimApplication::filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.transfer-claim-report-pdf', compact('trasferClaims'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('Transfer-Claim-Report.pdf');
    }
    public function exportTransferClaimExcel(Request $request)
    {
        return Excel::download(new TransferClaimExport($request), 'transfer-claim-report.xlsx');
    }

    public function printTransferClaim(Request $request)
    {
        $trasferClaims = TransferClaimApplication::filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.transfer-claim-report-pdf', compact('trasferClaims'))
            ->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Transfer-Claim-Report.pdf');
    }
}
