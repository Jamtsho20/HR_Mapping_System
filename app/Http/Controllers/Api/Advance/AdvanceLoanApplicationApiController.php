<?php

namespace App\Http\Controllers\Api\Advance;

use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\MasAdvanceTypes;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvanceLoanApplicationApiController extends Controller
{
    use JsonResponseTrait;
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

    public function index()
    {
        try {
            $applications = AdvanceApplication::all();
            return $this->successResponse($applications, 'Advance applications retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve applications', 500);
        }
        
    }

}
    