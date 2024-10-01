<?php

namespace App\Http\Controllers\Advance;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasAdvanceTypes;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        'advance-loan-type' => 'required|in:1,2,3,4,5,6,7',
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

    protected $messages = [
        'advance_no.required' => 'The advance number field is required.',
        'date.required' => 'The date field is required.',
        'advance-loan-type.required' => 'The advance type field is required.',
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
        $sifaLoan = $advanceTypes->where('advancetype', 'SIFA LOAN')->first();

        // Check if 'SIFA LOAN' exists before accessing the ID
        if ($sifaLoan) {
            $sifaLoanId = $sifaLoan->id;
        } else {
            $sifaLoanId = null;  // Handle if 'SIFA LOAN' is not found
        }


        return view('advance-loan.apply.create', compact('advanceTypes', 'sifaLoanId'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // Validate the request using your defined rules and messages
        $this->validate($request, $this->rules, $this->messages);

        // Create or fetch the existing advance application instance
        $advanceApplication = new AdvanceApplication();
        // Optionally fetch existing application if updating (logic can be added here)

        // Initialize the attachment variable
        $attachment = "";

        try {
            // Check if an attachment file was uploaded
            if ($request->hasFile('attachment')) {
                // Delete existing attachment if it exists
                if ($advanceApplication->attachment) {
                    delete_image($advanceApplication->attachment); // Deletes the old attachment from storage
                }

                // Store the file and generate a unique filename
                $file = $request->file('attachment');
                // $attachment = $file->storeAs('attachment', time() . '_' . $file->getClientOriginalName(), 'public');
                $attachment = uploadImageToDirectory($file, $this->attachmentPath);
            } elseif ($advanceApplication->attachment) {
                // Retain the existing attachment if no new one is uploaded
                $attachment = $advanceApplication->attachment;
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
        $advanceApplication->advance_type = $request->input('advance-loan-type');
        $advanceApplication->mas_employee_id = $request->mas_employee_id;
        $advanceApplication->mode_of_travel = $request->mode_of_travel ?? null;
        $advanceApplication->from_location = $request->from_location ?? null;
        $advanceApplication->to_location = $request->to_location ?? null;
        $advanceApplication->from_date = $request->from_date ?? null;
        $advanceApplication->to_date = $request->to_date ?? null;
        $advanceApplication->amount = $request->amount ?? null;
        $advanceApplication->purpose = $request->purpose ?? null;
        $advanceApplication->attachment = $attachment; // Store attachment path
        $advanceApplication->interest_rate = $request->interest_rate ?? null;
        $advanceApplication->total_amount = $request->total_amount ?? null;
        $advanceApplication->no_of_emi = $request->no_of_emi ?? null;
        $advanceApplication->monthly_emi_amount = $request->monthly_emi_amount ?? null;
        $advanceApplication->deduction_from_period = $request->deduction_from_period ?? null;
        $advanceApplication->item_type = $request->item_type ?? null;
        $advanceApplication->created_by = auth()->user()->id;
        $advanceApplication->updated_by = auth()->user()->id;

        // Save the advance application record to the database
        $advanceApplication->save();

        // Redirect to the advance loan index with a success message
        return redirect()->route('apply.index')->with('success', 'Advance application created successfully!');
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

        // Fetch the advance loan application details
        $advance = AdvanceApplication::with('advanceType')->findOrFail($id);

        return view('advance-loan.apply.show', compact('advance'));
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
