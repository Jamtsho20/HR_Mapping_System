<?php

namespace App\Http\Controllers\Api\V1\TravelAuthorization;

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
use App\Services\ApplicationHistoriesService;

use App\Traits\JsonResponseTrait;
use App\Http\Controllers\AjaxRequestController;

class TravelAuthorizationApplicationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     *@return \Illuminate\Http\Response
     */
    use JsonResponseTrait;
    protected $ajaxRequestController;
    protected $ajax;
    public function __construct(AjaxRequestController $ajaxRequestController)
    {
        $this->middleware('auth:api');
        $this->ajaxRequestController = $ajaxRequestController;
        $this->ajax = $ajaxRequestController;
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
        try {
            $privileges = $request->instance();
            $travelAuthorizations = TravelAuthorizationApplication::with('travelType:id,name', 'travel_approved_by:id,name')->with('details')->createdBy()->filter($request)->orderBy('created_at', 'desc')->paginate(config('global.pagination'))->withQueryString();
            return response()->json([
                'message' => 'Travel authorization applications retrieved successfully',
               'travelAuthorizations' => $travelAuthorizations
            ]);
          } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Failed to retrieve applications', 500);
        }

    }

    public function create()
    {
        try {
            require_once base_path('app/Http/constants.php');

            $user = Auth::user();
            $userId = $user->id;
            $gradeId = MasEmployeeJob::where('mas_employee_id', $userId)->value('mas_grade_id');
            $dailyAllowance = DailyAllowance::where('mas_grade_id', $gradeId)->value('da_in_country');
            $travelAuthorizationNumber = $this->getTravelAuthorizationNumber();
            $travelTypes = MasTravelType::all();
            // $defaultTravelTypeId = 1;

            $defaultTravelTypeId = request()->get('travel_type', 1);
                 return response()->json([
                'success' => true,
                'message' => 'Travel Authorization create function executed successfully!',
                'data' => [
                    'travelTypes' => $travelTypes,
                    'dailyAllowance' => $dailyAllowance,
                    'defaultTravelTypeId' => $defaultTravelTypeId

                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Failed to retrieve applications', 500);
        }
           }

    public function fetchTravelAuthorizationNumber($id)
    {
        $travelNo = $this->ajaxRequestController->getTravelNumber($id);

        return response()->json([
            'travel_no' => $travelNo,
        ]);
    }


    public function store(Request $request)
    {
        try{

        $travelAuthorization = new  TravelAuthorizationApplication();
            // dd($request->all());
        $validator = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        $conditionFields = approvalHeadConditionFields(TRAVEL_AUTHORIZATION_APPVL_HEAD, $request); // fetching condition field for particular aprroval head
        //dd($conditionFields);
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy($request->travel_type, \App\Models\MasTravelType::class, $conditionFields ?? []);
        $date= formatDate(request('date'));
        try {
            DB::beginTransaction();
            $travelAuthorization->travel_authorization_no = $request->travel_authorization_no;
            $travelAuthorization->date = $date;
            $travelAuthorization->advance_amount = $request->advance_required;
            $travelAuthorization->estimated_travel_expenses = $request->estimated_travel_expenses;
            $travelAuthorization->status = 1;
            $travelAuthorization->daily_allowance = $request->daily_allowance;
            $travelAuthorization->created_by = Auth::id();
            $travelAuthorization->type_id = $request->travel_type;


            $travelAuthorization->save();

            if ($request->has('details')) {
                foreach ($request->details as $detail) {

                    $travelAuthorization->details()->create([
                        'mode_of_travel' => $detail['mode_of_travel'],
                        'from_location' => $detail['from_location'],
                        'to_location' => $detail['to_location'],
                        'from_date' => formatDate($detail['from_date']),
                        'to_date' => formatDate($detail['to_date']),
                        'purpose' => $detail['purpose'],
                    ]);

                }
            }




            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory($travelAuthorization->histories(), $approverByHierarchy, $request->remarks);


            DB::commit();
            if(isset($approverByHierarchy['approver_details'])){
                $emailContent = 'has submitted a travel authorization application and is awaiting your approval for a estimated travel expense of ' . $request->estimated_travel_expenses ;
                $emailSubject = 'Travel Authorization Application';
                Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->email, $emailContent, $emailSubject));
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->errorResponse('Failed to store application', 500);

        }

        return $this->successResponse($travelAuthorization, 'Travel Authorization application has been successfully created.', 201);
    }catch (\Illuminate\Validation\ValidationException $e) {

        return $this->errorResponse('Failed to store application', 500, $e);
    }
    }

    public function show($id, Request $request)
    {   try {
        $instance = $request->instance();
        $travelAuthorization =  TravelAuthorizationApplication::with('details')->findOrFail($id);
        return $this->successResponse($travelAuthorization, 'Travel Authorization retrieved successfully');
    }catch (\Illuminate\Validation\ValidationException $e) {
        return $this->errorResponse('Failed to retrieve applications', 500);
    }
}



    public function getTravelTypes()
    {
        try{
        $travelTypes = MasTravelType::all()->toArray();
        return response()->json([
            'travelTypes' => $travelTypes
        ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Failed to retrieve applications', 500);
        }
    }


    public function update(Request $request, $id)
    {
        $travelAuthorization =  TravelAuthorizationApplication::findOrFail($id);

        $validator = \Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }
        $date= formatDate(request('date'));
        try {

            DB::beginTransaction();

            $travelAuthorization->update([
                'travel_authorization_no' => $request->travel_authorization_no,
                'date' => $date,
                'advance_amount' => $request->advance_required,
                'estimated_travel_expenses' => $request->estimated_travel_expenses,
                'status' => 1,
                'daily_allowance' => $request->daily_allowance,
                'updated_by' => Auth::id(),
                'type_id' => $request->travel_type,
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
                                'from_date' => formatDate($detail['from_date']),
                                'to_date' => formatDate($detail['to_date']),
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

         return $this->successResponse($travelAuthorization, 'Travel Authorization retrieved successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Failed to retrieve applications', 500);
        }

   }


    public function destroy($id)
    {
        try {
            TravelAuthorizationApplication::findOrFail($id)->delete();
            // dd(TravelAuthorization::findOrFail($id));
            return $this->successResponse($id, 'Travel Authorization has been deleted');
        } catch (\Exception $e) {
            return $this->errorResponse('Travel Authorization cannot be deleted.');
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
