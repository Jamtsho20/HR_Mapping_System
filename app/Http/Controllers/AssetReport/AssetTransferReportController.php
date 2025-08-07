<?php

namespace App\Http\Controllers\AssetReport;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AssetTransferApplication;
use App\Exports\AssetTransferExport;


class AssetTransferReportController extends Controller
{
    public function index(Request $request)
    {
        $assetTransferApplication = AssetTransferApplication::with(['audit_logs', 'details', 'histories'])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'))
            ->withQueryString();
        return view('asset-report.asset-transfer-report.index', compact('assetTransferApplication'));
    }

    public function exportPdf(Request $request)
    {
        $assetTransferApplication = AssetTransferApplication::with(['audit_logs', 'details', 'histories'])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $pdf = Pdf::loadView('export-report.asset-transfer-report-pdf', compact('assetTransferApplication', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        return $pdf->download('asset-transfer-report.pdf');
    }

    public function print(Request $request)
    {
        $assetTransferApplication = AssetTransferApplication::with(['audit_logs', 'details', 'histories'])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();

        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $pdf = Pdf::loadView('export-report.asset-transfer-report-pdf', compact('assetTransferApplication', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        return $pdf->stream('asset-transfer-report.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new AssetTransferExport($request), 'AssetTransfer.xlsx');
    }
}

