<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\MasSite;
use App\Models\MasTransferType;
use App\Models\ReceivedSerial;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ApprovalService;
use App\Services\ApplicationHistoriesService;
use App\Mail\ApplicationForwardedMail;
use App\Models\AssetTransferApplication;
use App\Models\AssetTransferDetail;
use App\Models\MasAssets;
use App\Models\RequisitionDetail;
use App\Models\MasSiteSupervisor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AssetTransferApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset/asset-transfer,view')->only('index', 'show');
        $this->middleware('permission:asset/assets,view')->only('myAssetIndex');
        $this->middleware('permission:asset/asset-transfer,create')->only('store');
        $this->middleware('permission:asset/asset-transfer,edit')->only('update');
        $this->middleware('permission:asset/asset-transfer,delete')->only('destroy');
    }

    protected $rules = [];

    public function rules()
        {
            $rules = [
                'type_id' => 'required|in:1,2',
                'transfer_date' => 'required|date',
                'reason_of_transfer' => 'required',
                'details.*.asset_no' => 'required',
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|mimes:pdf,jpg,png,docx|max:2048',
            ];

            if ($this->type_id == 1) {
                $rules['from_employee_id'] = 'required';
                $rules['to_employee_id'] = 'required|different:from_employee';
            }

            if ($this->type_id == 2) {
                $rules['from_site_id'] = 'required';
                $rules['to_site_id'] = 'required|different:from_site';
            }

            return $rules;
        }

        protected $messages = [
            'type_id.required' => 'Please select a transfer type.',
            'type_id.in' => 'Invalid transfer type selected.',

            'transfer_date.required' => 'The transfer date is required.',
            'transfer_date.date' => 'The transfer date must be a valid date.',

            'reason_of_transfer.required' => 'Please provide a reason for the transfer.',

            'details.*.asset_no.required' => 'Please select an asset number for each row.',

            'from_employee.required' => 'The sender employee is required for employee-based transfers.',
            'to_employee.required' => 'The recipient employee is required for employee-based transfers.',
            'to_employee.different' => 'The recipient must be different from the sender.',

            'from_site.required' => 'The source site is required for site-based transfers.',
            'to_site.required' => 'The destination site is required for site-based transfers.',
            'to_site.different' => 'The destination site must be different from the source site.',
        ];

        private $attachmentPath = 'images/asset-transfer/';
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $assetTransfer = AssetTransferApplication::filter($request)->where('created_by', auth()->user()->id)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();
        $transferTypes = MasTransferType::get(['id', 'name']);

        $toBeTransferedToUserAsset = AssetTransferApplication::where('type_id', 1)->where('status', 3)->where('received_acknowledged', 0)->whereHas('details.asset', function ($query) {
            $query->where('current_employee_id', auth()->user()->id);
        })->get();
        return view('asset.asset-transfer.index',compact('privileges', 'assetTransfer', 'transferTypes', 'toBeTransferedToUserAsset'));
    }


    public function myAssetIndex(Request $request){
        $privileges = $request->instance();
        $transferTypes = MasTransferType::get(['id', 'name']);
        $toBeTransferedToUserAsset = AssetTransferApplication::where('type_id', 2)->where('status', 3)->where('received_acknowledged', 0)->whereHas('details.asset', function ($query) {
            $query->where('current_employee_id', auth()->user()->id);
        })->get();
        $transferedToUser = AssetTransferApplication::where('received_acknowledged', 1)->whereHas('details.asset', function ($query) {
            $query->where('current_employee_id', auth()->user()->id);
        })->filter($request)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();

        $dzongkhag_ids = MasSiteSupervisor::where('employee_id', auth()->user()->id)->pluck('dzongkhag_id');
        $sites = MasSite::whereIn('dzongkhag_id', $dzongkhag_ids)->get();
        $site_ids = $sites->pluck('id'); // collect all site IDs

        $siteAsset = MasAssets::whereHas('site', function ($query) use ($site_ids) {
            $query->whereIn('current_site_id', $site_ids);
        })->filter($request)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();

        // $userAssets = $assets->concat($assetTransfer);
        return view('asset.asset-transfer.my_asset_index',compact('privileges', 'transferTypes', 'toBeTransferedToUserAsset', 'transferedToUser', 'siteAsset', 'sites'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $types = MasTransferType::whereStatus(1)->get(['id', 'name']);
        $employees = User::whereIsActive(1)->whereNotIn('employee_id', [0, 99999])->get();
        //$fromSites = MasSite::where('site_supervisor',  auth()->user()->id)->get(['id', 'name']);

        // if ($fromSites->isEmpty()) {
            $dzongkhagIds = MasSiteSupervisor::where('employee_id',  auth()->user()->id)
                ->pluck('dzongkhag_id');
           $fromSites = MasSite::where(function ($query) use ($dzongkhagIds, $employees) {
                $query->where(function ($q) use ($dzongkhagIds) {
                    $q->whereNull('site_supervisor')
                    ->whereIn('dzongkhag_id', $dzongkhagIds);
                })
                ->orWhereIn('site_supervisor', $employees->pluck('id'));
            })
            ->get(['id', 'name']);

        // }
        $sites = MasSite::get(['id', 'name']);

        // $assetNos = ReceivedSerial::with('commissionDetail')->where('is_commissioned', 1)->get();
        return view('asset.asset-transfer.create', compact('employees', 'types', 'sites', 'fromSites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('attachments')) {
            $this->rules['attachments'] = 'array'; // Ensure attachments is an array
            $this->rules['attachments.*'] = 'file|mimes:pdf,jpg,png,docx|max:2048';
        }
        $this->validate($request, $this->rules, $this->messages);
        $attachments = []; // Initialize an array to store uploaded file names

        if ($request->file('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachment = uploadImageToDirectory($file, $this->attachmentPath);
                // Add the uploaded file name to the attachments array
                $attachments[] = $attachment;
            }
        }

        $conditionFields = approvalHeadConditionFields(ASSET_TRANSFER_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->type_id, \App\Models\MasTransferType::class, $conditionFields ?? []);

        // $reqType = MasRequisitionType::where('id', $request->type_id)->first();

        $transferType = MasTransferType::where('id', $request->type_id)->first();
        $lastTransaction = AssetTransferApplication::latest('id')->first();
        $transactionNo = generateTransactionNumber1($transferType, $lastTransaction, 'transaction_no');
        $to_employee=null;
        if ($request->to_site) {
            // Try to get direct site_supervisor from mas_sites
            $to_employee = MasSite::where('id', $request->to_site)->pluck('site_supervisor')->first();

            // If not found, fallback to mas_site_supervisors based on dzongkhag
            if (empty($to_employee)) {
                $dzongkhag_id = MasSite::where('id', $request->to_site)->pluck('dzongkhag_id')->first();

                $to_employee = \App\Models\MasSiteSupervisor::where('dzongkhag_id', $dzongkhag_id)
                ->pluck('employee_id')
                    ->first();
                }
        }

        try{
            DB::beginTransaction();
            $application = AssetTransferApplication::create([
                'transaction_no' => $transactionNo,
                'type_id' => $request->type_id,
                'transaction_date' => $request->transfer_date,
                'reason_of_transfer' => $request->reason_of_transfer,
                'status' => $approverByHierarchy['application_status'],
                'from_site_id' => $request->from_site ?? null,
                'to_site_id' => $request->to_site ?? null,
                'from_employee_id' => $request->from_employee ?? Auth::user()->id,
                'to_employee_id' => $to_employee ?? $request->to_employee,
                'attachments' => !empty($attachments) ? json_encode($attachments) : []
            ]);

            foreach ($request->details as $detail) {

                $mas_assets = MasAssets::where('id', $detail['asset_no'])->first();

                if($mas_assets){
                    $mas_assets->is_transfered = 1;
                    $mas_assets->save();
                }

                $application->details()->create([
                    'asset_transfer_id' => $application->id,
                    'mas_asset_id' => $detail['asset_no']
                ]);
            }

            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($application->histories(), $approverByHierarchy, $request->remarks);

            // Fetch the approver dynamically using ApprovalService and sent email to notify approver accordingly
            DB::commit();
            if(isset($approverByHierarchy['approver_details'])){
                $emailContent = 'has submitted a asset transfer request and is awaiting your approval.';
                $emailSubject = 'Asset Transfer Application';
                try{
                    Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
                }catch(\Exception $e){
                    \Log::error('Error sending mail for Asset Transfer Application:' . $e->getMessage());
                }
          }

        }catch(\Exception $e){
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
        return redirect('asset/asset-transfer')->with('msg_success', 'Asset transfer successfully!');

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
     $transfer = AssetTransferApplication::with('details')->findOrFail($id);
     $approvalDetail = getApplicationLogs(\App\Models\AssetTransferApplication::class, $transfer->id);

     return view('asset.asset-transfer.show', compact('transfer', 'approvalDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
