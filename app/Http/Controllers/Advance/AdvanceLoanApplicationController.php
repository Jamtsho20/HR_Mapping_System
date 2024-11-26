<?php

namespace App\Http\Controllers\Advance;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasAdvanceTypes;
use App\Models\MasLeaveType;
use App\Services\ApprovalService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdvanceLoanApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:advance-loan/apply,view')->only('index', 'show');
        $this->middleware('permission:advance-loan/apply,create')->only('store');
        $this->middleware('permission:advance-loan/apply,edit')->only('update');
        $this->middleware('permission:advance-loan/apply,delete')->only('destroy');
    }

    private $attachmentPath = 'images/advance/';

    protected $rules = [
        'advance_no' => 'required',
        'date' => 'required|date',
        'advance_type' => 'required',
        'mode_of_travel' => 'required_if:advance_type,' . ADVANCE_TO_STAFF . '|required_if:advance_type,' . DSA_ADVANCE,
        'from_location' => 'required_if:advance_type,' . ADVANCE_TO_STAFF . '|required_if:advance_type,' . DSA_ADVANCE,
        'to_location' => 'required_if:advance_type,' . ADVANCE_TO_STAFF . '|required_if:advance_type,' . DSA_ADVANCE,
        'from_date' => 'required_if:advance_type,' . ADVANCE_TO_STAFF . '|required_if:advance_type,' . DSA_ADVANCE . '|date',
        'to_date' => 'required_if:advance_type,' . ADVANCE_TO_STAFF . '|required_if:advance_type,' . DSA_ADVANCE . '|date|after_or_equal:from_date',
        'item_type' => 'required_if:advance_type,' . GADGET_EMI,
        'amount' => 'required_if:advance_type,' . ADVANCE_TO_STAFF . '|required_if:advance_type,' . DSA_ADVANCE . '|required_if:advance_type,' . ELECTRICITY_IMPREST_ADVANCE .
            '|required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . IMPREST_ADVANCE . '|required_if:advance_type,' . SALARY_ADVANCE . '|required_if:advance_type,' . SIFA_LOAN . '|numeric|min:0',
        // 'attachment' => 'required_if:advance_type,' . ADVANCE_TO_STAFF . '|required_if:advance_type,' . DSA_ADVANCE . '|required_if:advance_type,' . ELECTRICITY_IMPREST_ADVANCE .
            // '|required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . IMPREST_ADVANCE . '|required_if:advance_type,' . SALARY_ADVANCE . '|required_if:advance_type,' . SIFA_LOAN . '|mimes:jpg,png,pdf|max:2048',
        'interest_rate' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN . '|numeric|min:0',
        'total_amount' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN . '|numeric|min:0',
        'no_of_emi' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN,
        'monthly_emi_amount' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN,
        'deduction_from_period' => 'required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . SIFA_LOAN . '|date_format:Y-m',
    ];

    protected $messages = [
        'mode_of_travel.required_if' => 'Mode of travel is required for the selected advance type.',
        'from_location.required_if' => 'From location is required for the selected advance type.',
        'to_location.required_if' => 'To location is required for the selected advance type.',
        'from_date.required_if' => 'From date is required for the selected advance type.',
        'to_date.required_if' => 'To date is required for the selected advance type and must be after or equal to the from date.',
        'item_type.required_if' => 'Item type is required for the selected gadget EMI.',
        'amount.required_if' => 'Amount is required for the selected advance type.',
        // 'attachment.required_if' => 'Attachment is required for the selected advance type and must be a valid file (jpg, png, pdf).',
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
        $privileges = $request->instance();
        $advances = AdvanceApplication::with('advanceType')
            ->createdBy() // Apply the createdBy scope
            ->paginate(10);
        foreach ($advances as $advance) {
            $advance->formatted_date = Carbon::parse($advance->date)->format('Y-m-d');
        }

        return view('advance-loan.apply.index', compact('privileges', 'advances'));
    }

    public function create()
    {
        $advanceTypes = MasAdvanceTypes::all();

        return view('advance-loan.apply.create', compact('advanceTypes'));
    }

    public function store(Request $request)
    {
        $advanceApplication = new AdvanceApplication();
        // $this->validate($request, $this->rules, $this->messages);
        $conditionFields = approvalHeadConditionFields(LEAVE_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->leave_type, \App\Models\MasLeaveType::class, $conditionFields ?? []);

        $attachment = "";
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachment = uploadImageToDirectory($file, $this->attachmentPath);
        }
        try {
            DB::beginTransaction();
            $advanceApplication->advance_no = $request->advance_no;
            $advanceApplication->date = $request->date;
            $advanceApplication->advance_type_id = $request->advance_type;
            $advanceApplication->mode_of_travel = $request->mode_of_travel ?? null;
            $advanceApplication->from_location = $request->from_location ?? null;
            $advanceApplication->to_location = $request->to_location ?? null;
            $advanceApplication->from_date = $request->from_date ?? null;
            $advanceApplication->to_date = $request->to_date ?? null;
            $advanceApplication->amount = $request->amount ?? null;
            $advanceApplication->attachment = $attachment; // Store attachment path
            $advanceApplication->total_amount = $request->total_amount ?? null;
            $advanceApplication->no_of_emi = $request->no_of_emi ?? null;
            $advanceApplication->monthly_emi_amount = $request->monthly_emi_amount ?? null;
            $advanceApplication->deduction_from_period = $request->deduction_from_period ?? null;
            $advanceApplication->item_type = $request->item_type ?? null;
            $advanceApplication->remark = $request->remark ?? null;
            $advanceApplication->status = 1;

            $advanceApplication->save();

            // Create a corresponding history record for advance
            // Create a history record
            $advanceApplication->histories()->create([
                'approval_option' => $approverByHierarchy['approval_option'],
                'hierarchy_id' => $approverByHierarchy['hierarchy_id'] ?? null,
                'level_id' => $approverByHierarchy['next_level']->id ?? null,
                'approver_role_id' => $approverByHierarchy['approver_details']['approver_role_id'],
                'approver_emp_id' => $approverByHierarchy['approver_details']['user_with_approving_role']->id,
                'level_sequence' => $approverByHierarchy['next_level']->sequence ?? null,
                'status' => 1,
                'remarks' => $request->remarks,
                'action_performed_by' => loggedInUser(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

        return redirect()->route('apply.index')->with('msg_success', 'Advance application created successfully!');
    }



    public function show($id, Request $request)
    {
        $instance = $request->instance();
        $advance = AdvanceApplication::with('advanceType')->findOrFail($id);
        $advanceTypes = MasAdvanceTypes::all();
        $advance->mode_of_travel_name = $this->travelModes[$advance->mode_of_travel] ?? 'Unknown';

        return view('advance-loan.apply.show', compact('advance', 'advanceTypes'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $advance = AdvanceApplication::findOrFail($id);

        $advanceTypes = MasAdvanceTypes::all(); // Fetch advance types
        return view('advance-loan.apply.edit', compact('advance', 'advanceTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     // dd($request->all());
    //     // Find the advance application record by ID or fail if not found
    //     $advanceApplication = AdvanceApplication::findOrFail($id);

    //     // Define validation rules and messages (you can move them to class properties if reused)
    //     $validatedData = $request->validate([
    //         'advance_no' => 'sometimes|required',
    //         'date' => 'sometimes|required|date',
    //         'advance_type_id' => 'sometimes|required',
    //         'mode_of_travel' => 'nullable',
    //         'from_location' => 'nullable|string',
    //         'to_location' => 'nullable|string',
    //         'from_date' => 'nullable|date',
    //         'to_date' => 'nullable|date|after_or_equal:from_date',
    //         'amount' => 'nullable|numeric|min:0',
    //         'attachment' => 'nullable|mimes:jpg,png,pdf|max:2048',
    //         'total_amount' => 'nullable|numeric|min:0',
    //         'no_of_emi' => 'nullable|integer',
    //         'monthly_emi_amount' => 'nullable|numeric',
    //         'deduction_from_period' => 'nullable|date_format:Y-m',
    //         'item_type' => 'nullable|string',
    //         'remark' => 'nullable|string|max:255',
    //     ]);

    //     // Handle file upload for the attachment
    //     // Handle file upload for the attachment
    //     if ($request->hasFile('attachment')) {
    //         // Check if there is an existing file and delete it
    //         if ($advanceApplication->attachment) {
    //             $existingFilePath = public_path($advanceApplication->attachment);
    //             if (file_exists($existingFilePath) && is_file($existingFilePath)) {
    //                 unlink($existingFilePath); // Delete the existing file
    //             }
    //         }

    //         // Upload the new file and save the path
    //         $file = $request->file('attachment');
    //         $path = uploadImageToDirectory($file, $this->attachmentPath); // Ensure this function generates a relative path
    //         $validatedData['attachment'] = $path; // Save the relative path
    //     } else {
    //         // If no new file is uploaded, keep the existing attachment path
    //         $validatedData['attachment'] = $advanceApplication->attachment; // Maintain the existing path
    //     }


    //     try {
    //         DB::beginTransaction();

    //         // Use fill() method to update all fields at once
    //         $advanceApplication->fill($validatedData);

    //         // Save the updated model
    //         $advanceApplication->save();

    //         // Optionally create a corresponding history record for the advance
    //         $advanceApplication->histories()->create([
    //             'level' => 'Test Level',
    //             'status' => 1,
    //             'remarks' => $request->remark ?? $advanceApplication->remark,
    //             'created_by' => loggedInUser(),
    //         ]);

    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->withInput()->with('msg_error', $e->getMessage());
    //     }

    //     return redirect()->route('apply.index')->with('msg_success', 'Advance application updated successfully!');
    // }

    public function update(Request $request, $id)
    {
        // Find the advance application record by ID or fail if not found
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

            // Update the advance application fields
            $advanceApplication->update([
                'advance_no' => $request->advance_no ?? $advanceApplication->advance_no,
                'date' => $request->date,
                'advance_type_id' => $request->advance_type_id,
                'mode_of_travel' => $request->mode_of_travel,
                'from_location' => $request->from_location,
                'to_location' => $request->to_location,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'amount' => $request->amount,
                'total_amount' => $request->total_amount,
                'no_of_emi' => $request->no_of_emi,
                'monthly_emi_amount' => $request->monthly_emi_amount,
                'deduction_from_period' => $request->deduction_from_period,
                'item_type' => $request->item_type,
                'remark' => $request->remark,
                'status' => $request->status ?? 1,
                'attachment' => $validatedData['attachment'], // Use the validated attachment data
            ]);

            // Optionally create a history record for the advance application
            $advanceApplication->histories()->create([
                'level' => 'Test Level', // This could be dynamic, depending on the use case
                'status' => $advanceApplication->status,
                'remarks' => $request->remark ?? $advanceApplication->remark,
                'created_by' => loggedInUser(),  // Assuming loggedInUser() fetches the current user's ID
                'updated_by' => loggedInUser(),
            ]);

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();

            // Handle the error by returning back with error message
            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        // Return a success response after the update is complete
        return redirect()->route('apply.index')->with('msg_success', 'Advance application updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { {
            try {
                AdvanceApplication::findOrFail($id)->delete();

                return back()->with('msg_success', 'Advance Applicaton has been deleted');
            } catch (\Exception $e) {
                return back()->with('msg_error', 'Advance Applicaton cannot be deleted as it is used by other modules.');
            }
        }
    }
}
