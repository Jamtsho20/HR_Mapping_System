<?php

namespace App\Http\Controllers\RetirementBenefitNomination;

use App\Http\Controllers\Controller;
use App\Models\RetirementBenefit;
use App\Models\User;
use Illuminate\Http\Request;

class RetirementBenefitUserListController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:retirement-benefit-nomination/retirement-benefit-list,view')->only('index', 'show');
        $this->middleware('permission:retirement-benefit-nomination/retirement-benefit-list,create')->only('store');
        $this->middleware('permission:retirement-benefit-nomination/retirement-benefit-list,edit')->only('update');
        $this->middleware('permission:retirement-benefit-nomination/retirement-benefit-list,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $query = RetirementBenefit::with('employee.empJob.designation', 'employee.empJob.section', 'employee.empJob.department')
            ->orderByDesc('created_at');

        $employees = User::select('id', 'name', 'employee_id', 'username', 'title')
            ->orderBy('name')
            ->get()
            ->map(function ($emp) {
                $emp->emp_id_name = $emp->employee_id . ' - ' . $emp->name;
                return $emp;
            });
        if ($request->filled('employee')) {
            $query->where('mas_employee_id', $request->employee);
        }

        $retirementBenefits = $query->paginate(50);


        // $retirementBenefits = $query->paginate(50);
        return view('retirement-benefit-nomination/retirement-benefit-list.index', compact('privileges', 'retirementBenefits', 'employees'));
    }

    public function show($id, Request $request)
    {
        $user = auth()->user();
        $nomination = RetirementBenefit::with([
            'employee.empJob.designation',
            'employee.empJob.section',
            'employee.empJob.department',
            'details'
        ])->findOrFail($id);
        $approvalDetail = getApplicationLogs(\App\Models\AdvanceApplication::class, $nomination->id);


        return view('retirement-benefit-nomination/retirement-benefit-list.show',compact('nomination', 'user', 'approvalDetail'));
    }
}
