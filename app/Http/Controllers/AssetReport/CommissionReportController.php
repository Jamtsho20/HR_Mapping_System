<?php

namespace App\Http\Controllers\AssetReport;

use App\Http\Controllers\Controller;
use App\Models\AssetCommissionApplication;
use App\Models\AssetCommissionDetail;
use App\Models\MasSite;
use Illuminate\Http\Request;

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
        $commissions = AssetCommissionApplication::with(['audit_logs' => function($query){
                $query->whereIn('status', [-1, 3]); 
            }])
            ->filter($request, false)
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination'))
            ->withQueryString();

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
        $commission = AssetCommissionDetail::with(['details'])->findOrFail($id);
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
}
