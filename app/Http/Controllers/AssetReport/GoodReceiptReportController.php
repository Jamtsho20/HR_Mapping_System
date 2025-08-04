<?php

namespace App\Http\Controllers\AssetReport;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ReceivedSerial;
use App\Exports\GoodReceiptExport;

class GoodReceiptReportController extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:asset-report/good-receipt-report,view')->only('index');
        $this->middleware('permission:asset-report/good-receipt-report,create')->only('store');
        $this->middleware('permission:asset-report/good-receipt-report,edit')->only('update');
        $this->middleware('permission:asset-report/good-receipt-report,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $receivedSerials = ReceivedSerial::where('is_received',1)->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'))
            ->withQueryString();
        return view('asset-report.good-receipt-report.index',compact('receivedSerials'));
    }

    public function exportGoodReceiptExcel(Request $request)
    {
        return Excel::download(new GoodReceiptExport($request), 'GoodReceipt.xlsx');
    }

    public function exportGoodReceiptPdf(Request $request)
    {
        $receivedSerials = ReceivedSerial::where('is_received',1)->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $pdf = Pdf::loadView('export-report.good-receipt-report-pdf', compact('receivedSerials', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        return $pdf->download('GoodReceipt.pdf');
    }

    public function printGoodReceipt(Request $request){
        $receivedSerials = ReceivedSerial::where('is_received',1)->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();

        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $pdf = Pdf::loadView('export-report.good-receipt-report-pdf', compact('receivedSerials', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        return $pdf->stream('GoodReceipt.pdf');
    }

}
