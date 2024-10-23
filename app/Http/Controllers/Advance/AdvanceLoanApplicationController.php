<?php

namespace App\Http\Controllers\Advance;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasAdvanceTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        'attachment' => 'required_if:advance_type,' . ADVANCE_TO_STAFF . '|required_if:advance_type,' . DSA_ADVANCE . '|required_if:advance_type,' . ELECTRICITY_IMPREST_ADVANCE . 
                        '|required_if:advance_type,' . GADGET_EMI . '|required_if:advance_type,' . IMPREST_ADVANCE . '|required_if:advance_type,' . SALARY_ADVANCE . '|required_if:advance_type,' . SIFA_LOAN . '|mimes:jpg,png,pdf|max:2048',
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
        'attachment.required_if' => 'Attachment is required for the selected advance type and must be a valid file (jpg, png, pdf).',
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
        $advances = AdvanceApplication::with('advanceType')->paginate(10);
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
        $this->validate($request, $this->rules, $this->messages);
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
            $advanceApplication->histories()->create([
                'level' => 'Test Level',
                'status' => 1,
                'remarks' => $request->remark,
                'created_by' => loggedInUser(),
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
