<?php

namespace App\Http\Controllers\Api\Advance;

use App\Http\Controllers\AjaxRequestController;
use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasAdvanceTypes;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use App\Models\BudgetCode;
use App\Models\MasDzongkhag;
use App\Services\ApprovalService;
use App\Models\TravelAuthorizationApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\ApplicationForwardedMail;
use App\Models\AdvanceDetail;
use Illuminate\Support\Facades\Mail;
use App\Services\ApplicationHistoriesService;
use App\Models\User;

class AdvanceLoanApplicationApiController extends Controller
{
    use JsonResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private $attachmentPath = 'images/advance/';

    protected $rules = [
        'advance_no' => 'required',
        'date' => 'required|date',
        'advance_type' => 'required',
        'travel_authorization_no' => 'required_if:advance_type,' . DSA_ADVANCE,
        'advance_settlement_date' => 'required_if:advance_type,' . ADVANCE_TO_STAFF,
        'item_type' => 'required_if:advance_type,' . GADGET_EMI,
        'amount' => '|required_if:advance_type,' . DSA_ADVANCE . '|required_if:advance_type,' . ADVANCE_TO_STAFF .
            '|required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . IMPREST_ADVANCE . '|required_if:advance_type,' . SALARY_ADVANCE . '|required_if:advance_type,' . SIFA_LOAN . '|numeric|min:0',
        'interest_rate' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN . '|numeric|min:0',
        'total_amount' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN . '|numeric|min:0',
        'no_of_emi' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN,
        'monthly_emi_amount' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN,
        'deduction_from_period' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN . '|date_format:Y-m',
    ];

    protected $messages = [
        'travel_authorization_no.required_if' => 'Travel authorization no is required for the selected advance type.',
        'advance_settlement_date.required_if' => 'Advance settlement date no is required for the selected advance type.',
        'item_type.required_if' => 'Item type is required for the selected gadget EMI.',
        'amount.required_if' => 'Amount is required for the selected advance type.',
        'interest_rate.required_if' => 'Interest rate is required for the selected advance type.',
        'total_amount.required_if' => 'Total amount is required for the selected advance type.',
        'no_of_emi.required_if' => 'Number of EMIs is required for the selected advance type.',
        'monthly_emi_amount.required_if' => 'Monthly EMI amount is required for the selected advance type.',
        'deduction_from_period.required_if' => 'Deduction from period is required for the selected advance type and must be a valid date.',
    ];

    private $travelModes = [
        1 => 'Bike',
        2 => 'Bus',
        3 => 'Car',
        4 => 'Flight',
        5 => 'Train'
    ];

    public function index(Request $request)
    {

        try {
            $applications = AdvanceApplication::with('advanceType', 'advance_approved_by:id,name')->createdBy()->orderBy('created_at', 'desc')->get();

            return $this->successResponse($applications, 'Advance applications retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve applications'.$e->getMessage(), 500);
        }
    }

    public function create()
    {   try {
        $advanceTypes = MasAdvanceTypes::whereStatus(1)->all();
        $budgetCodes = BudgetCode::get();
        $dzongkhags = MasDzongkhag::get();
        $excludedTravelAuthorizationIds = AdvanceApplication::pluck('travel_authorization_id')->filter()->toArray(); //filter is used incase travel_authorization_id column is null to remove those
        $travelAuthorizations = TravelAuthorizationApplication::where('created_by', loggedInUser())
                                    ->where('status', 3)
                                    ->when(!empty($excludedTravelAuthorizationIds), function ($query) use ($excludedTravelAuthorizationIds) {
                                        $query->whereNotIn('id', $excludedTravelAuthorizationIds);
                                    })
                                    ->get(['id', 'travel_authorization_no']); // Always fetch after conditions are applied
        return response()->json(['advanceTypes' => $advanceTypes, 'dzongkhags' => $dzongkhags, 'budgetCodes' => $budgetCodes, 'excludedTravelAuthorizationIds' => $excludedTravelAuthorizationIds, 'travelAuthorizations' => $travelAuthorizations]);

    }catch (\Exception $e) {
        return $this->errorResponse($e->getMessage(), 500);
    }
 }

    public function store(Request $request)
    {   try{

        //define validation rules when advance to staff is applied for detail section
        $advanceApplication = new AdvanceApplication();
        $validator = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        $conditionFields = approvalHeadConditionFields(ADVANCE_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->advance_type, \App\Models\MasAdvanceTypes::class, $conditionFields ?? []);
        $date= formatDate(request('date'));
        $attachment = "";
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachment = uploadImageToDirectory($file, $this->attachmentPath);
        }
        try {
            DB::beginTransaction();
            $advanceApplication->advance_no = $request->advance_no;
            $advanceApplication->date = $date;
            $advanceApplication->date = $date;
            $advanceApplication->advance_settlement_date = formatDate($request->advance_settlement_date) ?? null;
            $advanceApplication->type_id = $request->advance_type;
            $advanceApplication->mas_employee_id = $request->employee ?? null; // only required if user applies on behalf of someone
            $advanceApplication->travel_authorization_id = $request->travel_authorization_no ?? null;

            $advanceApplication->amount = $request->amount ?? null;
            $advanceApplication->attachment = $attachment ?? null; // Store attachment path
            $advanceApplication->total_amount = $request->total_amount ?? null;
            $advanceApplication->no_of_emi = $request->advance_type === DSA_ADVANCE ? 1 : $request->no_of_emi ?? null;
            $advanceApplication->monthly_emi_amount = $request->monthly_emi_amount ?? null;
            $advanceApplication->deduction_from_period = $request->deduction_from_period ?? null;
            $advanceApplication->item_type = $request->item_type ?? null;
            $advanceApplication->remark = $request->remark ?? null;
            $advanceApplication->interest_rate = $request->interest_rate ?? null;
            $advanceApplication->status = $approverByHierarchy['application_status'];

            $advanceApplication->save();

            if ($request->advance_type == ADVANCE_TO_STAFF && $request->details) {
                $this->saveAdvanceDetails($request->details, $advanceApplication->id);


            }

            // Create a corresponding history record for advance
            // Create a history record
            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($advanceApplication->histories(), $approverByHierarchy, $request->remarks);


            DB::commit();

            if (isset($approverByHierarchy['approver_details'])) {
                $emailContent = 'has submitted a advance request and is awaiting your approval for advance no ' . $request->advance_no . 'amounting to Nu.' . $request->amount . '/-.';
                $emailSubject = 'Advance Application';
                Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
            }
        } catch (\Exception $e) {
            DB::rollBack();
           return $this->errorResponse($e->getMessage(), 500);
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }
        return $this->successResponse($advanceApplication, 'Advance application created successfully');
    }catch (\Exception $e) {
        return $this->errorResponse($e->getMessage(), 500);
    }
    }




    public function show($id)
    {
        try {
        $advance = AdvanceApplication::with('advanceType')->findOrFail($id);
        return $this->successResponse($advance, 'Advance application retrieved successfully');
        }catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $advance = AdvanceApplication::findOrFail($id);  // Fetch the advance application
        // dd($advance);
        // if (!$advance) {dd("a");
        //     // Return a custom error response if the advance is not found
        //     return response()->json([
        //         'error' => 'Advance application not found',
        //         'message' => 'No advance application exists with the given ID.'
        //     ], 404);  // 404 is the HTTP status code for Not Found
        // }
        $advanceTypes = MasAdvanceTypes::all();  // Fetch advance types

        // Return data as JSON response
        return response()->json([
            'advance' => $advance,
            'advanceTypes' => $advanceTypes
        ]);
    }

    public function update(Request $request, $id)
    {
        try{
        $advanceApplication = AdvanceApplication::findOrFail($id);

        // Initialize the $validatedData array
        $validatedData = [];

        if ($request->hasFile('attachment')) {
            // Check if there is an existing file and delete it
            if ($advanceApplication->attachment) {
                $existingFilePath = public_path($advanceApplication->attachment);
                if (file_exists($existingFilePath) && is_file($existingFilePath)) {
                    unlink($existingFilePath); // Delete the existing file
                }
            }

            // Upload the new file and save the path
            $file = $request->file('attachment');
            $path = uploadImageToDirectory($file, $this->attachmentPath); // Ensure this function generates a relative path
            $validatedData['attachment'] = $path; // Save the relative path
        } else {
            // If no new file is uploaded, keep the existing attachment path
            $validatedData['attachment'] = $advanceApplication->attachment; // Maintain the existing path
        }

        try {
            // Start a database transaction to ensure atomicity
            DB::beginTransaction();
            $advanceApplication->advance_no = $request->advance_no;
            $advanceApplication->date = formatDate($request->date);
            // $advanceApplication->date = $request->date;
            $advanceApplication->advance_settlement_date = formatDate($request->advance_settlement_date) ?? null;
            $advanceApplication->type_id = $request->advance_type;
            $advanceApplication->mas_employee_id = $request->employee ?? null; // only required if user applies on behalf of someone
            $advanceApplication->travel_authorization_id = $request->travel_authorization_no ?? null; // only required if user applies on behalf of someone

            $advanceApplication->amount = $request->amount ?? null;
            $advanceApplication->attachment = $attachment ?? null; // Store attachment path
            $advanceApplication->total_amount = $request->total_amount ?? null;
            $advanceApplication->no_of_emi = $request->no_of_emi ?? null;
            $advanceApplication->monthly_emi_amount = $request->monthly_emi_amount ?? null;
            $advanceApplication->deduction_from_period = $request->deduction_from_period ?? null;
            $advanceApplication->item_type = $request->item_type ?? null;
            $advanceApplication->remark = $request->remark ?? null;
            $advanceApplication->interest_rate = $request->interest_rate ?? null;
            $advanceApplication->status = $advanceApplication->status;

            $advanceApplication->save();
            if ($request->advance_type == ADVANCE_TO_STAFF && $request->details) {
                $this->saveAdvanceDetails($request->details, $advanceApplication->id);
            }

            // Optionally create a history record for the advance application
            // $advanceApplication->histories()->create([
            //     'level' => 'Test Level', // This could be dynamic, depending on the use case
            //     'status' => $advanceApplication->status,
            //     'remarks' => $request->remark ?? $advanceApplication->remark,
            //     'created_by' => loggedInUser(),  // Assuming loggedInUser() fetches the current user's ID
            //     'updated_by' => loggedInUser(),
            // ]);

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();

            // Handle the error by returning back with error message
            return $this ->errorResponse($e->getMessage(), 500);
        }

     return $this->successResponse($advanceApplication, 'Advance application updated successfully');
    }catch (\Exception $e) {
        return $this->errorResponse($e->getMessage(), 500);
    }


    }




    public function destroy($id)
    {
        try {
            AdvanceApplication::findOrFail($id)->delete();

            return $this->successResponse($id, 'Advance Applicaton has been deleted');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function saveAdvanceDetails($advanceDetails, $advanceApplicationId)
    {
        // Track existing IDs to avoid deleting records that are updated
        $existingIds = [];

        foreach ($advanceDetails as $detail) {
            // Check if the detail has an 'id' (indicating an existing record)
            if (isset($detail['id']) && !empty($detail['id'])) {
                // Update the existing record
                $existingDetail = AdvanceDetail::find($detail['id']);
                if ($existingDetail) {
                    $existingDetail->update([
                        'budget_code_id' => $detail['budget_code'],
                        'from_date' => formatDate($detail['from_date']),
                        'to_date' => formatDate($detail['to_date']),
                        'dzongkhag_id' => $detail['dzongkhag'],
                        'site_location' => $detail['site_location'],
                        'amount_required' => $detail['amount_required'],
                        'purpose' => $detail['purpose'],
                    ]);

                    $existingIds[] = $existingDetail->id; // Track updated record IDs
                }
            } else {
                // Insert new record
                $newDetail = AdvanceDetail::create([
                    'advance_application_id' => $advanceApplicationId,
                    'budget_code_id' => $detail['budget_code'],
                    'from_date' => formatDate($detail['from_date']),
                    'to_date' => formatDate($detail['to_date']),
                    'dzongkhag_id' => $detail['dzongkhag'],
                    'site_location' => $detail['site_location'],
                    'amount_required' => $detail['amount_required'],
                    'purpose' => $detail['purpose'],
                ]);

                if ($newDetail) {
                    $existingIds[] = $newDetail->id; // Track newly inserted record IDs
                }
            }
        }

        // Optionally delete records not in the current request
        AdvanceDetail::where('advance_application_id', $advanceApplicationId)
            ->whereNotIn('id', $existingIds)
            ->delete();
    }
}
