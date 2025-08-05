<?php

namespace App\Http\Controllers\AssetReport;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ReceivedSerial;
use App\Exports\GoodIssueExport;

class GoodIssueReportController extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:asset-report/good-issue-report,view')->only('index');
        $this->middleware('permission:asset-report/good-issue-report,create')->only('store');
        $this->middleware('permission:asset-report/good-issue-report,edit')->only('update');
        $this->middleware('permission:asset-report/good-issue-report,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $receivedSerials = ReceivedSerial::filter($request, false)
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'))
            ->withQueryString();

        return view('asset-report.good-issue-report.index',compact('receivedSerials'));
    }


    public function exportGoodIssue(Request $request)
    {
        $receivedSerials = ReceivedSerial::filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.good-issue-report-pdf', compact('receivedSerials', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');

        // Return the PDF download
        return $pdf->download('GoodIssue.pdf');
    }

    public function printGoodIssue(Request $request){
         $receivedSerials = ReceivedSerial::filter($request, false)
            ->orderBy('created_at', 'desc')
            ->get();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.good-issue-report-pdf', compact('receivedSerials', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');

        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('GoodIssue.pdf');
    }

    public function exportGoodIssueExcel(Request $request)
    {
        return Excel::download(new GoodIssueExport($request), 'GoodIssue.xlsx');
    }

}
