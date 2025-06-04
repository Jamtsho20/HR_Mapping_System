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
use App\Models\RequisitionDetail;
use App\Models\AssetCommissionDetail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\MasDzongkhag;
use App\Models\MasSite;
use Carbon\Carbon;

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
        'details.*.asset_id' => 'required',
        'details.*.date_placed_in_service' => 'required',
        'details.*.site' => 'required',
     ];

     protected $messages = [
        'details.*.asset_id.required' => 'The asset no is required for each detail item.',
        'details.*.date_placed_in_service.required' => 'The date placed in service is required for each detail item.',
        'details.*.site.required' => 'The site is required for each detail item.',
    ];

    private $attachmentPath = 'images/asset-comm/';


    public function index(Request $request)
    {
        try {
            $commissions = AssetCommissionApplication::filter($request)->orderByDesc('created_at')->get();
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
        $requisitions = RequisitionApplication::with([
                'details' => function ($q) {
                    $q->whereHas('serials', function ($query) {
                        $query->where('is_received', 1)
                            ->where('is_commissioned', '<>', 1);
                    });
                },
                'details.grnItem',
                'details.serials' => function ($query) {
                    $query->where('is_received', 1)
                        ->where('is_commissioned', '<>', 1);
                    }
                ])
                ->where('type_id', FIXED_ASSET)
                ->where('is_received', 1)
                ->where('created_by', auth()->user()->id)
                ->whereHas('details.serials', function ($query) {
                    $query->where('is_received', 1)
                        ->where('is_commissioned', '<>', 1);
                })
                ->get();

        $response = $requisitions->map(function ($requisition) {
            return [
                'id' => $requisition->id,
                'transaction_no' => $requisition->transaction_no,
                'details' => $requisition->details->map(function ($detail) {
                    return ['id' => $detail->id];
                })->values(),
            ];
        });
        $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();
            // $simplifiedRequisitions = $requisitions->flatMap(function ($req) {
            //     $transactionNo = $req->transaction_no;
            //     return collect($req->details)->map(function ($detail) use ($transactionNo) {
            //         return [
            //             'req_no' => $transactionNo,
            //             'id' => $detail->id,
            //             'grn_item' => $detail->grnItem ?? null,
            //         ];
            //     });
            // })->values();

            return $this->successResponse([

                'grns' => $response, 'dzongkhags'=>$dzongkhags], 'Commission applications retrieved successfully');
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
                 'requisition_id' => $request->grn,
                'file' => !empty($attachments) ? json_encode($attachments) : null,
                'status' => $approverByHierarchy['application_status'],
            ]);


            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $commissionApplication->details()->create([
                        'received_serial_id' => $detail['asset_id'],
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
        $commission = AssetCommissionApplication::with(
            'requisitionDetail:id,grn_item_detail_id',
            'requisitionDetail.grnItemDetail:item_id,id', // assuming item_id is foreign key
            'requisitionDetail.grnItemDetail.item:id,item_no,item_description,uom,item_group_id',
            'details', 'details.site:id,name',
                'details.dzongkhag:id,dzongkhag',
                'details.office:id,name','details.receivedSerial:id,asset_serial_no,asset_description,amount')->findOrFail($id);
        $approvalDetail = getApplicationLogs(\App\Models\AssetCommissionApplication::class, $commission->id);
        return $this->successResponse($commission, 'Commission application retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
    }



    public function getAssetNoByGrnId($grnId)
    {
        //only those serial whose status is not -1
        $existingSerials = AssetCommissionDetail::whereHas('assetCommission', function ($query) {
            $query->where('status', '!=', -1);
        })->pluck('received_serial_id')->toArray();
        // dd($existingSerials);
        try {
            $assetNosQuery = RequisitionDetail::where('id', $grnId)
                ->whereHas('serials', function ($query) {
                    $query->where('is_commissioned', 0);
                })
                ->with('grnItemDetail:id,item_id', 'grnItemDetail.item:id,item_no,item_description,uom')
                ->with(['serials' => function ($query) use ($existingSerials) {
                        $query->where('is_commissioned', 0);
                        if(!empty($existingSerials)){
                            $query->whereNotIn('id', $existingSerials);
                        }
                        $query->selectRaw("id, requisition_detail_id, asset_serial_no, asset_description, amount");
                    },
                ])->selectRaw("id, requisition_id, grn_item_id, grn_item_detail_id");
                // dd($assetNosQuery->toSql(), $assetNosQuery->getBindings());

            $assetNos = $assetNosQuery->first();

            if (!$assetNos) {
                return $this->errorResponse('No asset numbers found for the provided GRN number.');
            }

            return $this->successResponse( $assetNos);
        } catch(\Exception $e) {
            return $this->errorResponse('Something went wrong while fetching asset numbers'. $e->getMessage());
        }
    }


    public function indexCommissionApproval(Request $request)
     {
         try {
             $currentUser = auth()->user();
             $name = $request->input('name');
             $requisitionTypes = MasCommissionTypes::get(['id', 'name']);
             $statuses = [];
             $applicationType = 'App\Models\AssetCommissionApplication'; // Default application type
             $tab = null;

             // Define conditions for filtering based on status
             switch ($request->input('status')) {
                 case 'pending':
                     $statuses = [1, 2]; // Pending statuses
                     $tab = 'history';
                     break;
                 case 'approved':
                     $statuses = [2, 3]; // Approved statuses
                     $tab = 'audit_logs';
                     break;
                 case 'rejected':
                     $statuses = [-1]; // Rejected status
                     $tab = 'audit_logs'; // Adjust tab if needed
                     break;
                 default:
                     return response()->json(['error' => 'Invalid status parameter'], 400);
             }

             // Build the query dynamically
             $commissionApplications = AssetCommissionApplication::with([
                'employee:id,name,username,contact_number',
                     'employee.empjob' => function ($query) {
                         $query->select('mas_employee_id', 'mas_department_id', 'mas_section_id', 'mas_designation_id');
                     },
                     'employee.empjob.designation:id,name',
                 'employee.empjob.department:id,name',
                 'employee.empjob.section:id,name',
                 'histories:id,application_id,action_performed_by',

             ])
             ->when($tab === 'history', function ($query) use ($currentUser, $applicationType) {
                 $query->whereHas('histories', function ($query) use ($currentUser, $applicationType) {
                     $query->where('approver_emp_id', $currentUser->id)
                           ->where('application_type', $applicationType);
                 });
             })
             ->when($tab === 'audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                 $query->whereHas('audit_logs', function ($query) use ($currentUser, $applicationType, $statuses) {
                     $query->where('application_type', $applicationType)
                           ->where('action_performed_by', $currentUser->id);
                 })
                 ->whereYear('created_at', Carbon::now()->year); // Add condition for audit_logs
             })
             ->when($name, function ($query) use ($name) {
                 $query->whereHas('employee', function ($query) use ($name) {
                     $query->where('name', 'like', "%{$name}%"); // Filter by name
                 });
             })
             ->whereIn('status', $statuses) // Filter based on statuses
            //  ->filter($request, false)
             ->orderBy('created_at')
             ->get();

             $mappedModel = AssetCommissionApplication::class;
             $commissionApplications = $commissionApplications->map(function ($commission) use ($mappedModel) {
                 return loadApplicationDetails($commission, $mappedModel);
             });
             return response()->json([
                 'success' => true,
                 'message' => 'Commission approval applications fetched successfully',
                 'data' => $commissionApplications,
             ]);

         } catch (\Exception $e) {
             return $this->errorResponse($e->getMessage());
         }


     }

}
