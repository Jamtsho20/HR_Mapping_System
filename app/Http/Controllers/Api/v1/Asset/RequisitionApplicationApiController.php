<?php

namespace App\Http\Controllers\Api\v1\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use App\Models\ApplicationHistory;
use App\Models\RequisitionApplication;
use App\Models\RequisitionDetail;
use App\Services\ApprovalService;
use App\Services\ApplicationHistoriesService;
use App\Models\MasRequisitionType;
use App\Models\MasSite;
use App\Models\MasDzongkhag;
use App\Models\MasItem;
use App\Models\MasStore;
use App\Models\MasGrnItem;
use App\Models\MasGrnItemDetail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationForwardedMail;
use App\Models\AssetUnitOfMeasurement;
use App\Http\Controllers\Api\SAP\ApiController;

class RequisitionApplicationApiController extends Controller
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
            // 'requisition_no' => 'required|unique:requisition_applications,requisition_no',
            'type_id' => 'required',
            'requisition_date' => 'required',
            'need_by_date' => 'required',

            'details.*.quantity_required' => 'required',
            'details.*.dzongkhag' => 'required',
            // 'details.*.office_name' => 'required',
            'details.*.site_name' => 'required',
         ];

         protected $messages = [
            'details.*.item_id.required' => 'The item id is required for each detail item.',
            'details.*.store.required' => 'The store is required for each detail item.',
            // 'details.*.stock_status.required' => 'The stock status is required for each detail item.',
            'details.*.quantity_required.required' => 'The quantity is required for each detail item.',
            'details.*.dzongkhag.required' => 'The dzongkhag is required for each detail item.',
            // 'details.*.office_name.required' => 'The office name is required for each detail item.',
            'details.*.site_name.required' => 'The site name is required for each detail item.',
        ];


        public function index(Request $request)
        {
            try{
                $requisitions = RequisitionApplication::filter($request)->orderBy('created_at')->get();
                $mappedModel = 'App\Models\RequisitionApplication';
                $requisitions->map(function ($requisition) use ($mappedModel) {
                    return loadApplicationDetails($requisition, $mappedModel);
                });
                return $this->successResponse($requisitions, 'Requisition applications retrieved successfully');
            }catch(\Exception $e){
                return $this->errorResponse($e->getMessage());
            }

        }

        public function create()
        {
            try{
            $reqTypes = MasRequisitionType::where('status', 1)->orderBy('id', 'desc')->select('id', 'name')->get();
            // $grnNos = MasGrnItem::whereStatus(1)
            // ->select('id', 'grn_no')
            // ->get();
            $items = MasItem::where('is_fixed_asset', 0)->select('id','item_no', 'item_description', 'uom')->get();
           $stores = MasStore::where('status', 1)->select('id', 'name', 'code')->get();
           $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();
           return $this->successResponse(['reqTypes' => $reqTypes,  'dzongkhags' => $dzongkhags, 'items' => $items, 'stores' => $stores], 'Requisition applications retrieved successfully');
            }catch(\Exception $e){
                return $this->errorResponse($e->getMessage());
            }
        }


        public function store(Request $request)
     {
        $requisition = new RequisitionApplication();
        $validator = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        $conditionFields = approvalHeadConditionFields(REQUISITION_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->type_id, \App\Models\MasRequisitionType::class, $conditionFields ?? []);

        $reqType = MasRequisitionType::where('id', $request->type_id)->first();

        $lastTransaction = RequisitionApplication::latest('id')->first();

        $transactionNo = generateTransactionNumber1($reqType, $lastTransaction, 'transaction_no');


        try {
            DB::beginTransaction();
            $requisition->transaction_no = $transactionNo;
            $requisition->type_id = $request->type_id;
            $requisition->transaction_date = $request->requisition_date;
            $requisition->need_by_date = $request->need_by_date;

            $requisition->status = $approverByHierarchy['application_status'];
            $requisition->save();

            if($request->details){
                $this->saveDetails($request->details, $requisition->id, $request->type_id);
            }

            // Create a corresponding history record for advance
            // Create a history record
            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($requisition->histories(), $approverByHierarchy, $request->remarks);


            DB::commit();

            if (isset($approverByHierarchy['approver_details'])) {
                $emailContent = 'has submitted a requisition request and is awaiting your approval for requisition no ' . $request->requisition_no;
                $emailSubject = 'Requisition Application';

                try{
                    Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
                }catch(\Exception $e){
                    \Log::error('Error sending mail for requisition application: ' . $e->getMessage());
                }
           }
           return $this->successResponse($requisition, 'Requisition application created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

     }


     public function show(string $id)
     {
        try{
            $requisition = RequisitionApplication::with([
                'details.serials',
                'details.site:id,name',
                'details.dzongkhag:id,dzongkhag',
                'details.office:id,name',
                'details.item:id,item_no,item_description,uom',
                'details.store:id,name,code',
                'details.grnItem:id,grn_no',          // eager load grnItem relation
                'details.grnItemDetail.item:id,item_description,uom',   // nested item from grnItemDetail
            'details.grnItemDetail.store:id,name,code'    // eager load grnItemDetail relation
            ])->find($id);
        return $this->successResponse($requisition, 'Requisition application retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
     }
     private function saveDetails($details, $requisitionId, $typeId)
     {
         $existingIds = [];

         try{
         foreach ($details as $detail) {
             $grn_item = null;
             $grn_item_id = null;
             $grn_item_detail_id = null;
             $newStock = null;
             $uomId = AssetUnitofMeasurement::where('name', $detail['uom'])->firstOrFail()->id;
             if($uomId == null){
                 return $this->errorResponse('Selected uom not found, contact admin.');
                }

                // Handle GRN-based requisition (type_id == 1)
                if (!empty($detail['grn_item_id'])) {
                    $grnData = $detail['grn_item_id'];

                    if ($grnData) {
                        $grn_item = MasGrnItemDetail::where('grn_id', $grnData)
                        ->where('store_id', $detail['store'])
                        ->where('item_id', $detail['item_id'])
                        ->first();

                        $conversionFlag = isset($detail['conversion']) && strtolower($detail['conversion']) !== 'false';

                        if ($grn_item) {
                            $newStock=0;
                            if($conversionFlag == false){
                                $newStock = max(0, $grn_item->quantity - $detail['quantity_required']);
                                $grn_item->update(['quantity' => $newStock]);
                            }
                            $grn_item_id = $grn_item->grn_id;
                            $grn_item_detail_id = $grn_item->id;
                        }else{
                            return $this->errorResponse('GRN item not found, contact admin.');
                        }
                    }
                }

                $data = [
                    'requisition_id' => $requisitionId,
                    'status' => 1,
                    'requested_quantity' => $detail['quantity_required'],
                    'dzongkhag_id' => $detail['dzongkhag'],
                    'site_id' => $detail['site_name'],
                    'uom' => $uomId,
                    'remark' => $detail['remark'],
                ];

                if ($typeId == 1) {
                    $data['grn_item_id'] = $grn_item_id;
                 $data['grn_item_detail_id'] = $grn_item_detail_id;
                 $data['current_stock'] = $newStock;

                } else {
                 $data['item_id'] = $detail['item_id'];
                 $data['store_id'] = $detail['store'];
                 $data['current_stock'] = $detail['stock_status'] - $detail['quantity_required'];
             }


             if (!empty($detail['id'])) {
                 $existingDetail = RequisitionDetail::find($detail['id']);
                 if ($existingDetail) {
                     $existingDetail->update($data);
                     $existingIds[] = $existingDetail->id;
                    }
                } else {
                    $newDetail = RequisitionDetail::create($data);
                    if ($newDetail) {
                        $existingIds[] = $newDetail->id;
                    }
                }
            }

            // Cleanup: delete details that are no longer present
            RequisitionDetail::where('requisition_id', $requisitionId)
            ->whereNotIn('id', $existingIds)
            ->delete();


     }catch(\Exception $e){
         return $this->errorResponse($e->getMessage());
     }
    }

     public function indexRequisitionApproval(Request $request)
     {
         try {
             $currentUser = auth()->user();
             $name = $request->input('name');
             $requisitionTypes = MasRequisitionType::get(['id', 'name']);
             $statuses = [];
             $applicationType = 'App\Models\RequisitionApplication'; // Default application type
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
             $requisitionApplications = RequisitionApplication::with([
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

             $mappedModel = RequisitionApplication::class;
             $requisitionApplications = $requisitionApplications->map(function ($requisition) use ($mappedModel) {
                 return loadApplicationDetails($requisition, $mappedModel);
             });
             return response()->json([
                 'success' => true,
                 'message' => 'Requisition approval applications fetched successfully',
                 'data' => $requisitionApplications,
             ]);

         } catch (\Exception $e) {
             return $this->errorResponse($e->getMessage());
         }


     }


     public function receive(string $id)
     {
        try{
            $requisition = RequisitionApplication::with('details.grnItem:id,grn_no','details.grnItemDetail.item:id,item_no,item_description','details.serials')->find($id);
            if($requisition->type_id == 1){
                 foreach ($requisition->details as $detail) {
                $itemCode = $detail->grnItemDetail->item?->item_no ?? '';
                foreach ($detail->serials as $serial) {
                    $serial->asset_serial_no = $itemCode . '-' . $serial->asset_serial_no;
                }
            }
            }
            return $this->successResponse($requisition, 'Requisition application retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage());
        }

     }

}

