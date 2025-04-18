<?php

namespace App\Http\Controllers\Api\v1\Asset;

use Illuminate\Support\Facades\Log;
use App\Traits\JsonResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApprovalService;
use App\Services\ApplicationHistoriesService;
use App\Mail\ApplicationForwardedMail;
use App\Models\AssetCommissionApplication;
use App\Models\MasCommissionTypes;
use App\Models\RequisitionApplication;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\MasDzongkhag;
use App\Models\MasSite;


class CommissionApplicationApiController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected $rules = [
        'commission_date' => 'required',
        'grn' => 'required',
        // 'attachments' => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
        'details.*.asset_no' => 'required',
        'details.*.date_placed_in_service' => 'required',
        'details.*.site' => 'required',
     ];

     protected $messages = [
        'details.*.asset_no.required' => 'The asset no is required for each detail item.',
        'details.*.date_placed_in_service.required' => 'The date placed in service is required for each detail item.',
        'details.*.site.required' => 'The site is required for each detail item.',
    ];

    private $attachmentPath = 'images/asset-comm/';


    public function index(Request $request)
    {
        try {
            $commissions = AssetCommissionApplication::filter($request)->orderByDesc('created_at')->orderBy('created_at', 'desc')->get();
            $mappedModel = 'App\Models\AssetCommissionApplication';
            $commissions->map(function ($commission) use ($mappedModel) {
                return loadApplicationDetails($commission, $mappedModel);
            });
            return $this->successResponse($commissions, 'Commission applications retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }


    public function create()
    {
        try{
        // only fixed asset can be commissioned
        $requisitions = RequisitionApplication::with(['details.grnItem'])->where('type_id', FIXED_ASSET)
            ->where('is_received', 1)
            ->where('created_by', auth()->user()->id)
            ->get();

        $empDetails = empDetails(auth()->user()->id);
        $employee = auth()->user()->name;
        $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();
        $department = $empDetails->empJob->department;
        return $this->successResponse(['requisitions' => $requisitions,'empname'=>$employee, 'empdepartment'=>$department, 'dzongkhags'=>$dzongkhags], 'Commission applications retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }


    public function store(Request $request)
    {
        // Add file validation only if a file is uploaded
        if ($request->hasFile('attachments')) {
            $this->rules['attachments'] = 'array'; // Ensure attachments is an array
            $this->rules['attachments.*'] = 'file|mimes:pdf,jpg,png,docx|max:2048';
        }

        $validator = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        $attachments = []; // Initialize an array to store uploaded file names

        if ($request->file('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachment = uploadImageToDirectory($file, $this->attachmentPath);
                // Add the uploaded file name to the attachments array
                $attachments[] = $attachment;
            }
        }

        $conditionFields = approvalHeadConditionFields(COMMISSION_APPVL_HEAD, $request); // fetching condition field for particular aprroval head

        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy(COMMISSION_TYPE, \App\Models\MasCommissionTypes::class, $conditionFields ?? []);

        // $reqType = MasRequisitionType::where('id', $request->type_id)->first();
        $comType = MasCommissionTypes::where('id', COMMISSION_TYPE)->first();
        $lastTransaction = AssetCommissionApplication::latest('id')->first();
        $transactionNo = generateTransactionNumber1($comType, $lastTransaction, 'transaction_no');

        try {
            DB::beginTransaction();
            $commissionApplication = AssetCommissionApplication::create([
                'type_id' => COMMISSION_TYPE,
                'transaction_no' => $transactionNo,
                'transaction_date' => $request->commission_date,
                'requisition_detail_id' => $request->grn,
                'file' => !empty($attachments) ? json_encode($attachments) : null,
                'status' => $approverByHierarchy['application_status'],
            ]);


            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $commissionApplication->details()->create([
                        'received_serial_id' => $detail['asset_no'],
                        'date_placed_in_service' => $detail['date_placed_in_service'],
                        'dzongkhag_id' => $detail['dzongkhag'],
                        'office_id' => $detail['office'] ?? null,
                        'site_id' => $detail['site'],
                        'remark' => $detail['remark'],
                    ]);
                }
            }

            // Create a history record
            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($commissionApplication->histories(), $approverByHierarchy, $request->remarks);

            // Fetch the approver dynamically using ApprovalService and sent email to notify approver accordingly
            DB::commit();
            if(isset($approverByHierarchy['approver_details'])){
                $emailContent = 'has submitted a asset commission request and is awaiting your approval.';
                $emailSubject = 'Asset Commission Application';
                try{
                    Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
                }catch(\Exception $e){
                    \Log::error('Error sending mail for Commission Application: ' . $e->getMessage());
                }
          }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

        return $this->successResponse($commissionApplication, 'Commission application created successfully');

    }


    public function show(string $id)
    {
        try{
        $commission = AssetCommissionApplication::with('details', 'details.site:id,name',
                'details.dzongkhag:id,dzongkhag',
                'details.office:id,name','details.receivedSerial:id,asset_serial_no,asset_description,amount')->findOrFail($id);
        $approvalDetail = getApplicationLogs(\App\Models\AssetCommissionApplication::class, $commission->id);
        return $this->successResponse($commission, 'Commission application retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

}
