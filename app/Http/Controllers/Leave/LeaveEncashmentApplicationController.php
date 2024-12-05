<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use App\Models\PaySlipDetailView;
use App\Models\LeaveEncashmentApplication;
use App\Models\LeaveEncashmentType;
use Carbon\Carbon;
use App\Services\ApprovalService;
use App\Models\MasLeavePolicy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveEncashmentApplicationController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:leave/leave-encashment,view')->only('index', 'show');
    //     $this->middleware('permission:leave/leave-encashment,create')->only('create');
    //     $this->middleware('permission:leave/leave-encashment,edit')->only('update');
    //     $this->middleware('permission:leave/leave-encashment,delete')->only('destroy');
    // }
    protected $rules = [
       'encashment_amount' => 'required|numeric',
        'leave_applied_for_encashment' => 'required',
    ];
    
    protected $messages = [
       'leave_applied_for_encashment.required' => 'Leave applied for encashment is required.',
        'encashment_amount.required' => 'Encashment amount is required.',
        
    ];

   
    
    public function index(Request $request){
        $privileges = $request->instance();
        $leaveEncashment = LeaveEncashmentApplication::where('mas_employee_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return view('leave.leave.encash_index',compact('privileges','leaveEncashment'));
    }
    public function create()
    {      
        $earnedLeave = EmployeeLeave::where('mas_employee_id', auth()->user()->id)
        ->where('mas_leave_type_id', EARNED_LEAVE)
        ->whereYear('created_at', Carbon::now()->year);
        
        $openingBalance = $earnedLeave->value('opening_balance');
        $currentEntitlement = $earnedLeave->value('current_entitlement');
        $closingBalance = $earnedLeave->value('closing_balance');
        $leaveAppiled = $earnedLeave->value('leaves_availed');

        $earnedLeaveBalance = ($openingBalance + $currentEntitlement)-$leaveAppiled;

        $applyFlag = false;

        $leavePolicy = MasLeavePolicy::with('yearEnd')->where('mas_leave_type_id', EARNED_LEAVE)->first();
        if (!$leavePolicy) {
            return back()->withInput()->with('Leave policy not found.', 404);
            
        }
        $requiredBalance = $leavePolicy->yearEnd->min_balance_required;
        $earnedLeaveEncahsment = $leavePolicy->yearEnd->min_encashment_per_year;
        $message="";
        if($earnedLeaveBalance < $requiredBalance ){
            $message="Insufficient Balance";
        }
        $applicationExists = LeaveEncashmentApplication::where('mas_employee_id', auth()->user()->id)
    ->whereYear('created_at', Carbon::now()->year)
    ->exists();
        
        if ($applicationExists) {
            // Application exists
            $message = "An application already exists for this year.";
        }

        if($earnedLeaveBalance >= $requiredBalance && !$applicationExists){
            $applyFlag = true;
        }
    
        $encashedAmount = PaySlipDetailView::where('mas_employee_id', auth()->user()->id)->whereForMonth(Carbon::now()->subMonth()->format('Y-m-01'))->value('basic_pay'); 
        return view('leave.leave.leave-encashment', compact('earnedLeaveBalance', 'encashedAmount', 'requiredBalance', 'earnedLeaveEncahsment', 'applyFlag', 'message'));
    }

    public function store(Request $request){
        $leaveEncashment = new  LeaveEncashmentApplication();
    
        $conditionFields = approvalHeadConditionFields(LEAVE_ENCASHMENT_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $encashmentType = LeaveEncashmentType::first()?->id;
        $approverByHierarchy = $approvalService->getApproverByHierarchy($encashmentType, \App\Models\LeaveEncashmentType::class, $conditionFields ?? []);
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

        return redirect()->route('leave.encashment-history')->with('msg_success', 'Leave Encashment application created successfully!');
    }
    }

