<?php

namespace App\Http\Controllers\Api\Leave;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use App\Models\PaySlipDetailView;
use App\Models\LeaveEncashmentApplication;
use App\Models\LeaveEncashmentType;
use Carbon\Carbon;
use App\Services\ApprovalService;
use App\Models\MasLeavePolicy;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\ApplicationHistoriesService;
use App\Models\MasEmployeeJob;
use App\Models\MasPaySlabDetails;

class LeaveEncashmentApplicationController extends Controller
{
    use JsonResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');

    }
    protected $rules = [
       'encashment_amount' => 'required|numeric',
        'leave_applied_for_encashment' => 'required',
    ];

    protected $messages = [
       'leave_applied_for_encashment.required' => 'Leave applied for encashment is required.',
        'encashment_amount.required' => 'Encashment amount is required.',

    ];



    public function index(Request $request){
        try{$privileges = $request->instance();
        $leaveEncashment = LeaveEncashmentApplication::where('mas_employee_id', auth()->user()->id)->with( 'histories:id,application_id,action_performed_by,application_type,status',  'histories.actionPerformer:id,name,username')->orderBy('created_at', 'desc')->get();
        return $this->successResponse($leaveEncashment, 'Leave encashment applications retrieved successfully');
    }catch (\Exception $e) {
          return $this->errorResponse($e->getMessage());
      }
    }
    public function create()
    {
        try{
            $earnedLeave = EmployeeLeave::where('mas_employee_id', auth()->user()->id)
            ->where('mas_leave_type_id', EARNED_LEAVE)
            ->whereYear('created_at', Carbon::now()->year);

            $openingBalance = $earnedLeave->value('opening_balance');
            $currentEntitlement = $earnedLeave->value('current_entitlement');
            $closingBalance = $earnedLeave->value('closing_balance');
            $leaveAppiled = $earnedLeave->value('leaves_availed');

            $earnedLeaveBalance = ($openingBalance + $currentEntitlement)-$leaveAppiled;

            $applyFlag = false;

            $leavePolicy = MasLeavePolicy::with('yearEnd')->where('type_id', EARNED_LEAVE)->first();
            if (!$leavePolicy) {
                return back()->withInput()->with('Leave policy not found.', 404);

            }
            $requiredBalance = $leavePolicy->yearEnd->min_balance_required;
            $earnedLeaveEncahsment = $leavePolicy->yearEnd->min_encashment_per_year;
            $message="";
            if($earnedLeaveBalance < $requiredBalance ){
                $message="You do not have enough earned leave balance to encash.";
            }
            $applicationExists = LeaveEncashmentApplication::where('mas_employee_id', auth()->user()->id)
        ->whereYear('created_at', Carbon::now()->year)
        ->whereNot('status', -1)
        ->exists();

            if ($applicationExists) {
                // Application exists
                $message = "An application already exists for this year.";
            }

            if($earnedLeaveBalance >= $requiredBalance && !$applicationExists){
                $applyFlag = true;
            }

            $encashedAmount = MasEmployeeJob::where('mas_employee_id', auth()->user()->id)->value('basic_pay');
        return response()->json(["earnedLeaveBalance"=>$earnedLeaveBalance, "requiredBalance"=>$requiredBalance, "earnedLeaveEncahsment" =>$earnedLeaveEncahsment, "applyFlag" =>$applyFlag, "message"=>$message, "encashedAmount" =>$encashedAmount]);
        }catch (\Exception $e) {
        return $this->errorResponse($e->getMessage());
    }
    }

    public function store(Request $request){
        try{
        $validator = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        $leaveEncashment = new  LeaveEncashmentApplication();

        $conditionFields = approvalHeadConditionFields(LEAVE_ENCASHMENT_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();

        //if encashment amount / basic pay (monthly income) > 167400 calculate tax amount seperately
        if((int)$request->encashment_amount > 167400){
            $tax_amount = (((int)$request->encashment_amount - 125000) * 0.3) + 20208;
        }else{
            $tax_amount = MasPaySlabDetails::whereRaw('? BETWEEN pay_from AND pay_to', [$request->encashment_amount])->where('mas_pay_slab_id', 1)->value('amount');
        }

        if (!$tax_amount ) {
             return response()->json(['message' => 'Tax amount has not been intialized, contact admin!']);
        }
        $encashmentType = LeaveEncashmentType::first()?->id;
        $approverByHierarchy = $approvalService->getApproverByHierarchy($encashmentType, \App\Models\LeaveEncashmentType::class, $conditionFields ?? []);
        try {
            DB::beginTransaction();

            $leaveEncashment->mas_employee_id = Auth::id();
            $leaveEncashment->type_id = 1;
            $leaveEncashment->tax_amount=$tax_amount;
            $leaveEncashment->leave_applied_for_encashment = $request->leave_applied_for_encashment;
            $leaveEncashment->encashment_amount = $request->encashment_amount;
            $leaveEncashment->created_by = Auth::id();
            $leaveEncashment->status = 1;
            $leaveEncashment->save();

            // Create a history record
            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($leaveEncashment->histories(), $approverByHierarchy, $request->remarks);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
            }
        return $this->successResponse($leaveEncashment, 'Leave Encashment application created successfully!');
        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

    }
    }


