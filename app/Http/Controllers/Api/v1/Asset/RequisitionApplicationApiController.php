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
            'details.*.item_description' => 'required',
            'details.*.store' => 'required',
            // 'details.*.stock_status' => 'required',
            'details.*.quantity_required' => 'required',
            'details.*.dzongkhag' => 'required',
            // 'details.*.office_name' => 'required',
            'details.*.site_name' => 'required',
         ];

         protected $messages = [
            'details.*.item_description.required' => 'The item description is required for each detail item.',
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
            $grnNos = MasGrnItem::whereStatus(1)
            ->select('id', 'grn_no')
            ->get();
            $items = MasItem::where('is_fixed_asset', 0)->select('id','item_no', 'item_description', 'uom')->get();
           $stores = MasStore::where('status', 1)->select('id', 'name', 'code')->get();
           $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();
           return $this->successResponse(['reqTypes' => $reqTypes, 'grnNos' => $grnNos,  'dzongkhags' => $dzongkhags, 'items' => $items, 'stores' => $stores], 'Leave applications retrieved successfully');
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
                    \Log::error('Error sending mail for DSA Claim/Settlement' . $e->getMessage());
                }
           }

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }
        return $this->successResponse($requisition, 'Requisition application created successfully');
     }


     public function show(string $id)
     {
        try{
            $requisition = RequisitionApplication::with([
                'details.serials',
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

         foreach ($details as $detail) {
             $grn_item = null;
             $grn_item_id = null;
             $grn_item_detail_id = null;

             // Handle GRN-based requisition (type_id == 1)
             if (!empty($detail['grn_id'])) {
                 $grnId = $detail['grn_id'];

                 if ($grnId) {
                     $grn_item = MasGrnItemDetail::where('grn_id', $grnId)
                         ->where('store_id', $detail['store'])
                         ->where('item_id', $detail['item_description'])
                         ->first();

                     if ($grn_item) {
                         $newStock = max(0, $grn_item->quantity - $detail['quantity_required']);
                         $grn_item->update(['quantity' => $newStock]);

                         $grn_item_id = $grn_item->grn_id;
                         $grn_item_detail_id = $grn_item->id;
                     }
                 }
             }

             $data = [
                 'requisition_id' => $requisitionId,
                 'status' => 1,
                 'requested_quantity' => $detail['quantity_required'],
                 'dzongkhag_id' => $detail['dzongkhag'],
                 'site_id' => $detail['site_name'],
                 'remark' => $detail['remark'],
             ];

             if ($typeId == 1) {
                 $data['grn_item_id'] = $grn_item_id;
                 $data['grn_item_detail_id'] = $grn_item_detail_id;
             } else {
                 $data['item_id'] = $detail['item_description'];
                 $data['store_id'] = $detail['store'];
                 $data['current_stock'] = $detail['stock_status'];
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
     }

}

