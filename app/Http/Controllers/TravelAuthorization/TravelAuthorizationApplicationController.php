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
      'mode_of_travel' => 'required',
      'from_location' => 'required',
      'to_location' => 'required',
      'from_date' => 'required',
      'to_date' => 'required',
      'advance_amount' => 'nullable',
      'purpose' => 'nullable|string|max:500',
  ];
  
  protected $messages = [
      'mode_of_travel.required_if' => 'Mode of travel is required for the selected travel type.',
      'from_location.required_if' => 'From location is required for the selected travel type.',
      'to_location.required_if' => 'To location is required for the selected travel type.',
      'from_date.required_if' => 'From date is required for the selected travel type.',
      'to_date.required_if' => 'To date is required for the selected travel type and must be after or equal to the from date.',
      'amount.required_if' => 'Amount is required for the selected travel type.',
      'attachment.required_if' => 'Attachment is required for the selected travel type and must be a valid file (jpg, png, pdf).',
      'remark.max' => 'Remark should not exceed 500 characters.',
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
        return view('travel-authorizations.apply.create', compact('dailyAllowance'));
      }


public function store(Request $request)
    {
        $travelAuthorization = new TravelAuthorization();
        $this->validate($request, $this->rules, $this->messages);
        try {
            DB::beginTransaction();

            $travelAuthorization->date = $request->date;
            $travelAuthorization->from_date = $request->from_date;
            $travelAuthorization->to_date = $request->to_date;
            $travelAuthorization->from_location = $request->from_location;
            $travelAuthorization->to_location = $request->to_location;
            $travelAuthorization->mode_of_travel = $request->mode_of_travel;
            $travelAuthorization->advance_amount = $request->advance_required;
            $travelAuthorization->estimated_travel_expenses = $request->estimated_travel_expenses;
            $travelAuthorization->purpose = $request->remark ?? null;
            $travelAuthorization->status = 1;
            $travelAuthorization->daily_allowance = $request->daily_allowance;
            $travelAuthorization->created_by = Auth::id();
        

            $travelAuthorization->save();

            $travelAuthorization->histories()->create([
                'level' => 'Test Level',
                'status' => 1,
                'remarks' => $request->remarks,
                'created_by' => loggedInUser(),
            ]);


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
        $travelAuthorization = TravelAuthorization::findOrfail($id);
        return view('travel-authorizations.apply.edit', compact('travelAuthorization'));
   
    }

   
    public function update(Request $request, $id)
    {   
        $travelAuthorization = TravelAuthorization::findOrFail($id);

        $validatedData = $request->validate([
            'date' => 'sometimes|required|date',
            'mode_of_travel' => 'sometimes|required',
            'from_location' => 'required',
            'to_location' => 'required',
            'from_date' => 'sometimes|required',
            'to_date' => 'sometimes|required',
            'advance_amount' => 'nullable',
            'estimated_travel_expenses' => 'sometimes|required',
            'purpose' => 'nullable|string|max:500',
            'daily_allowance' => 'nullable',
        ]);
        try {
            DB::beginTransaction();
            $travelAuthorization->fill($validatedData);
            $travelAuthorization->save();

            $travelAuthorization->histories()->create([
                'level' => 'Test Level',
                'status' => 1,
                'remarks' => $request->remark ?? $travelAuthorization->remark,
                'created_by' => loggedInUser(),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
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
}

