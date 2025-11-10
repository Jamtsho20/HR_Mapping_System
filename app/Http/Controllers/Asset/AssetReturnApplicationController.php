<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationForwardedMail;
use App\Models\AssetReturnApplication;
use App\Models\MasDzongkhag;
use App\Models\MasReturnType;
use App\Models\MasStore;
use App\Models\MasTransferType;
use App\Models\ReceivedSerial;
use App\Services\ApplicationHistoriesService;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use App\Models\MasSiteSupervisor;
use App\Models\MasSite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


class AssetReturnApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $rules = [
        'transaction_date' => 'required|date',
        'attachment' => 'nullable|array',
        'attachment.*' => 'file|mimes:pdf,jpg,png,docx|max:2048',

        'details.*.mas_asset_id' => 'required',
        'details.*.dzongkhag_id' => 'required',
        'details.*.store_id' => 'required',
        'details.*.condition_code' => 'required|in:1,2,3,4',
    ];
    protected $messages = [


    ];

    private $attachmentPath = 'images/asset-return/';

    public function __construct()
    {
        $this->middleware('permission:asset/asset-return,view')->only('index');
        $this->middleware('permission:asset/asset-return,create')->only('store');
        $this->middleware('permission:asset/asset-return,edit')->only('update');
        $this->middleware('permission:asset/asset-return,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $assetReturns = AssetReturnApplication::orderByDesc('created_at')->orderBy('created_at', 'desc')->paginate(config('global.pagination'))->withQueryString();
        return view('asset.asset-return.index', compact('privileges','assetReturns'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = MasReturnType::whereStatus(1)->get(['id', 'name']);
        $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();
        $stores = MasStore::select('id', 'name')->get();

        $employeeId = Auth::user()->id;
        $dzongkhagIds = MasSiteSupervisor::where('employee_id',  auth()->user()->id)
                ->pluck('dzongkhag_id');
        $fromSites = MasSite::where(function ($query) use ($dzongkhagIds, $employeeId) {
                $query->where(function ($q) use ($dzongkhagIds) {
                    $q->whereNull('site_supervisor')
                    ->whereIn('dzongkhag_id', $dzongkhagIds);
                })
                ->orWhereIn('site_supervisor', [$employeeId]);
            })
            ->get(['id', 'name']);
        // $assetNos = ReceivedSerial::where('is_commissioned', 1)->where('is_transfered', '<>', 1)->get(['id', 'asset_serial_no']);

        return view('asset.asset-return.create', compact('types', 'dzongkhags', 'stores', 'fromSites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // dd($request->file('attachments'));

        // Add file validation only if a file is uploaded
        if ($request->hasFile('attachments')) {
            $this->rules['attachments'] = 'array';
            $this->rules['attachments.*'] = 'file|mimes:pdf,jpg,png,docx|max:2048';
        }

        $this->validate($request, $this->rules, $this->messages);

        $attachments = [];
        if ($request->file('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = uploadImageToDirectory($file, $this->attachmentPath);
            }
        }


        $conditionFields = approvalHeadConditionFields(ASSET_RETURN_APPVL_HEAD, $request); // fetching condition field for particular aprroval head

        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy(ASSET_RETURN_TYPE, \App\Models\MasReturnType::class, $conditionFields ?? []);
        $returnType = MasReturnType::where('id', $request->type_id)->first();
        $lastTransaction = AssetReturnApplication::latest('id')->first();
        $transactionNo = generateTransactionNumber1($returnType, $lastTransaction, 'transaction_no');

        try {
            DB::beginTransaction();
            $assetReturnApplication = AssetReturnApplication::create([
                'type_id'         => $request->type_id,
                'transaction_no'  => $transactionNo,
                'transaction_date'=> $request->transaction_date,
                'attachment'       => !empty($attachments) ? json_encode($attachments) : null,
                'status'          => $approverByHierarchy['application_status'],
                'created_by'      => auth()->id(),
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {

                    $mas_assets = MasAssets::where('id', $detail['mas_asset_id'])->first();

                    if($mas_assets){
                        $mas_assets->is_returned = 1;
                        $mas_assets->save();
                    }
                    $assetReturnApplication->details()->create([
                        'asset_return_id'    => $assetReturnApplication->id,
                        'mas_asset_id' => $detail['mas_asset_id'],
                        'store_id'           => $detail['store_id'],
                        'condition_code'     => $detail['condition_code'] ?? 1,
                        'remark'             => $detail['remark'] ?? null,
                    ]);
                }
            }

            // Save application history
            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($assetReturnApplication->histories(), $approverByHierarchy, $request->remarks);

            DB::commit();

            // Send email to approver
            if (isset($approverByHierarchy['approver_details'])) {
                $emailContent = 'has submitted an asset return request and is awaiting your approval for transaction no ' . $transactionNo;
                $emailSubject = 'Asset Return Application';

                try {
                    Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])
                        ->send(new ApplicationForwardedMail(
                            auth()->user()->id,
                            $approverByHierarchy['approver_details']['user_with_approving_role']->id,
                            $emailContent,
                            $emailSubject
                        ));
                } catch (\Exception $e) {
                    \Log::error('Error sending mail for Asset Return: ' . $e->getMessage());
                }
            }

            return redirect('asset/asset-return')->with('msg_success', 'Asset return has been submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
    }




    public function show(string $id)
    {
        $return = AssetReturnApplication::with('details.store')->findOrFail($id);
        $approvalDetail = getApplicationLogs(\App\Models\AssetReturnApplication::class, $return->id);
        $dzongkhags = MasDzongkhag::select('id', 'dzongkhag')->get();
        $stores = MasStore::select('id', 'name')->get();

        return view('asset.asset-return.show', compact('return', 'approvalDetail', 'dzongkhags', 'stores'));
    }
    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
