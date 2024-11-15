<?php

namespace App\Http\Controllers\TravelAuthorization;
use App\Http\Controllers\Controller;
use App\Models\TravelAuthorization;
use App\Models\MasAdvanceTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
    ];
  
public function index(Request $request)
      {
          $privileges = $request->instance();
          $travelAuthorizations = TravelAuthorization::with('employee')->filter($request)->orderBy('created_at')->paginate(config('global.pagination'))
          ->withQueryString();
  
          return view('travel-authorizations.apply.index', compact('privileges', 'travelAuthorizations'));
      }

public function create()
      {
        require_once base_path('app/Http/constants.php');

        $dailyAllowance = DAILY_ALLOWANCE;
        $travelAuthorizationNumber = $this->getTravelAuthorizationNumber();
        return view('travel-authorizations.apply.create', compact('dailyAllowance', 'travelAuthorizationNumber'));
      }


public function store(Request $request)
    {
        $travelAuthorization = new TravelAuthorization();
        $this->validate($request, $this->rules, $this->messages);

        
        try {
            DB::beginTransaction();
            $travelAuthorization->travel_authorization_no = $request->travel_authorization_no;
            $travelAuthorization->date = $request->date;
            $travelAuthorization->advance_amount = $request->advance_required;
            $travelAuthorization->estimated_travel_expenses = $request->estimated_travel_expenses;
            $travelAuthorization->status = 1;
            $travelAuthorization->daily_allowance = $request->daily_allowance;
            $travelAuthorization->created_by = Auth::id();

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
        

            

            // $travelAuthorization->histories()->create([
            //     'level' => 'Test Level',
            //     'status' => 1,
            //     'remarks' => $request->remarks,
            //     'created_by' => loggedInUser(),
            // ]);


            DB::commit();
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
        $travelAuthorization = TravelAuthorization::findOrFail($id);         
        return view('travel-authorizations.apply.show', compact('travelAuthorization'));

    }


    
    public function edit($id)
    {
        $travelAuthorizations = TravelAuthorization::findOrfail($id);
        $dailyAllowance = DAILY_ALLOWANCE;
        return view('travel-authorizations.apply.edit', compact('travelAuthorizations', 'dailyAllowance'));
   
    }

   
    public function update(Request $request, $id)
    {   
        $travelAuthorization = TravelAuthorization::findOrFail($id);

        $this->validate($request, $this->rules, $this->messages);

        try {
        
            DB::beginTransaction();

            $travelAuthorization->travel_authorization_no = $request->travel_authorization_no;
            $travelAuthorization->date = $request->date;
            $travelAuthorization->advance_amount = $request->advance_required;
            $travelAuthorization->estimated_travel_expenses = $request->estimated_travel_expenses;
            $travelAuthorization->status = 1;  
            $travelAuthorization->daily_allowance = $request->daily_allowance;
            $travelAuthorization->updated_by = Auth::id();

            
            $travelAuthorization->save();

        
            if ($request->has('details')) {
        
                $travelAuthorization->details()->forceDelete();
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

           
            // $travelAuthorization->histories()->create([
            //     'level' => 'Test Level', // Adjust this according to your requirements
            //     'status' => 1, // Adjust as needed
            //     'remarks' => $request->remarks,
            //     'created_by' => loggedInUser(),
            // ]);

           
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('msg_error', $e->getMessage());
        }
        return redirect()->route('apply-travel-authorization.index')->with('msg_success', 'Travel Authorization updated successfully!');
    }

    
    public function destroy($id)
    {
        try {
            TravelAuthorization::findOrFail($id)->delete();
            // dd(TravelAuthorization::findOrFail($id));
            return back()->with('msg_success', 'Travel Authorization has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Travel Authorization cannot be deleted as it is used by other modules.');
        }
    
    }

    public function getTravelAuthorizationNumber()
{
    
    
     $travelAuthPrefix = 'TA';

     
     $latestTransaction = TravelAuthorization::latest('id')->first();

     $nextSequence = $latestTransaction ? (int)substr($latestTransaction->travel_authorization_no, -4) + 1 : 1;
     
 
   
     $authorizationNo = generateTransactionNumber($travelAuthPrefix, $nextSequence);
 
     // Return the generated Travel Authorization number
     return $authorizationNo;
     
}
}

