<?php

namespace App\Http\Controllers\TravelAuthorization;
use App\Http\Controllers\Controller;
use App\Models\ApplicationHistory;
use App\Models\TravelAuthorizationApplication;
use App\Models\MasTravelType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ApprovalService;
use Illuminate\Support\Facades\Auth;
class TravelAuthorizationApprovalController extends Controller
{
         
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:travel-authorization/travel-authorization-approval,view')->only('index');
        $this->middleware('permission:travel-authorization/travel-authorization-approval,create')->only('store');
        $this->middleware('permission:travel-authorization/travel-authorization-approval,edit')->only('update');
        $this->middleware('permission:travel-authorization/travel-authorization-approval,delete')->only('destroy');
    }

    protected $rules = [
        'date' => 'required|date',
        'details.*.mode_of_travel' => 'required|string',
        'details.*.from_location' => 'required|string',
        'details.*.to_location' => 'required|string',
        'details.*.from_date' => 'required|date',
        'details.*.to_date' => 'required|date|after_or_equal:details.*.from_date',
        'advance_amount' => 'nullable|numeric',
        'details.*.purpose' => 'nullable|string|max:500',
        'travel_type' => 'required|exists:mas_travel_types,id', 
    ];
    
    protected $messages = [
        'date.required' => 'The main travel date is required.',
        'details.*.mode_of_travel.required' => 'Mode of travel is required for each travel detail.',
        'details.*.from_location.required' => 'From location is required for each travel detail.',
        'details.*.to_location.required' => 'To location is required for each travel detail.',
        'details.*.from_date.required' => 'From date is required for each travel detail.',
        'details.*.to_date.required' => 'To date is required for each travel detail.',
        'details.*.to_date.after_or_equal' => 'To date must be after or equal to the from date for each travel detail.',
        'advance_amount.numeric' => 'Advance amount should be a numeric value.',
        'details.*.purpose.max' => 'Purpose should not exceed 500 characters for each travel detail.',
        'travel_type.required' => 'The travel type is required.',
        'travel_type.exists' => 'The selected travel type is invalid.', 
    ];
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $user = auth()->user();
        $historyData = ApplicationHistory::whereHas('application', function ($query) {
            $query->where('application_type', 'App\Models\TravelAuthorizationApplication'); // Assuming you store this class in 'application_type' column
        })->where('approver_emp_id', $user->id)
          ->get();
        $travelAuthorizations = TravelAuthorizationApplication::whereHas('histories', function ($query) use ($user) {
            $query
                ->where('application_type', \App\Models\TravelAuthorizationApplication::class)
                ->where('approver_emp_id', $user->id);
        })
            ->whereNotIn('status', [-1, 3])
            ->filter($request, false) //sent onesOenRecord parameter as flase as it need to fetch all despites of authenticated user
            ->orderBy('created_at')
            ->paginate(config('global.pagination'))
            ->withQueryString();
    
        return view('travel-authorizations.approval.index', compact( 'privileges', 'travelAuthorizations'));
    } 


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $instance = $request->instance();
        $travelAuthorization =  TravelAuthorizationApplication::findOrFail($id);
        $context = 'approval';         
        return view('travel-authorizations.apply.show', compact('travelAuthorization', 'context'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $travelTypes = MasTravelType::get(['id', 'name']);
        $travelAuthorizations = TravelAuthorizationApplication::findOrfail($id);
        return view('travel-authorizations.approval.edit', compact('travelAuthorizations', 'travelTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $travelAuthorization =  TravelAuthorizationApplication::findOrFail($id);

        $this->validate($request, $this->rules, $this->messages);

        try {
        
            DB::beginTransaction();

            $travelAuthorization->update([
                'travel_authorization_no' => $request->travel_authorization_no,
                'date' => $request->date,
                'advance_amount' => $request->advance_required,
                'estimated_travel_expenses' => $request->estimated_travel_expenses,
                'status' => 1,
                'daily_allowance' => $request->daily_allowance,
                'updated_by' => Auth::id(),
                'travel_type_id' => $request->travel_type,
            ]);

            
            if ($request->has('details')) {
                // Collect IDs of updated or existing details from the request
                $updatedDetailIds = [];
            
                foreach ($request->details as $index => $detail) {
                    if (isset($detail['id'])) {
                        $travelDetail = $travelAuthorization->details()->find($detail['id']);
                        if ($travelDetail) {
                            $travelDetail->update([
                                'mode_of_travel' => $detail['mode_of_travel'],
                                'from_location' => $detail['from_location'],
                                'to_location' => $detail['to_location'],
                                'from_date' => $detail['from_date'],
                                'to_date' => $detail['to_date'],
                                'purpose' => $detail['purpose'],
                            ]);
                            $updatedDetailIds[] = $detail['id'];
                        }
                    } else {
                        // Create new detail if no ID is provided
                        $newDetail = $travelAuthorization->details()->create([
                            'mode_of_travel' => $detail['mode_of_travel'],
                            'from_location' => $detail['from_location'],
                            'to_location' => $detail['to_location'],
                            'from_date' => $detail['from_date'],
                            'to_date' => $detail['to_date'],
                            'purpose' => $detail['purpose'],
                        ]);
                        $updatedDetailIds[] = $newDetail->id;
                    }
                }
            
                // Delete details that weren't included in the request
                $travelAuthorization->details()->whereNotIn('id', $updatedDetailIds)->delete();
            }
    
            DB::commit();
            // if ($request->has('details')) {
        
            //     $travelAuthorization->details()->forceDelete();
            //     foreach ($request->details as $detail) {
            //         $travelAuthorization->details()->create([
            //             'mode_of_travel' => $detail['mode_of_travel'],
            //             'from_location' => $detail['from_location'],
            //             'to_location' => $detail['to_location'],
            //             'from_date' => $detail['from_date'],
            //             'to_date' => $detail['to_date'],
            //             'purpose' => $detail['purpose'],
            //         ]);
            //     }
            // }

           
            // $travelAuthorization->histories()->create([
            //     'level' => 'Test Level', // Adjust this according to your requirements
            //     'status' => 1, // Adjust as needed
            //     'remarks' => $request->remarks,
            //     'created_by' => loggedInUser(),
            // ]);

           
            // DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
        return redirect()->route('travel-authorization-approval.index')->with('msg_success', 'Travel Authorization updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function bulkApprovalRejection(Request $request)
    {
        $action = $request->action;
        $itemIds = $request->item_ids;
        $status = ($action === 'approve') ? 2 : -1;
        $rejectRemarks = $request->input('reject_remarks', '');
        $userId = auth()->id();
        $responseMessage = $action === 'approve' ? 'approved.' : 'rejected.';
        DB::beginTransaction();
        try {
            $approvalService = new ApprovalService();

            foreach ($itemIds as $id) {
                $travelAuthorizationApplication = TravelAuthorizationApplication::findOrFail($id);
                $applicationHistory = $travelAuthorizationApplication->histories
                    ->where('application_type', TravelAuthorizationApplication::class)
                    ->where('application_id', $id)
                    ->first();

                // Update leave application status
                $travelAuthorizationApplication->update([
                    'status' => $status,
                    'updated_by' => $userId,
                ]);

                // Forward application if approved
                $updateData = [
                    'status' => $status,
                    'remarks' => $rejectRemarks,
                    'action_performed_by' => $userId,
                ];

                if ($action === 'approve' && $applicationHistory) {
                    $applicationForwardedTo = $approvalService->applicationForwardedTo($id, TravelAuthorizationApplication::class);
                    // dd($applicationForwardedTo);
                    if ($applicationForwardedTo && isset($applicationForwardedTo['next_level'])) {
                        $updateData = array_merge($updateData, [
                            'level_id' => $applicationForwardedTo['next_level']->id,
                            'approver_role_id' => $applicationForwardedTo['approver_details']['approver_role_id'],
                            'approver_emp_id' => $applicationForwardedTo['approver_details']['user_with_approving_role']->id,
                            'level_sequence' => $applicationForwardedTo['next_level']->sequence,
                        ]);
                        // Attempt to send email to next approver need to work on it
                        // try {
                        //     Mail::to($nextApprover->email)->send(new NextApproverNotificationMail($leaveApplication, $nextApprover));
                        // } catch (\Exception $e) {
                        //     \Log::error('Failed to send email to next approver: ' . $e->getMessage());
                        // }
                    } elseif ($applicationForwardedTo && isset($applicationForwardedTo['application_status']) && $applicationForwardedTo['application_status'] === 'max_level_reached') {
                        // Finalize approval if it's at the maximum level
                        $travelAuthorizationApplication->update([
                            'status' => 3, // 3 could represent 'final approved'
                            'updated_by' => $userId,
                        ]);
                        $updateData['status'] = 3; // Mark the history entry as final approved
                    } elseif ($applicationForwardedTo && $applicationForwardedTo['application_status'] === 3) {
                        $travelAuthorizationApplication->update([
                            'status' => $applicationForwardedTo['application_status'], // 3 could represent 'final approved'
                            'updated_by' => $userId,
                        ]);
                        $updateData['status'] = $applicationForwardedTo['application_status'];
                    }
                }
                // Update application history
                if ($applicationHistory) {
                    $applicationHistory->update($updateData);
                }

                // Attempt to send email to applicant about the approval/rejection status need to work on it
                // try {
                //     Mail::to($user->email)->send(new LeaveApplicationStatusMail($leaveApplication, $action, $rejectRemarks));
                // } catch (\Exception $e) {
                //     \Log::error('Failed to send email to applicant: ' . $e->getMessage());
                // }
            }

            DB::commit();
            return response()->json(['message' => 'All Travel Authorization has been successfully ' . $responseMessage], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk approval/rejection error: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred during the operation.'], 500);
        }
    }
}
