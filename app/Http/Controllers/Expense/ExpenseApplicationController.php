<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\ExpenseApplication;
use App\Models\MasExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:expense/apply-expense,view')->only('index');
        $this->middleware('permission:expense/apply-expense,create')->only('store');
        $this->middleware('permission:expense/apply-expense,edit')->only('update');
        $this->middleware('permission:expense/apply-expense,delete')->only('destroy');
    }
    private $filePath = 'images/files/';

    public function index(Request $request)
    {
        $privileges = $request->instance();

        $expenseApplication = ExpenseApplication::filter($request)->paginate(30);

        return view('expense.apply.index', compact('expenseApplication', 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expenses = MasExpenseType::all();
        return view('expense.apply.create', compact('expenses'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->all());
        try {


            DB::beginTransaction();

            if (isset($doc['file'])) {
                // Remove old file if exists
                if ($request->file) {
                    delete_image($request->file);
                }
                $file = uploadImageToDirectory($doc['file'], $this->filePath);
            } else {
                $file = $request->file;
            }

            $expenseApplication = ExpenseApplication::create([
                'mas_employee_id' => loggedInUser(),
                'mas_expense_type_id' => $request->mas_expense_type_id,
                'date' => $request->date,
                'expense_amount' => $request->expense_amount,
                'description' => $request->description,
                'file' => $file,
                'travel_type' => $request->travel_type,
                'travel_mode' => $request->travel_mode,
                'travel_from_date' => $request->travel_from_date,
                'travel_to_date' => $request->travel_to_date,
                'travel_from' => $request->travel_from,
                'travel_to' => $request->travel_to,
                'status' => $request->status ?? 1,
            ]);

            // Create a history record
            // $leaveApplication->histories()->create([
            //     'level' => 'Test Level',
            //     'status' => 1,
            //     'remarks' => $request->remarks,
            //     'created_by' => loggedInUser(),
            // ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
        }

        return redirect('expense/apply-expense')->with('msg_success', 'Expense has been applied successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
