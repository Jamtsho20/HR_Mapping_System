<?php

namespace App\Http\Controllers\Api\v1\TravelAuthorization;

use App\Http\Controllers\Controller;
use App\Models\ApplicationHistory;
use App\Models\TravelAuthorizationApplication;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;

class TravelAuthorizationApprovalController extends Controller
{
    use JsonResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        try {
            $privileges = $request->instance();
            $user = auth()->user();
            // $historyData = ApplicationHistory::whereHas('application', function ($query) {
            //     $query->where('application_type', 'App\Models\TravelAuthorizationApplication'); // Assuming you store this class in 'application_type' column
            // })->where('approver_emp_id', $user->id)
            //   ->get();
            $travelAuthorizations = TravelAuthorizationApplication::with('employee:id,name,username', 'travelType:id,name')->whereHas('histories', function ($query) use ($user) {
                $query
                    ->where('application_type', \App\Models\TravelAuthorizationApplication::class)
                    ->where('approver_emp_id', $user->id);
            })
                ->whereNotIn('status', [-1, 3])
                ->filter($request, false) //sent onesOenRecord parameter as flase as it need to fetch all despites of authenticated user
                ->orderBy('created_at')
                ->get();
            return $this->successResponse($travelAuthorizations);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Failed to retrieve applications', 500);
        }

    }

    public function show($id){
        try {
            $travelAuthorization = TravelAuthorizationApplication::findOrfail($id);
            $empDetails = empDetails($travelAuthorization->created_by);
            return response()->json(['travelAuthorization' => $travelAuthorization, 'empDetails' => $empDetails]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }
}
