<?php

namespace App\Http\Controllers\Payroll;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PaySlip;
use App\Models\MasPayHead;
use Illuminate\Http\Request;
use App\Models\PaySlipDetail;
use Illuminate\Validation\Rule;
use App\Services\PayrollService;
use App\Models\PaySlipDetailView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class PaySlipController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->middleware('permission:payroll/pay-slips,view')->only('index');
        $this->middleware('permission:payroll/pay-slips,create')->only('store');
        $this->middleware('permission:payroll/pay-slips,edit')->only('update', 'processPaySlip', 'verifyPaySlip', 'approvePaySlip');
        $this->middleware('permission:payroll/pay-slips,delete')->only('destroy');

        $this->payrollService = $payrollService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $paySlips = PaySlip::filter($request)->orderBy('for_month')->paginate(30);

        return view('payroll.pay-slips.index', compact('paySlips', 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll.pay-slips.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'for_month' => [
                'required',
                'date_format:Y-m',
                function ($attribute, $value, $fail) {
                    $startOfMonth = Carbon::parse($value)->startOfMonth()->format('Y-m-d');

                    if (PaySlip::where('for_month', 'like', $startOfMonth)->exists()) {
                        $fail('A payslip for this month already exists.');
                    }
                },
            ],
        ]);

        try {
            $paySlip = new PaySlip();
            $paySlip->for_month = $request->for_month;
            $paySlip->save();

            return redirect()->route('pay-slips.index')->with('msg_success', 'Payslip for the month of ' . Carbon::parse($paySlip->for_month)->format('F Y') . ' has been created successfully');

        } catch (\Exception $e) {
            Log::error('Error creating payslip: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('msg_error', 'An error occurred while creating the payslip. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $employees = User::filter($request)->select(['id', 'name', 'employee_id'])->get();
        $payHeads = MasPayHead::select(['id', 'name', 'code'])->orderBy('name')->get();

        $paySlip = PaySlip::findOrFail($id);
        $month = $paySlip->for_month;

        $count = PaySlipDetailView::where('for_month', $month)->count();
        if (!$count) {
            $this->payrollService->populateReportTable($paySlip);
        }
        $records = PaySlipDetailView::where('for_month', $month)->paginate(30);

        return view('payroll.pay-slips.show', compact('paySlip', 'records', 'employees', 'payHeads'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paySlip = PaySlip::findOrFail($id);

        return view('payroll.pay-slips.show', compact('paySlip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'for_month' => 'required|date_format:Y-m',
        ]);

        try {
            $paySlip = PaySlip::findOrFail($id);
            $paySlip->for_month = $request->for_month;
            $paySlip->save();

            $month = Carbon::parse($paySlip->for_month)->format('F Y');

            return redirect()->route('pay-slips.show', $id)->with('msg_success', 'Payslip for the month of ' . $month . ' has been updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating payslip: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('msg_error', 'An error occurred while updating the payslip. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function processPaySlip($id, Request $request)
    {
        try {
            $status = $request->query('status');
            $paySlip = PaySlip::where('status', 1)->find($id);

            if (!$paySlip) {
                return redirect()->back()->with('msg_error', 'Payslip not found.');
            }

            $result = $this->payrollService->processPaySlip($paySlip);
            if (!$result) {
                Log::error('Error processing payslip: ' . $result);

                return redirect()->back()->with('msg_error', 'An error occurred while processing the payslip.');
            }

            $this->payrollService->updateStatus($paySlip, $status);

            $month = Carbon::parse($paySlip->for_month)->format('F Y');

            return redirect()->route('pay-slips.show', $id)->with('msg_success', 'Payslip for the month of ' . $month . ' has been processed successfully.');
        } catch (\Exception $e) {
            Log::error('Error processing payslip: ' . $e->getMessage());

            return redirect()->back()->with('msg_error', 'An unexpected error occurred while processing the payslip.');
        }
    }

    public function verifyPaySlip($id, Request $request)
    {
        try {
            $status = $request->query('status');
            $paySlip = PaySlip::where('status', 2)->find($id);

            $result = $this->payrollService->updateStatus($paySlip, $status);

            if (!$result) {
                Log::error('Error verifying payslip: ' . $result);

                return redirect()->back()->with('msg_error', 'An error occurred while verifying the payslip.');
            }

            $month = Carbon::parse($paySlip->for_month)->format('F Y');
            return redirect()->route('pay-slips.show', $id)->with('msg_success', 'Payslip for the month of ' . $month . ' has been verified successfully.');
        } catch (\Exception $e) {
            Log::error('Error verifying payslip: ' . $e->getMessage());

            return redirect()->back()->with('msg_error', 'An unexpected error occurred while verifying the payslip.');
        }
    }

    public function approvePaySlip($id, Request $request)
    {
        try {
            $status = $request->query('status');
            $paySlip = PaySlip::where('status', 3)->find($id);

            $result = $this->payrollService->updateStatus($paySlip, $status);

            if (!$result) {
                Log::error('Error approving payslip: ' . $result);

                return redirect()->back()->with('msg_error', 'An error occurred while approving the payslip.');
            }

            $month = Carbon::parse($paySlip->for_month)->format('F Y');
            return redirect()->route('pay-slips.show', $id)->with('msg_success', 'Payslip for the month of ' . $month . ' has been approved successfully.');
        } catch (\Exception $e) {
            Log::error('Error approving payslip: ' . $e->getMessage());

            return redirect()->back()->with('msg_error', 'An unexpected error occurred while approving the payslip.');
        }
    }

    public function mailPaySlip($id)
    {
        try {
            $paySlip = PaySlip::where('status', 4)->find($id);

            $result = $this->payrollService->generateAndMailPaySlips($paySlip);

            if (!$result) {
                Log::error('Error mailing payslip: ' . $result);

                return redirect()->back()->with('msg_error', 'An error occurred while mailing the payslip.');
            }

            $month = Carbon::parse($paySlip->for_month)->format('F Y');
            return redirect()->route('pay-slips.show', $id)->with('msg_success', 'Payslip for the month of ' . $month . ' has been mailed successfully.');
        } catch (\Exception $e) {
            Log::error('Error mailing payslip: ' . $e->getMessage());

            return redirect()->back()->with('msg_error', 'An unexpected error occurred while mailing the payslip.');
        }
    }

    public function addPaySlipDetail(Request $request, $id)
    {
        $request->validate([
            'mas_employee_id' => 'required|exists:mas_employees,id',
            'mas_pay_head_id' => 'required|exists:mas_pay_heads,id',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $employeeId = $request->mas_employee_id;
            $payHeadId = $request->mas_pay_head_id;
            $amount = $request->amount;

            $paySlipDetail = new PaySlipDetail();
            $paySlipDetail->pay_slip_id = $id;
            $paySlipDetail->mas_employee_id = $employeeId;
            $paySlipDetail->mas_pay_head_id = $payHeadId;
            $paySlipDetail->amount = $amount;
            $paySlipDetail->save();

            $paySlip = $paySlipDetail->paySlip;
            $employee = $paySlipDetail->employee;
            $payHead = MasPayHead::whereId($payHeadId)->first();
            $column = str_replace(" ", "_", $payHead->name);

            if (!Schema::hasColumn('pay_slip_detail_views', $column)) {
                Schema::table('pay_slip_detail_views', function (Blueprint $table) use ($column) {
                    $table->decimal($column, 15, 2)->nullable()->before('net_pay');
                });
            }

            $allowance = $payHead->accountHead;
            $paySlipDetailView = PaySlipDetailView::whereMasEmployeeId($employeeId)->whereForMonth($paySlip->for_month)->first();

            PaySlipDetailView::unguard();

            $paySlipDetailView->update([$column => $amount]);

            if ($allowance) { // Allowance
                if ($allowance->type == 1) {
                    $paySlipDetailView->update(['gross_pay' => ($paySlipDetailView->gross_pay + $amount), 'net_pay' => ($paySlipDetailView->net_pay + $amount)]);
                } elseif ($allowance->type == 2) { // Deduction
                    $paySlipDetailView->update(['net_pay' => ($paySlipDetailView->net_pay - $amount)]);
                }
            }
            PaySlipDetailView::reguard();

            DB::commit();

            return redirect()->route('pay-slips.show', $id)->with('msg_success', 'Payhead ' . $payHead->name . ' for ' . $employee->name . ' has been added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding payslip detail: ' . $e->getMessage());

            return redirect()->back()->with('msg_error', 'An unexpected error occurred while adding the payslip detail.');
        }
    }
}
