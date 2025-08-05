<?php

namespace App\Http\Controllers\AssetReport;

use App\Exports\RequisitionExport;
use App\Http\Controllers\Controller;
use App\Models\MasRequisitionType;
use App\Models\MasStore;
use App\Models\RequisitionApplication;
use App\Models\RequisitionDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RequisitionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset-report/requisition-report,view')->only('index', 'show');
        $this->middleware('permission:asset-report/requisition-report,create')->only('store');
        $this->middleware('permission:asset-report/requisition-report,edit')->only('update');
        $this->middleware('permission:asset-report/requisition-report,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $stores = MasStore::get(['id', 'name']);
        $reqTypes = MasRequisitionType::get(['id', 'name']);
        $requisitions = RequisitionApplication::with(['audit_logs', 'details',])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'))
            ->withQueryString();
        return view('asset-report.requisition-report.index', compact('privileges', 'stores', 'requisitions', 'reqTypes'));
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
        $requisition = RequisitionApplication::with(['details'])->findOrFail($id);
        // dd($requisition);
        $empDetails = empDetails($requisition->created_by);

        return view('asset-report.requisition-report.show', compact('requisition', 'empDetails'));
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

    public function exportRequisition(Request $request)
    {

        $requisitions = RequisitionApplication::with(['audit_logs', 'details',])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();
        $fromDate = $request->query('from_date') ?? null;
        $toDate = $request->query('to_date') ?? null;

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.requisition-report-pdf', compact('requisitions', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');

        // Return the PDF download
        return $pdf->download('Requisition.pdf');
    }

    public function exportRequisitionExcel(Request $request)
    {
        return Excel::download(new RequisitionExport($request), 'requisition.xlsx');
    }
    public function printRequisitionReport(Request $request)
    {
        $requisitions = RequisitionApplication::with(['audit_logs', 'details',])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();

        // Generate the PDF view and pass the data
        $fromDate = $request->query('from_date') ?? null;
        $toDate = $request->query('to_date') ?? null;

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.requisition-report-pdf', compact('requisitions', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Requisition.pdf');
    }
}
