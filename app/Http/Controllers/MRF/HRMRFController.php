<?php

namespace App\Http\Controllers\MRF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MRF;
use App\Models\FunctionModel;
use Illuminate\Support\Facades\Auth;

class HRMRFController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:mrf/hr,view')->only('index');
        $this->middleware('permission:mrf/hr,edit')->only(['approve', 'reject']);
    }

    /**
     * Display all MRFs for HR
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();

        $query = MRF::with([
                'function',
                'department',
                'section',
                'employmentType',
                'salary',
                'requester',
                'approver'
            ])
            ->orderBy('created_at', 'desc');

        // Filter by requisition number
        if ($request->filled('requisition_number')) {
            $query->where('requisition_number', $request->requisition_number);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by function
        if ($request->filled('function_id')) {
            $query->where('function_id', $request->function_id);
        }

        $mrfs = $query->paginate(config('global.pagination', 10));
        $functions = FunctionModel::orderBy('name')->get();

        return view('mrf.hr.index', compact('mrfs', 'functions', 'privileges'));
    }

    /**
     * Show single MRF (HR view)
     */
    public function show(MRF $mrf)
    {
        $mrf->load([
            'function',
            'department',
            'section',
            'employmentType',
            'salary',
            'requester',
            'approver'
        ]);

        return view('mrf.hr.show', compact('mrf'));
    }

    /**
     * Approve MRF (HR)
     */
    public function approve(MRF $mrf)
    {
        if ($mrf->status !== 'pending') {
            return back()->with('msg_error', 'Only pending MRFs can be approved.');
        }

        $mrf->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        /**
         * OPTIONAL:
         * Increase current_strength in functions table
         */
        if ($mrf->function) {
            $mrf->function->increment('current_strength', $mrf->vacancies);
        }

        return back()->with('msg_success', 'MRF approved successfully.');
    }

    /**
     * Reject MRF (HR)
     */
    public function reject(Request $request, MRF $mrf)
    {
        if ($mrf->status !== 'pending') {
            return back()->with('msg_error', 'Only pending MRFs can be rejected.');
        }

        $request->validate([
            'remarks' => 'nullable|string|max:255',
        ]);

        $mrf->update([
            'status'      => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'remarks'     => $request->remarks,
        ]);

        return back()->with('msg_success', 'MRF rejected successfully.');
    }
}
