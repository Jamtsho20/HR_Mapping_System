<?php

namespace App\Http\Controllers\Reports;

use App\Exports\SifaExport;
use App\Http\Controllers\Controller;
use App\Models\FinalPaySlip;
use App\Models\SifaContrHistorical;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use Illuminate\Pagination\LengthAwarePaginator;

class SIFAContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:report/sifa-contribution,view')->only('index');
        $this->middleware('permission:report/sifa-contribution,create')->only('store');
        $this->middleware('permission:report/sifa-contribution,edit')->only('update');
        $this->middleware('permission:report/sifa-contribution,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $employee = employeeList();
        $sifaContributions = collect(); // Default empty

        $hasFilters = $request->filled('employee_id') || $request->filled('cid_no');
        $isOldYear = $request->filled('year') && $request->year < '2025-01';

        if ($request->year == '' && $hasFilters) {
            // Case 1: Merge both sources if year is missing but filters are provided
            $oldData = SifaContrHistorical::filter($request)->get();
            $newData = FinalPaySlip::filter($request)->get();

            $merged = $oldData->merge($newData)->sortByDesc('created_at')->values();

            // Manual pagination
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = config('global.pagination');
            $currentItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $sifaContributions = new LengthAwarePaginator(
                $currentItems,
                $merged->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } elseif ($isOldYear) {
            // Case 2: Only historical data for years before 2025
            $sifaContributions = SifaContrHistorical::filter($request)
                ->paginate(config('global.pagination'))
                ->withQueryString();
        } else {
            // Case 3: Default to new data
            $sifaContributions = FinalPaySlip::filter($request)
                ->paginate(config('global.pagination'))
                ->withQueryString();
        }

        // dd($sifaContributions);

        return view('report.sifa-contribution.index', compact('privileges', 'employee', 'sifaContributions'));
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
    public function exportSifa(Request $request)
    {

        // Load all bookings with their dzongkhag names
        // $sifaContributions = FinalPaySlip::filter($request)->get();
        // $totalAmount = $sifaContributions->sum(function ($paySlip) {
        //     return $paySlip->details['deductions']['SIFA'] ?? 0;
        // });

        $sifaContributions = collect(); // Default empty

        $hasFilters = $request->filled('employee_id') || $request->filled('cid_no');
        $isOldYear = $request->filled('year') && $request->year < '2025-01';

        if ($request->year == '' && $hasFilters) {
            // Case 1: Merge both sources if year is missing but filters are provided
            $oldData = SifaContrHistorical::filter($request)->get();
            $newData = FinalPaySlip::filter($request)->get();

            $merged = $oldData->merge($newData)->sortByDesc('created_at')->values();
            $sifaContributions = $merged;
        } elseif ($isOldYear) {
            // Case 2: Only historical data for years before 2025
            $sifaContributions = SifaContrHistorical::filter($request)
                ->get();
        } else {
            // Case 3: Default to new data
            $sifaContributions = FinalPaySlip::filter($request)
                ->get();
        }

        $totalAmount = 0;

        foreach ($sifaContributions as $item) {
            $sifaFromDetails = 0; //detail->sifa_contr
            $sifaFromColumn = 0;  //sifa_contr

            // Parse 'details' if present
            if (!empty($item->details)) {
                $details = is_array($item->details)
                    ? $item->details
                    : json_decode($item->details, true);

                if (json_last_error() === JSON_ERROR_NONE && isset($details['deductions']['SIFA'])) {
                    $sifaFromDetails = floatval($details['deductions']['SIFA']);
                }
            }

            // Parse 'sifa_contr' if present
            if (!empty($item->sifa_contr)) {
                $sifaFromColumn = floatval($item->sifa_contr);
            }

            // if ($request->filled('employee_id')) {

            //     $totalAmount += $sifaFromDetails + $sifaFromColumn - 300;
            // }

            // Add both if they exist
            $totalAmount += $sifaFromDetails + $sifaFromColumn;
        }
        if ($request->filled('employee_id')) {

            $totalAmount =  $totalAmount - 300;
        }

        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.sifa-contribution-report-pdf', compact('sifaContributions', 'totalAmount'))->setPaper('a4', 'landscape');

        // Return the PDF download
        return $pdf->download('Sifa-Contribution.pdf');
    }

    public function exportSifaExcel(Request $request)
    {
        return Excel::download(new SifaExport($request), 'sifa-contribution-report.xlsx');
    }
    public function printSifa(Request $request)
    {

        $sifaContributions = collect(); // Default empty

        $hasFilters = $request->filled('employee_id') || $request->filled('cid_no');
        $isOldYear = $request->filled('year') && $request->year < '2025-01';

        if ($request->year == '' && $hasFilters) {
            // Case 1: Merge both sources if year is missing but filters are provided
            $oldData = SifaContrHistorical::filter($request)->get();
            $newData = FinalPaySlip::filter($request)->get();

            $merged = $oldData->merge($newData)->sortByDesc('created_at')->values();
            $sifaContributions = $merged;
        } elseif ($isOldYear) {
            // Case 2: Only historical data for years before 2025
            $sifaContributions = SifaContrHistorical::filter($request)
                ->get();
        } else {
            // Case 3: Default to new data
            $sifaContributions = FinalPaySlip::filter($request)
                ->get();
        }

        $totalAmount = 0;

        foreach ($sifaContributions as $item) {
            $sifaFromDetails = 0; //detail->sifa_contr
            $sifaFromColumn = 0;  //sifa_contr

            // Parse 'details' if present
            if (!empty($item->details)) {
                $details = is_array($item->details)
                    ? $item->details
                    : json_decode($item->details, true);

                if (json_last_error() === JSON_ERROR_NONE && isset($details['deductions']['SIFA'])) {
                    $sifaFromDetails = floatval($details['deductions']['SIFA']);
                }
            }

            // Parse 'sifa_contr' if present
            if (!empty($item->sifa_contr)) {
                $sifaFromColumn = floatval($item->sifa_contr);
            }

            // Add both if they exist
            $totalAmount += $sifaFromDetails + $sifaFromColumn;
        }
        if ($request->filled('employee_id')) {

            $totalAmount =  $totalAmount - 300;
        }
        // Generate the PDF view and pass the data
        $pdf = Pdf::loadView('export-report.sifa-contribution-report-pdf', compact('sifaContributions', 'totalAmount'))->setPaper('a4', 'landscape');



        // Return the PDF as a stream to display it in the browser
        return $pdf->stream('Sifa-Contribution.pdf');
    }
}
