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
   

    public function index(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return $this->errorResponse('Invalid ID format', 400);
        }
        try {

        $expenseApplications = AdvanceApplication::where('created_by', $id)->get(['advance_no',  'total_amount', 'item_type']);
        
        
            return $this->successResponse($expenseApplications, 'Expense applications retrieved successfully');

            } catch (\Exception $e) {
                return $this->errorResponse('Failed to retrieve applications', 500);
            }
    }

    public function getEmployees(Request $request){
        try{
        $employees = AdvanceApplication::where('status', 3)->get(['created_by']);
        
        return $this->successResponse($employees, 'Employees retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve employees', 500);
        }
    }
}


