<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\MasRequisitionType;
use App\Models\RequisitionApplication;
use Illuminate\Http\Request;

class RequisitionApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('permission:asset/requisition-approval,view')->only('index');
        $this->middleware('permission:leave/requisition-approval,edit')->only('update');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $reqTypes = MasRequisitionType::get(['id', 'name']);
        $user = auth()->user();
        $requisitions = RequisitionApplication::whereHas('histories', function ($query) use ($user) {
            $query->where('approver_emp_id', $user->id)
                ->where('application_type', \App\Models\RequisitionApplication::class);
        })
            ->whereNotIn('status', [-1, 3])
            ->filter($request, false) //sent onesOenRecord parameter as flase as it need to fetch all despites of authenticated user
            ->orderBy('created_at')
            ->paginate(config('global.pagination'))
            ->withQueryString();

        return view('asset.requisition-approval.index', compact('privileges', 'requisitions', 'reqTypes'));
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
    public function edit($id)
    {
        $application = RequisitionApplication::with('details', 'requisitionType:id,name')->findOrFail($id);
        $reqTypes = MasRequisitionType::get();
        return view('asset.requisition-apply.edit', compact('application', 'reqTypes'));
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
