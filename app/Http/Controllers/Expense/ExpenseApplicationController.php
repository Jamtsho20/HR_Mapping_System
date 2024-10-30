<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\ExpenseApplication;
use App\Models\MasEmployeeJob;
use App\Models\MasExpensePolicy;
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

    protected function rules(Request $request)
    {
        $rules = [
            'expense_type' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required',
        ];

        // Add conveyance-specific rules if the expense type is CONVEYANCE_EXPENSE
        if ($request->input('expense_type') == CONVEYANCE_EXPENSE) {
            $rules['travel_type'] = 'required';
            $rules['mode_of_travel'] = 'required';
            $rules['travel_from_date'] = 'required|date';
            $rules['travel_to_date'] = 'required|date|after_or_equal:travel_from_date';
            $rules['travel_from'] = 'required';
            $rules['travel_to'] = 'required';
        }

        return $rules;
    }


    protected $messages = [
        'travel_type.required_if' => 'Travel type is required for the selected expense type.',
        'mode_of_travel.required_if' => 'Mode of travel is required for the selected expense type.',
        'travel_from_date.required_if' => 'Travel from date is required for the selected expense type.',
        'travel_to_date.required_if' => 'Travel to date is required for the selected expense type.',
        'travel_to_date.date' => 'Travel to date must be equal or greater than travel from date for selected expense type.',
        'travel_from.required_if' => 'Travel from is required for the selected expense type.',
        'travel_to.required_if' => 'Travel to is required for the selected expense type.',
    ];

    private $attachmentPath = 'images/expenses/';

    public function index(Request $request)
    {
        $privileges = $request->instance();

        $expenseApplication = ExpenseApplication::filter($request) ->createdBy() ->paginate(30);

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
        $result = $this->handleExpenseApplication($request);

        // If $result is a RedirectResponse, return it immediately
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $result;
        }

         $validatedData = $request->validate($this->rules($request));
        try {
            DB::beginTransaction();

            $expenseApplication = ExpenseApplication::create([
                // 'mas_employee_id' => loggedInUser(),
                'mas_expense_type_id' => $request->expense_type,
                'date' => $request->date,
                'expense_amount' => $request->amount,
                'description' => $request->description,
                'file' => $result['file'],
                'travel_type' => $request->travel_type,
                'travel_mode' => $request->mode_of_travel,
                'travel_from_date' => $request->travel_from_date,
                'travel_to_date' => $request->travel_to_date,
                'travel_from' => $request->travel_from,
                'travel_to' => $request->travel_to,
                'status' => $request->status ?? 1,
            ]);

            // Create a history record
            $expenseApplication->histories()->create([
                'level' => 'Test Level',
                'status' => 1,
                'remarks' => $request->remarks,
                'created_by' => loggedInUser(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
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
        $expense = ExpenseApplication::findOrfail($id);
        return view('expense.apply.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expenses = MasExpenseType::all();
        $expenseApplication = ExpenseApplication::findOrfail($id);
        return view('expense.apply.edit', compact('expenses', 'expenseApplication'));
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
        $expenseApplication = ExpenseApplication::findOrFail($id);
        
        $result = $this->handleExpenseApplication($request, $expenseApplication);
        // If $result is a RedirectResponse, return it immediately
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $result;
        }

        $validatedData = $request->validate($this->rules($request));
        try {
            DB::beginTransaction();
            $expenseApplication->update([
                // 'mas_employee_id' => $expenseApplication->mas_employee_id,
                'mas_expense_type_id' => $request->expense_type,
                'date' => $request->date,
                'expense_amount' => $request->amount,
                'description' => $request->description,
                'file' => $result['attachment'] ?? $expenseApplication->file,
                'travel_type' => $request->travel_type,
                'travel_mode' => $request->mode_of_travel,
                'travel_from_date' => $request->travel_from_date,
                'travel_to_date' => $request->travel_to_date,
                'travel_from' => $request->travel_from,
                'travel_to' => $request->travel_to,
                'status' => $request->status ?? 1,
            ]);        

            // Create a history record
            $expenseApplication->histories()->create([
                'level' => 'Test Level',
                'status' => $expenseApplication->status,
                'remarks' => $request->remarks,
                'created_by' => $expenseApplication->created_by,
                'updated_by' => loggedInUser()
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', $e->getMessage());
            // return back()->withInput()->with('msg_error', GENERAL_ERR_MSG);
        }

        return redirect('expense/apply-expense')->with('msg_success', 'Expense application has been updated successfully!.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        {
            try {
                ExpenseApplication::findOrFail($id)->delete();
    
                return back()->with('msg_success', 'Expense Applicaton has been deleted');
            } catch (\Exception $e) {
                return back()->with('msg_error', 'Expense Applicaton cannot be deleted as it is used by other modules.');
            }
        }
    }

    private function handleExpenseApplication(Request $request, $expenseApplication = null)
    { //common function to handle store and update of expense
        /// query to fetch employee grade step and region
        $empJobDetail = MasEmployeeJob::where('mas_employee_id', loggedInUser())->first();
        // dd($empJobDetail);
        $loggedInUserRegion = loggedInUserRegion(); //defined in helpers.php to get loggedInUser region id and name for common use
        //query to expense policy details
        $expensePolicy = MasExpensePolicy::with(['rateDefinition' => function ($query) use ($request, $empJobDetail, $loggedInUserRegion) {
            // Filter rateDefinition by travel type
            $query->where('travel_type', $request->travel_type ?? DOMESTIC_TRAVEL_TYPE)
                ->with(['expenseRateLimits' => function ($q) use ($empJobDetail, $loggedInUserRegion) {
                    // Filter expenseRateLimits by grade step and region
                    $q->where('mas_grade_step_id', $empJobDetail->mas_grade_step_id)
                        ->where('mas_region_id', $loggedInUserRegion[0]->region_id)
                        ->whereStatus(1);
                }]);
        }, 'policyEnforcement'])
            ->where('mas_expense_type_id', $request->expense_type)
            ->whereStatus(1)
            ->first();
        //check weather attachment is required while applying expense from expense policy                              
        $attachmentRequired = $expensePolicy && $expensePolicy->rateDefinition ? $expensePolicy->rateDefinition->attachment_required : 0;
        $expenseType = $expensePolicy && $expensePolicy->expenseType ? $expensePolicy->expenseType->name : '';

        //validation based on expense policy rate(at once how much amount user can apply based on region and grade steps)
        if ($expensePolicy && $expensePolicy->rateDefinition->expenseRateLimits[0]->limit_amount < $request->amount) {
            $limitAmount = $expensePolicy->rateDefinition->expenseRateLimits[0]->limit_amount;
            // $region = DB::table('mas_regions')->where('id', $expensePolicy->rateDefinition->expenseRateLimits[0]->mas_region_id)->first();
            return back()->withInput()->with('msg_error', 'You cannot apply more than Nu. ' . $limitAmount .  ' for expense type ' . $expenseType . ' from ' . $loggedInUserRegion[0]->region_name . ' region.');
        }

        // Handle file upload if required based on defined in leave policy
        $attachment = $expenseApplication ? $expenseApplication->attachment : '';
        // if ($attachmentRequired && !$attachment) {
        if ($attachmentRequired && !$attachment) {
            $this->validate($request, 
                ['file' => 'required|file|mimes:pdf,jpg,png|max:2048'],
                ['file.required' => 'The file is required. Please upload a file.']
            );
        }
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($expenseApplication && $expenseApplication->attachment && file_exists(public_path($this->attachmentPath . $expenseApplication->attachment))) {
                delete_image($this->attachmentPath . $expenseApplication->attachment); // Delete old attachment
            }
            $attachment = uploadImageToDirectory($file, $this->attachmentPath);
        }

        return [
            'file' => $attachment
        ];
    }
}
