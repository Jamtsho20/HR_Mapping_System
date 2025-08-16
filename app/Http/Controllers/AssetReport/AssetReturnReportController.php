<?php

namespace App\Http\Controllers\AssetReport;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AssetReturnApplication;
use App\Exports\AssetReturnExport;


class AssetReturnReportController extends Controller
{
    public function index(Request $request)
    {
        $assetReturnApplication = AssetReturnApplication::with(['audit_logs', 'details', 'histories'])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'))
            ->withQueryString();
        return view('asset-report.asset-return-report.index', compact('assetReturnApplication'));
    }


    public function exportAssetPdf(Request $request){
        $assetReturnApplication = AssetReturnApplication::with(['audit_logs', 'details', 'histories'])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $pdf = Pdf::loadView('export-report.asset-return-report-pdf', compact('assetReturnApplication', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        return $pdf->download('asset-return-report.pdf');
    }

    public function print(Request $request)
    {
        $assetReturnApplication = AssetReturnApplication::with(['audit_logs', 'details', 'histories'])
            ->whereIn('status', [-1, 3])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();

        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $pdf = Pdf::loadView('export-report.asset-return-report-pdf', compact('assetReturnApplication', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        return $pdf->stream('asset-return-report.pdf');
    }

    public function exportAssetExcel(Request $request)
    {
        return Excel::download(new AssetReturnExport($request), 'AssetReturn.xlsx');
    }
}

