<?php

namespace App\Http\Controllers\Api\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseApplication;
use App\Models\MasExpenseType;
use App\Services\ApprovalService;
use App\Traits\JsonResponseTrait;
use App\Models\DsaClaimApplication;
use App\Models\TransferClaimApplication;

class ExpenseApprovalController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {

        try {
            $empIdName = LoggedInUserEmpIdName();
            $user = auth()->user();

            $data = ExpenseApplication::with('type:id,name')->with('employee:id,name,username')->whereHas('histories', function ($query) use ($user) {
                $query->where('approver_emp_id', $user->id)
                    ->where('application_type', \App\Models\ExpenseApplication::class);
            })
                ->whereNotIn('status', [-1, 3])
                ->filter($request, false)
                ->orderBy('created_at')
                ->get();




            $expenses = $data;

        return response()->json([
            'success' => true,
            'message' => 'Expense applications retrieved successfully!',
            'data' =>  $expenses

        ]);

        // return $this->successResponse([$privileges, $headers, $expenses, $dsaclaims, $transferclaims], 'Expense applications retrieved successfully');
    } catch (\Exception $e) {
        return $this->errorResponse($e->getMessage(), 404);
    }
    }

    public function indexDsa(Request $request)
    {
        try {
            $empIdName = LoggedInUserEmpIdName();
            $user = auth()->user();

            $data = DSAClaimApplication::with('employee:id,name,username')->whereHas('histories', function ($query) use ($user) {
                $query->where('approver_emp_id', $user->id)
                    ->where('application_type', \App\Models\DSAClaimApplication::class);
            })
                ->whereNotIn('status', [-1, 3])
                ->filter($request, false)
                ->orderBy('created_at')
                ->get();




            $expenses = $data;

        return response()->json([
            'success' => true,
            'message' => 'DSA claim applications retrieved successfully!',
            'data' =>  $expenses

        ]);

        // return $this->successResponse([$privileges, $headers, $expenses, $dsaclaims, $transferclaims], 'Expense applications retrieved successfully');
    } catch (\Exception $e) {
        return $this->errorResponse($e->getMessage(), 404);
    }
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function indexTransfer(Request $request)
    {
        try {
            $empIdName = LoggedInUserEmpIdName();
            $user = auth()->user();

            $data = TransferClaimApplication::with('type:id,name')->with('employee:id,name,username')->whereHas('histories', function ($query) use ($user) {
                $query->where('approver_emp_id', $user->id)
                    ->where('application_type', \App\Models\TransferClaimApplication::class);
            })
                ->whereNotIn('status', [-1, 3])
                ->filter($request, false)
                ->orderBy('created_at')
                ->get();




            $expenses = $data;

        return response()->json([
            'success' => true,
            'message' => 'DSA claim applications retrieved successfully!',
            'data' =>  $expenses

        ]);

        // return $this->successResponse([$privileges, $headers, $expenses, $dsaclaims, $transferclaims], 'Expense applications retrieved successfully');
    } catch (\Exception $e) {
        return $this->errorResponse($e->getMessage(), 404);
    }
}
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $expense = ExpenseApplication::findOrfail($id);

            return $this->successResponse($expense, 'Expense application retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }


    }

    public function showDsa($id)
    {
        try{
            $dsa = DsaClaimApplication::with('dsaClaimDetails')->findOrfail($id);
            return $this->successResponse($dsa, 'DSA claim application retrieved successfully');
       }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function showTransferClaim($id)
    {
       try{
         $transfer = TransferClaimApplication::findOrfail($id);
         return $this->successResponse($transfer, 'Transfer claim application retrieved successfully');
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }

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
