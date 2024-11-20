<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use App\Models\PaySlipDetailView;
use App\Models\LeaveEncashmentApplication;
use App\Models\LeaveEncashmentType;
use Carbon\Carbon;
use App\Services\ApprovalService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveEncashmentApplicationController extends Controller
{

    protected $rules = [
       'encashment_amount' => 'required|numeric',
        'leave_applied_for_encashment' => 'required',
    ];
    
    protected $messages = [
       'leave_applied_for_encashment.required' => 'Leave applied for encashment is required.',
        'encashment_amount.required' => 'Encashment amount is required.',
        
    ];
    public function index()
    {      
        $earnedLeave = EmployeeLeave::where('mas_employee_id', auth()->user()->id)
        ->where('mas_leave_type_id', 2)
        ->whereYear('created_at', Carbon::now()->year);
        // ->value('closing_balance');
        
        $openingBalance = $earnedLeave->value('opening_balance');
        $currentEntitlement = $earnedLeave->value('current_entitlement');
        $closingBalance = $earnedLeave->value('closing_balance');
        $leaveAppiled = $earnedLeave->value('leaves_availed');

        $earnedLeaveBalance = ($openingBalance + $currentEntitlement)-$leaveAppiled;
        // dd($closingBalance , $openingBalance, $currentEntitlement);
        // dd($leaveAppiled);

        $applyFlag = false;

      
        $requiredBalance = 37;
        $earnedLeaveEncahsment = 30;

        if($earnedLeaveBalance > $requiredBalance){
            $applyFlag = true;
        }

    
        $encashedAmount = PaySlipDetailView::where('mas_employee_id', auth()->user()->id)->whereForMonth(Carbon::now()->subMonth()->format('Y-m-01'))->value('basic_pay'); 
        return view('leave.leave.leave-encashment', compact('earnedLeaveBalance', 'encashedAmount', 'requiredBalance', 'earnedLeaveEncahsment', 'applyFlag'));
    }

    public function store(Request $request){
        $leaveEncashment = new  LeaveEncashmentApplication();
        // dd($request);
        // $this->validate($request, $this->rules, $this->messages);
        $conditionFields = approvalHeadConditionFields(LEAVE_ENCASHMENT_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $encashmentType = LeaveEncashmentType::first()?->id;
        $approverByHierarchy = $approvalService->getApproverByHierarchy($encashmentType, \App\Models\LeaveEncashmentType::class, $conditionFields ?? []);
        dd($approverByHierarchy);
        try {
            DB::beginTransaction();
            
            $leaveEncashment->mas_employee_id = Auth::id();
            $leaveEncashment->leave_applied_for_encashment = $request->leave_applied_for_encashment;
            $leaveEncashment->encashment_amount = $request->encashment_amount;
            $leaveEncashment->created_by = Auth::id();
            $leaveEncashment->status = 1;
            $leaveEncashment->save();
            
            $leaveEncashment->histories()->create([
                'approval_option' => $approverByHierarchy['approval_option'],
                'hierarchy_id' => $approverByHierarchy['hierarchy_id'] ?? null,
                'level_id' => $approverByHierarchy['next_level']->id ?? null,
                'approver_role_id' => $approverByHierarchy['approver_details']['approver_role_id'] ?? null,
                'approver_emp_id' => $approverByHierarchy['approver_details']['user_with_approving_role']->id ?? null,
                'level_sequence' => $approverByHierarchy['next_level']->sequence ?? null,
                'status' => $approverByHierarchy['application_status'],
                'remarks' => $request->remarks ?? null,
                'action_performed_by' => loggedInUser(),
            ]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

        return redirect()->route('leave-apply.index')->with('msg_success', 'Leave Encashment application created successfully!');
    }
    }

