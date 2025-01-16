<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\GoodIssueApplication;
use App\Models\RequisitionApplication;
use Illuminate\Http\Request;

class GoodsIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset/goods-issue,view')->only('index', 'show');
        $this->middleware('permission:asset/goods-issue,create')->only('store');
        $this->middleware('permission:asset/goods-issue,edit')->only('update');
        $this->middleware('permission:asset/goods-issue,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
      
        return view('asset.goods-issue.index',compact('privileges'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $excludedRequisitionIds = GoodIssueApplication::pluck('requisition_id')->filter()->toArray(); //filter is used incase travel_authorization_id column is null to remove those
        $requisitions = RequisitionApplication::where('status', 3)
                                    ->when(!empty($excludedRequisitionIds), function ($query) use ($excludedRequisitionIds) {
                                        $query->whereNotIn('id', $excludedRequisitionIds);
                                    })
                                    ->get(['id', 'requisition_no']); // Always fetch after conditions are applied

        return view('asset.goods-issue.create', compact('requisitions'));
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
}
