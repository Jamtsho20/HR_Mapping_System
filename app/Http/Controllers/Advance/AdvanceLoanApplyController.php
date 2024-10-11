<?php

namespace App\Http\Controllers\Advance;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasAdvanceTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvanceLoanApplyController extends Controller
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

    private $attachmentPath = 'images/attachments/';

    protected $rules = [
        'advance_no' => 'required|string|max:255',
        'date' => 'required|date',
        'advance_loan_type' => 'required|in:1,2,3,4,5,6,7',
        //'advance_type_id' => 'required|exists:mas_advance_types,id',
        'mode_of_travel' => 'nullable|in:1,2,3,4,5',
        'from_location' => 'nullable|string|max:255',
        'to_location' => 'nullable|string|max:255',
        'from_date' => 'nullable|date',
        'to_date' => 'nullable|date|after_or_equal:from_date',
        'amount' => 'nullable|numeric|min:0',
        'purpose' => 'nullable|string|max:150',
        'attachment' => 'nullable|mimes:jpg,png,pdf|max:2048',
        'interest_rate' => 'nullable|numeric|min:0',
        'total_amount' => 'nullable|numeric|min:0',
        'no_of_emi' => 'nullable|in:1,2,3,4',
        'monthly_emi_amount' => 'nullable|numeric|min:0',
        'deduction_from_period' => 'nullable|date',
        'item_type' => 'nullable|string|max:255',
        'mas_employee_id' => 'required|exists:mas_employees,id',
    ];
    private $travelModes = [
        1 => 'Bike',
        2 => 'Bus',
        3 => 'Car',
        4 => 'Flight',
        5 => 'Train'
    ];
    protected $messages = [
        'advance_no.required' => 'The advance number field is required.',
        'date.required' => 'The date field is required.',
        'advance_loan_type.required' => 'The advance type field is required.',
        'advance_type.in' => 'The selected advance type is invalid.',
        'mode_of_travel.in' => 'The selected mode of travel is invalid.',
        'from_date.date' => 'The from date must be a valid date.',
        'to_date.after_or_equal' => 'The to date must be a date after or equal to the from date.',
        'attachment.mimes' => 'The attachment must be a file of type: jpg, png, pdf.',
        'attachment.max' => 'The attachment may not be greater than 2MB.',
        'mas_employee_id.required' => 'The employee field is required.',
        'mas_employee_id.exists' => 'The selected employee does not exist.',
    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $advances = AdvanceApplication::with('advanceType')->paginate(10);
        foreach ($advances as $advance) {
            $advance->formatted_date = Carbon::parse($advance->date)->format('Y-m-d');
        }

        return view('advance-loan.apply.index', compact('privileges', 'advances'));
    }

    public function create()
    {
        $advanceTypes = MasAdvanceTypes::all();
        $interestRates = DB::table('interest_rates')->pluck('rate', 'advance_type_id');

        return view('advance-loan.apply.create', compact('advanceTypes','interestRates'));
    }

    public function store(Request $request)
    {
        // Validate the request using your defined rules and messages
        $this->validate($request, $this->rules, $this->messages);
    
        // Calculate total amount based on the entered values
        $amount = $request->input('amount'); // e.g. 1000
        $interestRate = $request->input('interest_rate'); // e.g. 12
    
        // Calculate total amount if amount and interest rate are provided
        if ($amount && $interestRate) {
            $totalAmount = $amount + ($amount * ($interestRate / 100));
        } else {
            $totalAmount = 0; // Default value if not provided
        }
    
        // Create a new instance of AdvanceApplication
        $advanceApplication = new AdvanceApplication();
    
        // Initialize the attachment variable
        $attachment = "";
    
        try {
            // Check if an attachment file was uploaded
            if ($request->hasFile('attachment')) {
                // Store the file and generate a unique filename
                $file = $request->file('attachment');
                $attachment = uploadImageToDirectory($file, $this->attachmentPath);
            } else {
                throw new \Exception('Please upload the attachment.');
            }
        } catch (\Exception $e) {
            // If an error occurs, redirect back with the error message
            return back()->withInput()->with('msg_error', 'Failed to upload the attachment: ' . $e->getMessage());
        }
    
        // Assign validated data to the model attributes
        $advanceApplication->advance_no = $request->advance_no;
        $advanceApplication->date = $request->date;
        $advanceApplication->advance_type = $request->input('advance_loan_type');
        $advanceApplication->mas_employee_id = $request->mas_employee_id;
        $advanceApplication->mode_of_travel = $request->mode_of_travel ?? null;
        $advanceApplication->from_location = $request->from_location ?? null;
        $advanceApplication->to_location = $request->to_location ?? null;
        $advanceApplication->from_date = $request->from_date ?? null;
        $advanceApplication->to_date = $request->to_date ?? null;
        $advanceApplication->amount = $amount; // Use the calculated amount
        $advanceApplication->purpose = $request->purpose ?? null;
        $advanceApplication->attachment = $attachment; // Store attachment path
        $advanceApplication->interest_rate = $interestRate; // Store interest rate
        $advanceApplication->total_amount = $totalAmount; // Set the calculated total amount
        $advanceApplication->no_of_emi = $request->no_of_emi ?? null;
    
        // Calculate monthly EMI if no_of_emi is provided
        if ($request->no_of_emi && $totalAmount > 0) {
            $advanceApplication->monthly_emi_amount = $totalAmount / $request->no_of_emi;
        }
    
        $advanceApplication->deduction_from_period = $request->deduction_from_period ?? null;
        $advanceApplication->item_type = $request->item_type ?? null;
        $advanceApplication->created_by = auth()->user()->id;
        $advanceApplication->updated_by = auth()->user()->id;
    
        // Set the default status to '1' (New)
        $advanceApplication->status = 1;
    
        // Save the advance application record to the database
        $advanceApplication->save();
    
        // Redirect to the advance loan index with a success message
        return redirect()->route('apply.index')->with('success', 'Advance application created successfully!');
    }
    
    

    public function show($id, Request $request)
    {
        $instance = $request->instance();
        $advance = AdvanceApplication::with('advanceType')->findOrFail($id);
        $advanceTypes = MasAdvanceTypes::all();
        $advance->mode_of_travel_name = $this->travelModes[$advance->mode_of_travel] ?? 'Unknown';

        return view('advance-loan.apply.show', compact('advance','advanceTypes'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
}
