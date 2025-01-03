<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\ExpenseApplication;
use App\Models\MasExpenseType;
use App\Services\ApprovalService;
use Illuminate\Http\Request;

class ExpenseApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:expense/approval,view')->only('index');
        $this->middleware('permission:expense/approval,create')->only('store');
        $this->middleware('permission:expense/approval,edit')->only('update');
        $this->middleware('permission:expense/approval,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $headers = MasExpenseType::whereIn('id', [2, 3, 4])->get();
        $empIdName = LoggedInUserEmpIdName();
        $user = auth()->user();

        $models = [
            2 => \App\Models\ExpenseApplication::class,
            3 => \App\Models\DsaClaimApplication::class,
            4 => \App\Models\TransferClaimApplication::class,
        ];

        $results = collect();

        foreach ($models as $key => $modelClass) {
            $data = $modelClass::whereHas('histories', function ($query) use ($user, $modelClass) {
                $query->where('approver_emp_id', $user->id)
                    ->where('application_type', $modelClass);
            })
                ->whereNotIn('status', [-1, 3])
                ->filter($request, false)
                ->orderBy('created_at')
                ->paginate(config('global.pagination'))
                ->withQueryString();

            $results->put($key, $data);
        }

        $expenses = $results->get(2);
        $dsaclaims = $results->get(3);
        $transferclaims = $results->get(4);

        return view('expense.approval.index', compact('privileges', 'headers', 'expenses', 'dsaclaims', 'transferclaims'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $expense = ExpenseApplication::findOrfail($id);
        $empDetails = empDetails($expense->created_by);
        $approvalDetail = getApplicationLogs(\App\Models\ExpenseApplication::class, $expense->id);

        return view('expense.approval.show', compact('expense', 'empDetails','approvalDetail'));
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
