<?php

namespace App\Http\Controllers\AssetReport;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ReceivedSerial;
use App\Exports\CwipReportExport;

class CwipReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:asset-report/cwip-report,view')->only('index');
        $this->middleware('permission:asset-report/cwip-report,create')->only('store');
        $this->middleware('permission:asset-report/cwip-report,edit')->only('update');
        $this->middleware('permission:asset-report/cwip-report,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $receivedSerials = ReceivedSerial::with('requisitionDetail.grnItemDetail.item')->filter($request, false)->where('is_commissioned', 0)->orderBy('created_at', 'desc')->paginate(config('global.pagination'))->withQueryString();

        return view('asset-report.cwip-report.index' , compact('receivedSerials'));
    }



    public function exportCwipPdf(Request $request)
    {
        $receivedSerials = ReceivedSerial::with('requisitionDetail.grnItemDetail.item')->filter($request, false)->where('is_commissioned', 0)->orderBy('created_at', 'desc')->get();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $pdf = Pdf::loadView('export-report.cwip-report-pdf', compact('receivedSerials', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        return $pdf->download('cwip-report-pdf');
    }

    public function exportCwipExcel(Request $request)
    {
        return Excel::download(new CwipReportExport($request), 'cwip-report.xlsx');
    }

    public function printCwip(Request $request){
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $receivedSerials = ReceivedSerial::with('requisitionDetail.grnItemDetail.item')->filter($request, false)->where('is_commissioned', 0)->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('export-report.cwip-report-pdf', compact('receivedSerials', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        return $pdf->stream('cwip-report-pdf');
    }
}
