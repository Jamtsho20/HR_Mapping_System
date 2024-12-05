<?php

namespace App\Http\Controllers\TravelAuthorization;

use App\Http\Controllers\Controller;
use App\Models\TravelAuthorizationApplication;
use App\Models\DailyAllowance;
use App\Models\MasTravelType;
use App\Models\MasEmployeeJob;
use App\Services\ApprovalService;
use App\Models\MasAdvanceTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationForwardedMail;

class TravelAuthorizationApplicationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     *@return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:travel-authorization/apply-travel-authorization,view')->only('index', 'show');
        $this->middleware('permission:travel-authorization/apply-travel-authorization,create')->only('store');
        $this->middleware('permission:travel-authorization/apply-travel-authorization,edit')->only('update');
        $this->middleware('permission:travel-authorization/apply-travel-authorization,delete')->only('destroy');
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
        $travelAuthorizations = TravelAuthorizationApplication::with('employee')->createdBy()->filter($request)->orderBy('created_at')->paginate(config('global.pagination'))->withQueryString();

        return view('travel-authorizations.apply.index', compact('privileges', 'travelAuthorizations'));
    }

    public function create()
    {
        require_once base_path('app/Http/constants.php');

        $user = Auth::user();
        $userId = $user->id;
        $gradeId = MasEmployeeJob::where('mas_employee_id', $userId)->value('mas_grade_id');
        $dailyAllowance = DailyAllowance::where('mas_grade_id', $gradeId)->value('da_in_country');
        $travelAuthorizationNumber = $this->getTravelAuthorizationNumber();
        $travelTypes = MasTravelType::all();
        // $defaultTravelTypeId = 1;

        $defaultTravelTypeId = request()->get('travel_type', 1);

        return view('travel-authorizations.apply.create', compact('travelTypes', 'dailyAllowance', 'travelAuthorizationNumber', 'defaultTravelTypeId'));
    }


    public function store(Request $request)
    {
        $travelAuthorization = new  TravelAuthorizationApplication();
        $this->validate($request, $this->rules, $this->messages);
        $conditionFields = approvalHeadConditionFields(TRAVEL_AUTHORIZATION_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        //dd($conditionFields);
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->travel_type, \App\Models\MasTravelType::class, $conditionFields ?? []);
        // dd($request->travel_type);

        try {
            DB::beginTransaction();
            $travelAuthorization->travel_authorization_no = $request->travel_authorization_no;
            $travelAuthorization->date = $request->date;
            $travelAuthorization->advance_amount = $request->advance_required;
            $travelAuthorization->estimated_travel_expenses = $request->estimated_travel_expenses;
            $travelAuthorization->status = 1;
            $travelAuthorization->daily_allowance = $request->daily_allowance;
            $travelAuthorization->created_by = Auth::id();
            $travelAuthorization->travel_type_id = $request->travel_type;


            $travelAuthorization->save();
            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $travelAuthorization->details()->create([
                        'mode_of_travel' => $detail['mode_of_travel'],
                        'from_location' => $detail['from_location'],
                        'to_location' => $detail['to_location'],
                        'from_date' => $detail['from_date'],
                        'to_date' => $detail['to_date'],
                        'purpose' => $detail['purpose'],
                    ]);
                }
            }




            $travelAuthorization->histories()->create([
                'approval_option' => $approverByHierarchy['approval_option'],
                'hierarchy_id' => $approverByHierarchy['hierarchy_id'] ?? null,
                'level_id' => $approverByHierarchy['next_level']->id ?? null,
                'approver_role_id' => $approverByHierarchy['approver_details']['approver_role_id'] ?? null,
                'approver_emp_id' => $approverByHierarchy['approver_details']['user_with_approving_role']->id ?? null,
                'level_sequence' => $approverByHierarchy['next_level']->sequence ?? null,
                'status' => $approverByHierarchy['application_status'],
                'remarks' => $request->remarks ?? null,
                'action_performed_by' => loggedInUser(),
            ]);


            DB::commit();
            if(isset($approverByHierarchy['approver_details'])){
                $emailContent = 'has submitted a travel authorization application and is awaiting your approval for a estimated travel expense of ' . $request->estimated_travel_expenses ;
                $emailSubject = 'Travel Authorization Application';
                Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }
        
        return redirect()->route('apply-travel-authorization.index')->with('msg_success', 'Travel Authorization application created successfully!');
    }

    public function show($id, Request $request)
    {
        $instance = $request->instance();
        $travelAuthorization =  TravelAuthorizationApplication::findOrFail($id);
        $context = 'application';
        return view('travel-authorizations.apply.show', compact('travelAuthorization', 'context'));
    }



    public function edit($id)
    {
        $travelAuthorizations =  TravelAuthorizationApplication::findOrfail($id);
        $dailyAllowance = $travelAuthorizations->daily_allowance;
        $travelTypes = MasTravelType::all();
        return view('travel-authorizations.apply.edit', compact('travelAuthorizations', 'dailyAllowance', 'travelTypes'));
    }


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
        return redirect()->route('apply-travel-authorization.index')->with('msg_success', 'Travel Authorization updated successfully!');
    }


    public function destroy($id)
    {
        try {
            TravelAuthorizationApplication::findOrFail($id)->delete();
            // dd(TravelAuthorization::findOrFail($id));
            return back()->with('msg_success', 'Travel Authorization has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Travel Authorization cannot be deleted as it is used by other modules.');
        }
    }

    public function getTravelAuthorizationNumber()
    {
        $travelAuthPrefix = MasTravelType::where('id', 1)->get('code')->first()->code;

        $latestTransaction =  TravelAuthorizationApplication::latest('id')->first();

        $nextSequence = $latestTransaction ? (int)substr($latestTransaction->travel_authorization_no, -4) + 1 : 1;



        $authorizationNo = generateTransactionNumber($travelAuthPrefix, $nextSequence);

        // Return the generated Travel Authorization number
        return $authorizationNo;
    }
}
