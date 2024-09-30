<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\MasExpenseType;
use App\Models\MasGradeStep;
use App\Models\MasRegion;
use Illuminate\Http\Request;

class ExpensePolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:expense/expense-policy,view')->only('index', 'show');
        $this->middleware('permission:expense/expense-policy,create')->only('store');
        $this->middleware('permission:expense/expense-policy,edit')->only('update');
        $this->middleware('permission:expense/expense-policy,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();

        return view('expense.expense-policy.index', compact('privileges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expenses=MasExpenseType::get();
        $gradeSteps = MasGradeStep::get(['id', 'name']);
        $regions= MasRegion::get();

        return view('expense.expense-policy.create',compact('expenses','gradeSteps','regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
