<?php

namespace App\Http\Controllers\MRF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MRF;
use App\Models\FunctionModel;
use App\Models\MasDepartment;
use App\Models\MasSection;
use App\Models\MasEmploymentType;
use App\Models\MasDzongkhag;
use App\Models\PaySlipDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\MasGrade;
use App\Models\MasGradeStep;


class HODMRFController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:mrf/hod,view')->only('index');
        $this->middleware('permission:mrf/hod,create')->only(['create', 'store']);
        $this->middleware('permission:mrf/hod,edit')->only('update');
        $this->middleware('permission:mrf/hod,delete')->only('destroy');
    }

    /**
     * Display all MRFs created by the HOD
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();

        $query = MRF::with(['function', 'department', 'section', 'employmentType', 'requester', 'approver'])
            ->where('requested_by', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by function_id if provided
        if ($request->filled('requisition_number')) {
            $query->where('requisition_number', $request->requisition_number);
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $mrfs = $query->paginate(config('global.pagination', 10));

        return view('mrf.hod.index', compact('mrfs', 'privileges'));
    }


    /**
     * Show form to create new MRF
     */
    public function create(Request $request)
    {
        $privileges = $request->instance();

        $functions       = FunctionModel::orderBy('name')->get();
        $departments     = MasDepartment::orderBy('name')->get();
        $sections        = MasSection::orderBy('name')->get();
        $employmentTypes = MasEmploymentType::orderBy('name')->get();
        $grades = MasGrade::orderBy('name')->get(['id', 'name']);
        $points = [];
        $startingSalary = 0;
        $increment = 0;
        $endingSalary = 0;

        $dzongkhags      = MasDzongkhag::orderBy('dzongkhag')->get();

        return view(
            'mrf.hod.create',
            compact('functions', 'departments', 'dzongkhags', 'sections', 'employmentTypes', 'grades', 'points', 'startingSalary', 'increment', 'endingSalary', 'privileges')
        );
    }

    /**
     * Store new MRF
     */
    public function store(Request $request)
    {
        $request->validate([
            'mas_function_id'       => 'required|exists:mas_function,id',
            'department_id'     => 'required|exists:mas_departments,id',
            'employment_type_id' => 'required|exists:mas_employment_types,id',
            'reason'            => 'required|string|min:5',
            'mrf_type'          => 'required|in:new,replacement',
            'vacancies'         => 'required|integer|min:1',
            'job.mas_grade_step_id'      => 'required|exists:mas_grade_steps,id',
        ]);

        // Auto-generate requisition number
        $requisition_number = strtoupper(Str::random(5));

        MRF::create([
            'requisition_number'   => $requisition_number,
            'date_of_requisition'  => $request->date_of_requisition ?? now(),
            'mas_function_id' => $request->mas_function_id,
            'department_id'        => $request->department_id,
            'section_id'           => $request->section_id,
            'employment_type_id'   => $request->employment_type_id,
            'location'             => $request->location,
            'experience'           => $request->experience,
            'vacancies'            => $request->vacancies,
            'mas_grade_step_id' => $request->input('job.mas_grade_step_id'),
            'mrf_type'             => $request->mrf_type,
            'job_description'      => $request->job_description,
            'reason'               => $request->reason,
            'remarks'              => $request->remarks,
            'requested_by'         => Auth::id(),
            'status'               => 'pending',
            'approved_by'          => null,
            'approved_at'          => null,
        ]);

        return redirect()->route('hod.index')
            ->with('msg_success', 'MRF submitted successfully');
    }

    /**
     * Show edit form for own pending MRF
     */
    public function edit($id)
    {
        $mrf = MRF::where('requested_by', Auth::id())
            ->where('status', 'pending')
            ->with(['function', 'department', 'section', 'employmentType', 'salary'])
            ->findOrFail($id);

        $functions       = FunctionModel::orderBy('name')->get();
        $departments     = MasDepartment::orderBy('name')->get();
        $sections        = MasSection::orderBy('name')->get();
        $employmentTypes = MasEmploymentType::orderBy('name')->get();
        $salaries        = PaySlipDetail::orderBy('id')->get();

        return view(
            'mrf.hod.edit',
            compact('mrf', 'functions', 'departments', 'sections', 'employmentTypes', 'salaries')
        );
    }

    /**
     * Update own pending MRF
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'function_id'       => 'required|exists:functions,id',
            'department_id'     => 'required|exists:departments,id',
            'employment_type_id' => 'required|exists:employment_types,id',
            'reason'            => 'required|string|min:5',
            'mrf_type'          => 'required|in:new,replacement',
            'vacancies'         => 'required|integer|min:1',
        ]);

        $mrf = MRF::where('requested_by', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $mrf->update([
            'function_id'          => $request->function_id,
            'department_id'        => $request->department_id,
            'section_id'           => $request->section_id,
            'employment_type_id'   => $request->employment_type_id,
            'location'             => $request->location,
            'experience'           => $request->experience,
            'vacancies'            => $request->vacancies,
            'basic_salary_id'      => $request->basic_salary_id,
            'mrf_type'             => $request->mrf_type,
            'job_description'      => $request->job_description,
            'reason'               => $request->reason,
            'remarks'              => $request->remarks,
        ]);

        return redirect()->route('hod.lists.index')
            ->with('msg_success', 'MRF updated successfully');
    }

    /**
     * Delete own pending MRF
     */
    public function destroy($id)
    {
        try {
            $mrf = MRF::where('requested_by', Auth::id())
                ->where('status', 'pending')
                ->findOrFail($id);

            $mrf->delete();

            return back()->with('msg_success', 'MRF deleted successfully');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Only pending MRFs can be deleted.');
        }
    }
}
