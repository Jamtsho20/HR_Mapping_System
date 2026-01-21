<?php

namespace App\Http\Controllers\MRF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MRF;
use App\Models\FunctionModel;
use Illuminate\Support\Facades\Auth;
use App\Models\MasDepartment;
use App\Models\MasDesignation;
use App\Models\MasEmploymentType;
use App\Models\MasGradeStep;
use App\Models\MasSection;
use App\Models\MasCompany;
use App\Models\MasGrade;
use App\Models\SystemNotification;
use App\Models\User;

class MRFController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:mrf/lists,view')->only('index');
        $this->middleware('permission:mrf/lists,create,store')->only(['create', 'store']);
        $this->middleware('permission:mrf/lists,edit')->only('update');
        $this->middleware('permission:mrf/lists,delete')->only('destroy');
    }

    /**
     * Display a listing of MRFs
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $privileges = $request->instance();

        // Get role names
        $userRoles = $user->roles->pluck('name')->toArray();

        $mrfs = MRF::with([
            'function',
            'department',
            'section',
            'designation',
            'requester',
            'approver'
        ])

            //ADMIN: only HR-approved MRFs
            ->when(in_array('Administrator', $userRoles), function ($q) {
                $q->whereIn('status', ['hr_approved', 'admin_approved']);
            })

            // HR & HOD: company-based filtering
            ->when(array_intersect($userRoles, ['Human Resource', 'Head Of Department']), function ($q) use ($user) {
                $companyId = $user->empJob?->mas_company_id;

                if ($companyId) {
                    $q->whereHas('requester.empJob', function ($q2) use ($companyId) {
                        $q2->where('mas_company_id', $companyId);
                    });
                }
            })

            ->orderBy('date_of_requisition', 'desc')
            ->paginate(15);

        $functions = FunctionModel::all();

        return view('mrf.index', compact('mrfs', 'functions', 'privileges', 'userRoles'));
    }


    /**
     * Show create form
     */
    public function create()
    {
        $user = auth()->user();
        $userCompanyId = $user->empJob?->mas_company_id;

        // Next requisition number
        $nextRequisitionNumber = 'MRF-' . date('Y') . '-' . str_pad(MRF::count() + 1, 4, '0', STR_PAD_LEFT);

        // Functions where current_strength < approved_strength and belong to user's company
        $functions = FunctionModel::where('mas_company_id', $userCompanyId)
            ->whereColumn('current_strength', '<', 'approved_strength')
            ->get();

        return view('mrf.create', [
            'nextRequisitionNumber' => $nextRequisitionNumber,
            'functions' => $functions,
            'departments' => MasDepartment::all(),
            'sections' => MasSection::all(),
            'employmentTypes' => MasEmploymentType::all(),
            'gradeSteps' => MasGradeStep::all(),
            'grades' => MasGrade::all(),
            'company' => \App\Models\MasCompany::find($userCompanyId), // only user's company
        ]);
    }

    /**
     * Store a newly created MRF
     */
    public function store(Request $request)
    {
        $request->validate([
            'mas_function_id'    => 'required|exists:mas_function,id',
            'mas_department_id'  => 'required|exists:mas_departments,id',
            'mas_section_id'     => 'required|exists:mas_sections,id',
            'designation_id'     => 'required|exists:mas_designations,id',
            'employment_type_id' => 'required|exists:mas_employment_types,id',
            'location'           => 'nullable|string|max:255',
            'experience'         => 'nullable|integer|min:0',
            'vacancies'          => 'required|integer|min:1',
            'mas_grade_step_id'  => 'required|exists:mas_grade_steps,id',
            'mrf_type'           => 'nullable|string|max:255',
            'job_description'    => 'nullable|string',
            'reason'             => 'required|string',
            'remarks'            => 'nullable|string',
            'date_of_requisition' => 'nullable|date',
        ]);

        MRF::create([
            'requisition_number'  => 'MRF-' . date('Y') . '-' . str_pad(MRF::count() + 1, 4, '0', STR_PAD_LEFT),
            'date_of_requisition' => $request->date_of_requisition ?? now(),
            'mas_function_id'     => $request->mas_function_id,
            'mas_department_id'   => $request->mas_department_id,
            'mas_section_id'      => $request->mas_section_id,
            'designation_id'      => $request->designation_id,
            'employment_type_id'  => $request->employment_type_id,
            'location'            => $request->location,
            'experience'          => $request->experience,
            'vacancies'           => $request->vacancies,
            'mas_grade_step_id'   => $request->mas_grade_step_id,
            'mrf_type'            => $request->mrf_type,
            'job_description'     => $request->job_description,
            'reason'              => $request->reason,
            'remarks'             => $request->remarks,
            'requested_by'        => Auth::id(),
            'status'              => 'hod_submitted',
        ]);

        return redirect('mrf/lists')
            ->with('msg_success', 'MRF submitted successfully');
    }
    /**
     * Display the specified MRF (View only)
     */
    public function show($id)
    {
        $mrf = MRF::with([
            'function',
            'department',
            'section',
            'designation',
            'gradeStep',
            'requester',
            'approver'
        ])->findOrFail($id);

        return view('mrf.show', compact('mrf'));
    }

    /**
     * View / Edit MRF
     */
    public function edit($id)
    {
        $user = auth()->user();
        $userCompanyId = $user->empJob?->mas_company_id;

        // Get the MRF with relationships
        $mrf = MRF::with([
            'function',
            'department',
            'section',
            'designation',
            'employmentType',
            'gradeStep',
            'requester',
            'approver',
        ])->findOrFail($id);

        // Check if user is authorized to edit this MRF
        // Option 1: Check if MRF belongs to user's company
        if ($userCompanyId && $mrf->function && $mrf->function->mas_company_id != $userCompanyId) {
            abort(403, 'You are not authorized to edit this MRF.');
        }

        // Option 2: Or check if user created this MRF
        if ($mrf->requested_by != $user->id && !$user->hasRole(['Adminstrator', 'Human Resource'])) {
            abort(403, 'You can only edit MRFs that you created.');
        }

        // Get functions for dropdown (filtered by company)
        $functions = FunctionModel::where('mas_company_id', $userCompanyId)
            ->where(function ($query) use ($mrf) {
                // Include the current function even if it doesn't meet the strength criteria
                $query->whereColumn('current_strength', '<', 'approved_strength')
                    ->orWhere('id', $mrf->mas_function_id);
            })
            ->get();

        // Get other dropdown data
        $departments = MasDepartment::all();
        $sections = MasSection::all();
        $employmentTypes = MasEmploymentType::all();
        $grades = MasGrade::all();
        $gradeSteps = MasGradeStep::all();

        // Get designations based on selected function (or all if not specified)
        $designations = MasDesignation::when($mrf->mas_function_id, function ($query) use ($mrf) {
            $query->where('mas_function_id', $mrf->mas_function_id);
        })->get();

        return view('mrf.edit', [
            'mrf' => $mrf,
            'nextRequisitionNumber' => $mrf->requisition_number, // Use existing number
            'functions' => $functions,
            'departments' => $departments,
            'sections' => $sections,
            'designations' => $designations,
            'employmentTypes' => $employmentTypes,
            'grades' => $grades,
            'gradeSteps' => $gradeSteps,
            'company' => MasCompany::find($userCompanyId),
            'user' => $user
        ]);
    }
    /**
     * Update MRF
     */
    public function update(Request $request, $id)
    {
        $mrf = MRF::findOrFail($id);
        $userRoles = auth()->user()->roles->pluck('name')->toArray();

        // ==========================
        // STATUS + ROLE GUARD
        // ==========================
        if (
            ($mrf->status === 'hod_submitted' && !in_array('Human Resource', $userRoles)) ||
            ($mrf->status === 'hr_approved' && !in_array('Administrator', $userRoles))
        ) {
            return back()->with('msg_error', 'You are not authorized to process this MRF.');
        }

        // ==========================
        // HOD update (reason only)
        // ==========================
        if ($request->has('reason') && !$request->has('status')) {

            $request->validate([
                'reason' => 'required|string',
            ]);

            $mrf->update([
                'reason' => $request->reason,
            ]);

            return redirect('mrf/lists')
                ->with('msg_success', 'MRF updated successfully');
        }

        // ==========================
        // ROLE-BASED VALIDATION
        // ==========================
        if (in_array('Human Resource', $userRoles)) {
            $request->validate([
                'status'  => 'required|in:hr_approved,rejected',
                'remarks' => 'nullable|string',
            ]);
        }

        if (in_array('Administrator', $userRoles)) {
            $request->validate([
                'status'  => 'required|in:admin_approved,rejected',
                'remarks' => 'nullable|string',
            ]);
        }

        // ==========================
        // UPDATE MRF
        // ==========================
        $mrf->update([
            'status'      => $request->status,
            'remarks'     => $request->remarks,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // ==========================
        // NOTIFICATIONS (HR APPROVAL)
        // ==========================
        if ($request->status === 'hr_approved') {

            $admins = User::whereHas('roles', function ($q) {
                $q->where('name', 'Administrator');
            })->get();

            foreach ($admins as $admin) {
                SystemNotification::create([
                    'mas_employee_id' => $admin->id,
                    'title'           => 'MRF Pending Review',
                    'message'         => 'An MRF (' . $mrf->requisition_number . ') has been approved by HR and requires your review.',
                    'created_by'      => auth()->id(),
                ]);
            }

            SystemNotification::create([
                'mas_employee_id' => $mrf->requested_by,
                'title'           => 'MRF Approved by HR',
                'message'         => 'Your MRF (' . $mrf->requisition_number . ') has been approved by HR and forwarded to the Administrator for final review.',
                'created_by'      => auth()->id(),
            ]);
        }

        // ==========================
        // NOTIFICATIONS (ADMIN APPROVAL)
        // ==========================
        if ($request->status === 'admin_approved') {

            $hrs = User::whereHas('roles', function ($q) {
                $q->where('name', 'Human Resource');
            })->get();

            foreach ($hrs as $hr) {
                SystemNotification::create([
                    'mas_employee_id' => $hr->id,
                    'title'           => 'MRF Approved by Administrator',
                    'message'         => 'MRF (' . $mrf->requisition_number . ') has been approved by the Administrator. You may now proceed with recruitment.',
                    'created_by'      => auth()->id(),
                ]);
            }

            SystemNotification::create([
                'mas_employee_id' => $mrf->requested_by,
                'title'           => 'MRF Fully Approved',
                'message'         => 'Your MRF (' . $mrf->requisition_number . ') has been approved by the Administrator. Recruitment process may now proceed.',
                'created_by'      => auth()->id(),
            ]);
        }

        return redirect('mrf/lists')
            ->with('msg_success', 'MRF status updated successfully');
    }

    /**
     * Remove MRF
     */
    public function destroy($id)
    {
        try {
            MRF::findOrFail($id)->delete();

            return back()->with(
                'msg_success',
                'MRF deleted successfully'
            );
        } catch (\Exception $e) {
            return back()->with(
                'msg_error',
                'MRF cannot be deleted.'
            );
        }
    }
}
