<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\InterestRate;
use App\Models\LoanEMIDeduction;
use App\Models\MasLoanType;
use App\Models\MasPayHead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoanEMIDeductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payroll/loan-emi-deductions,view')->only('index');
        $this->middleware('permission:payroll/loan-emi-deductions,create')->only('store');
        $this->middleware('permission:payroll/loan-emi-deductions,edit')->only('update');
        $this->middleware('permission:payroll/loan-emi-deductions,delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $loanEMIDeductions = LoanEMIDeduction::filter($request)->orderBy('created_at')
            ->paginate(config('global.pagination'))
            ->withQueryString();


        $employees = User::filter($request)->select(['id', 'name', 'employee_id', 'username', 'title'])->get();
        $payHeads = MasPayHead::whereCalculationMethod(7)->wherePayheadType(2)->whereIn('id', [16, 17, 18, 19, 20, 21, 22, 23, 24])->pluck('name', 'id'); // only loans
        $loanTypes = MasLoanType::pluck('name', 'id');


        return view('payroll.loan-emi-deductions.index', compact('privileges', 'loanEMIDeductions', 'employees', 'payHeads', 'loanTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $loanTypes = MasLoanType::all();
        $payHeads = MasPayHead::whereCalculationMethod(7)->wherePayheadType(2)->whereIn('id', [16, 17, 18, 19, 20, 21, 22, 23, 24])->get(); // only loans
        $employees = User::filter($request)->select(['id', 'name', 'employee_id', 'username', 'title', 'cid_no'])->get();

        return view('payroll.loan-emi-deductions.create', compact('payHeads', 'employees', 'loanTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'mas_pay_head_id' => 'required',
                    'mas_employee_id' => 'required',
                    'start_date' => 'required|date',
                    'amount' => 'required',
                    'loan_type_id' => 'required',
                    'loan_number' => 'required',
                    'recurring_months' => ['required_if:recurring,true', 'integer', 'min:1'],
                ],
                [
                    'mas_pay_head_id.required' => 'Pay Head field is required',
                    'mas_employee_id.required' => 'Employee field is required',
                ]
            );

            // $startDate = Carbon::parse($validated['start_date'])->startOfMonth();
            // $validated['start_date'] = $startDate->format('Y-m-d');

            // if ($request->has('recurring')) {
            //     $recurringMonths = $validated['recurring_months'] ?? 0;
            //     $validated['end_date'] = $startDate->copy()->addMonths($recurringMonths)->format('Y-m-d');
            // } else {
            //     $validated['end_date'] = $startDate->format('Y-m-d');
            // }
            $startDate = Carbon::parse($validated['start_date'])->startOfMonth();
            $validated['start_date'] = $startDate->format('Y-m-d');

            if ($request->has('recurring')) {
                $recurringMonths = $validated['recurring_months'] ?? 0;
                $validated['end_date'] = $startDate->copy()->addMonths($recurringMonths - 1)->format('Y-m-d');
            } else {
                $validated['end_date'] = $startDate->format('Y-m-d');
            }


            $loanEMIDeduction = new LoanEMIDeduction();
            $loanEMIDeduction->mas_pay_head_id = $validated['mas_pay_head_id'];
            $loanEMIDeduction->mas_employee_id = $validated['mas_employee_id'];
            $loanEMIDeduction->start_date = $validated['start_date'];
            $loanEMIDeduction->end_date = $validated['end_date'];
            $loanEMIDeduction->loan_type_id = $validated['loan_type_id'];
            $loanEMIDeduction->loan_number = $validated['loan_number'];
            $loanEMIDeduction->amount = $validated['amount'];
            $loanEMIDeduction->recurring = $request->recurring; // 1 or 0
            $loanEMIDeduction->recurring_months = $validated['recurring_months'];
            $loanEMIDeduction->branch_code = $request->branch_code;
            $loanEMIDeduction->remarks = $request->remarks;
            $loanEMIDeduction->is_paid_off = $request->paid_off_early ?? false;
            $loanEMIDeduction->save();

            return redirect()->route('loan-emi-deductions.index')->with('msg_success', 'Loan EMI Deduction created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating Loan EMI Deduction: ' . $e->getMessage());

            return redirect()->back()->withErrors('An error occurred while creating the Loan EMI Deduction.');
        }
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
        $loanEMIDeduction = LoanEMIDeduction::findOrFail($id);
        $loanTypes = MasLoanType::pluck('name', 'id');
        $employees = User::select('id', 'name', 'employee_id', 'cid_no')
            ->get();

        $employeeId = $loanEMIDeduction->mas_employee_id;

        // Step 1: Get last approved SIFA loan
        $lastApprovedSifaLoan = AdvanceApplication::where('created_by', $employeeId)
            ->where('status', 4)
            ->where('type_id', 7)
            ->orderByDesc('id')
            ->first();
        // Initialize
        $remainingPrincipal = 0;
        $accruedInterest = 0;
        $outstandingAmount = 0;
        $sifaInterestRate = InterestRate::where('advance_type_id', 7)->value('rate');
        $latestRepayment = null;

        if ($lastApprovedSifaLoan) {
            $latestRepayment = DB::table('sifaloanrepayment')
                ->where('advance_application_id', $lastApprovedSifaLoan->id)
                ->orderByDesc('month')
                ->first();

            if ($latestRepayment) {
                $closingBalance = floatval($latestRepayment->closing_balance);
                $remainingPrincipal = $closingBalance;

                // Check if current month's payslip exists
                $currentMonth = now()->startOfMonth()->format('Y-m-d');
                $payslipExists = DB::table('pay_slips')
                    ->where('for_month', $currentMonth)
                    ->exists();

                if ($payslipExists) {
                    // Payslip exists — interest already handled
                    $accruedInterest = 0;
                } else {
                    // Payslip not generated — calculate accrued interest
                    $accrualStartDate = Carbon::parse($latestRepayment->month)->addMonth()->startOfMonth();
                    $today = Carbon::today();
                    $daysElapsed = $accrualStartDate->diffInDays($today) + 1;

                    $daysInYear = Carbon::now()->daysInYear;
                    $accruedInterest = round(($closingBalance * ($sifaInterestRate / 100) * ($daysElapsed / $daysInYear)), 2);
                }

                $outstandingAmount = round($remainingPrincipal + $accruedInterest, 2);
            }
        }

        return view('payroll.loan-emi-deductions.edit', compact(
            'loanEMIDeduction',
            'employees',
            'loanTypes',
            'remainingPrincipal',
            'accruedInterest',
            'outstandingAmount',
            'lastApprovedSifaLoan',
            'sifaInterestRate',
            'latestRepayment'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate(
                [
                    'mas_pay_head_id' => 'required',
                    'mas_employee_id' => 'required',
                    'start_date' => 'required|date',
                    'amount' => 'required|numeric',
                    'loan_type_id' => 'required',
                    'loan_number' => 'required',
                    'recurring_months' => ['required_if:recurring,true', 'integer', 'min:1'],
                ],
                [
                    'mas_pay_head_id.required' => 'Pay Head field is required',
                    'mas_employee_id.required' => 'Employee field is required',
                ]
            );

            $loanEMIDeduction = LoanEMIDeduction::findOrFail($id);

            $startDate = Carbon::parse($validated['start_date'])->startOfMonth();
            $validated['start_date'] = $startDate->format('Y-m-d');

            if ($request->has('recurring')) {
                $recurringMonths = $validated['recurring_months'] ?? 0;
                $validated['end_date'] = $startDate->copy()->addMonths($recurringMonths)->format('Y-m-d');
            } else {
                $validated['end_date'] = $startDate->format('Y-m-d');
            }

            $loanEMIDeduction->mas_pay_head_id = $validated['mas_pay_head_id'];
            $loanEMIDeduction->mas_employee_id = $validated['mas_employee_id'];
            $loanEMIDeduction->start_date = $validated['start_date'];
            $loanEMIDeduction->end_date = $validated['end_date'];
            $loanEMIDeduction->loan_type_id = $validated['loan_type_id'];
            $loanEMIDeduction->loan_number = $validated['loan_number'];
            $loanEMIDeduction->amount = $validated['amount'];
            $loanEMIDeduction->recurring = $request->recurring; // 1 or 0
            $loanEMIDeduction->recurring_months = $validated['recurring_months'];
            $loanEMIDeduction->branch_code = $request->branch_code;
            $loanEMIDeduction->remarks = $request->remarks;
            $loanEMIDeduction->is_paid_off = $request->paid_off_early ?? $loanEMIDeduction->is_paid_off;
            $loanEMIDeduction->save();

            return redirect()->route('loan-emi-deductions.index')->with('msg_success', 'Loan EMI Deduction updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating Loan EMI Deduction: ' . $e->getMessage());

            return redirect()->back()->withErrors('An error occurred while updating the Loan EMI Deduction.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
