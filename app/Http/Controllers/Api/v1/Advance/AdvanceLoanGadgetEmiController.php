<?php

namespace App\Http\Controllers\Api\V1\Advance;
use App\Http\Controllers\Controller;
use App\Models\AdvanceApplication;
use App\Models\User;

use App\Traits\JsonResponseTrait;

use Illuminate\Http\Request;

class AdvanceLoanGadgetEmiController extends Controller
{
    use JsonResponseTrait;
   

    public function index(Request $request, $username)
    {

       $formattedUsername = 'E' . sprintf('%05d', $username);
       $id = User::where('username', $formattedUsername)->value('id');
     
        try {

        $expenseApplications = AdvanceApplication::where('created_by', $id)->where('advance_type_id', GADGET_EMI)->get(['advance_no',  'total_amount', 'item_type']);
        
        
            return $this->successResponse($expenseApplications, 'Gadjet EMI applications retrieved successfully');

            } catch (\Exception $e) {
                return $this->errorResponse('Failed to retrieve applications', 500);
            }
    }

    public function getEmployees(Request $request){
        try{
        $employees = AdvanceApplication::where('status', 3)->get(['created_by'])->unique('created_by');
        
        return $this->successResponse($employees, 'Employees retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve employees', 500);
        }
    }
}


