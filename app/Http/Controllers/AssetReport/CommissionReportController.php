<?php

namespace App\Http\Controllers\AssetReport;

use App\Exports\CommissionExport;
use App\Http\Controllers\Controller;
use App\Models\AssetCommissionApplication;
use App\Models\AssetCommissionDetail;
use App\Models\MasSite;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CommissionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset-report/commission-report,view')->only('index');
        $this->middleware('permission:asset-report/commission-report,create')->only('store');
        $this->middleware('permission:asset-report/commission-report,edit')->only('update');
        $this->middleware('permission:asset-report/commission-report,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        // $commissions = AssetCommissionApplication::with(['audit_logs' => function($query){
        //         $query->whereIn('status', [-1, 3]);
        //     }])
        //     ->filter($request, false)
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(config('global.pagination'))
        //     ->withQueryString();

        $commissions = AssetCommissionApplication::with(['audit_logs', 'details',])
            ->whereIn('status', [-1, 1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'))
            ->withQueryString();
        // dd($commissions);

        return view('asset-report.commission-report.index', compact('privileges', 'commissions'));
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
        $commission = AssetCommissionApplication::with(['details'])->findOrFail($id);
        $empDetails = empDetails($commission->created_by);
        return view('asset-report.commission-report.show', compact('commission', 'empDetails'));
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
    public function exportCommission(Request $request)
    {

        $commissions = AssetCommissionApplication::with(['audit_logs', 'details',])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.commission-report-pdf', compact('commissions', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');

        // Return the PDF download
        return $pdf->download('Commission.pdf');
    }

    public function exportCommissionExcel(Request $request)
    {
        return Excel::download(new CommissionExport($request), 'Commission.xlsx');
    }
    public function printCommissionReport(Request $request)
    {
        $commissions = AssetCommissionApplication::with(['audit_logs', 'details',])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')->get();

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.commission-report-pdf', compact('commissions'))->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Commission.pdf');
    }
}
