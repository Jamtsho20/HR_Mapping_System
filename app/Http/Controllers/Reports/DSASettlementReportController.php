<?php

namespace App\Http\Controllers\Reports;

use App\Exports\DSASettlementExport;
use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\DsaClaimApplication;
use App\Models\MasDepartment;
use App\Models\MasOffice;
use App\Models\MasRegion;
use App\Models\MasSection;
use App\Models\TravelAuthorizationApplication;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DSASettlementReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/dsa-settlement-report,view')->only('index');
        $this->middleware('permission:report/dsa-settlement-report,create')->only('store');
        $this->middleware('permission:report/dsa-settlement-report,edit')->only('update');
        $this->middleware('permission:report/dsa-settlement-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $departments = MasDepartment::select('name', 'id')->get();
        $offices = MasOffice::select('name', 'id')->get();
        $regions = MasRegion::select('name', 'id')->get();
        $employeeLists = employeeList();
        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('roles.id', [7, 8]);  // Fetch users with roles 6 or 7
        })->select('name', 'id')->get();
        $sections = MasSection::select('name', 'id')->get();

        $dsaClaim = DsaClaimApplication::with([
            'audit_logs' => function ($query) {
                $query->where('status', 3);
            },
            'dsaClaimDetails',
            'dsaClaimMappings.dsaDetails',
            'dsaClaimMappings.travelAuthorization',
            'dsaClaimMappings.advanceApplication',
            'dsaClaimMappings.dsaClaimApplication'
        ])->filter($request, false)
            ->paginate(config('global.pagination'))
            ->withQueryString();

        return view('report.dsa-settlement-report.index', compact('privileges', 'dsaClaim', 'regions', 'departments', 'sections', 'employeeLists', 'offices', 'managers'));
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
        $oldDataFlag = true;
        $travelNosString = "";
        $advanceNosString = "";
        $approvalDetail = getApplicationLogs(DsaClaimApplication::class, $id);
        if (DsaClaimApplication::findOrFail($id)->travel_authorization_id != null) {
            $dsa = DsaClaimApplication::findOrFail($id);
        } else {
            $dsa = DsaClaimApplication::with(['dsaClaimMappings.dsaDetails'])->findOrFail($id);

            // Extract Travel Authorization IDs
            $travelNumbers = $dsa->dsaClaimMappings->pluck('travel_authorization_id')->filter()->toArray();

            // Extract Advance Application IDs (if they exist)
            $advanceNumbers = $dsa->dsaClaimMappings->pluck('advance_application_id')->filter()->toArray();

            // Fetch Travel Authorization Numbers as key-value pairs (id => travel_no)
            $travelNos = TravelAuthorizationApplication::whereIn('id', $travelNumbers)
                ->pluck('transaction_no', 'id');

            // Fetch Advance Application Numbers as key-value pairs (id => transaction_no)
            $advanceNos = AdvanceApplication::whereIn('id', $advanceNumbers)
                ->pluck('transaction_no', 'id');


            // Attach both transaction_no and transaction_no to each dsaClaimMapping
            $dsa->dsaClaimMappings->transform(function ($mapping) use ($travelNos, $advanceNos) {
                $mapping->transaction_no = $travelNos[$mapping->travel_authorization_id] ?? null;
                $mapping->transaction_no = $advanceNos[$mapping->advance_application_id] ?? null;

                $newDays = $mapping->number_of_days ?? 0; // Ensure total_days is available for each mapping
                // Replace with actual daily allowance from config or DB
                $DAILY_ALLOWANCE = $mapping->dsaDetails->first()->daily_allowance;
                if ($newDays <= 15) {
                    $mapping->formula = "$DAILY_ALLOWANCE * $newDays day(s)";
                } else {
                    $mapping->formula = "($DAILY_ALLOWANCE * 15 day(s)) + (" . ($DAILY_ALLOWANCE / 2) . " * " . ($newDays - 15) . " day(s)) =";
                }
                return $mapping;
            });

            // Now, $dsa->dsaClaimMappings contains 'transaction_no' and 'transaction_no' for each mapping

            $travelNosString = $travelNos->implode(', ');
            $advanceNosString = $advanceNos->implode(', ');

            $oldDataFlag = false;
            $approvalDetail = getApplicationLogs(DsaClaimApplication::class, $id);
        }


        $empDetails = empDetails($dsa->created_by);

        return view('report.dsa-settlement-report.show', compact('dsa', 'empDetails', 'oldDataFlag', 'travelNosString', 'advanceNosString', 'approvalDetail'));
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
    public function exportDSASettlement(Request $request)
    {

        // Load all bookings with their dzongkhag names
        $dsaClaim = DsaClaimApplication::filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.dsa-settlement-report-pdf', compact('dsaClaim'))->setPaper('a4', 'landscape');;

        // Return the PDF download
        return $pdf->download('DSA-Settlement-Report.pdf');
    }
    public function exportDSASettlementExcel(Request $request)
    {
        return Excel::download(new DSASettlementExport($request), 'dsa-settlement-report.xlsx');
    }

    public function printDSASettlement(Request $request)
    {
        $dsaClaim = DsaClaimApplication::filter($request, false)->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.dsa-settlement-report-pdf', compact('dsaClaim'))
            ->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('DSA-Settlement-Report.pdf');
    }
}
