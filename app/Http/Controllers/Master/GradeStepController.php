<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasGrade;
use DB;

class GradeStepController extends Controller
{
    private $rules = [
        'grade_name' => 'required',
        'grade_steps.*.step_name' => 'required'
    ];

    private $messages = [
        'grade_steps.*.step_name.required' => 'Step name field is required',
    ];

    public function __construct()
    {
        $this->middleware('permission:master/grade-steps,view')->only('index');
        $this->middleware('permission:master/grade-steps,create')->only(['create', 'store']);
        $this->middleware('permission:master/grade-steps,edit')->only(['edit', 'update']);
        $this->middleware('permission:master/grade-steps,delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $grades = MasGrade::filter($request)->orderBy('name')->with('gradeSteps')->paginate('30')->withQueryString();

        return view('masters.grade-steps.index', compact('grades', 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.grade-steps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);

        DB::transaction(function () use ($request) {

            $grade = new MasGrade;
            $grade->name = $request->grade_name;
            $grade->save();

            $gradeSteps = [];
            foreach($request->grade_steps as $key => $value){
                $gradeSteps[] = [
                    'name' => $value['step_name'],
                    'starting_salary' => $value['starting_salary'],
                    'ending_salary' => $value['ending_salary'],
                    'increment' => $value['increment']
                ];
            }

            $grade->gradeSteps()->createMany($gradeSteps);

        });

        return back()->with('msg_success', 'Grade and steps have been created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $grade = MasGrade::with('gradeSteps')->findOrFail($id);

        return view('masters.grade-steps.edit', compact('grade'));
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
        $this->validate($request, $this->rules, $this->messages);

        DB::transaction(function () use ($request, $id) {

            $grade = MasGrade::findOrFail($id);
            $grade->name = $request->grade_name;
            $grade->save();

            $gradeSteps = [];
            foreach($request->grade_steps as $key => $value){
                $grade->gradeSteps()->updateOrCreate([
                    'id' => $value['step_id']
                ],
                [
                    'name' => $value['step_name'],
                    'starting_salary' => $value['starting_salary'],
                    'ending_salary' => $value['ending_salary'],
                    'increment' => $value['increment']
                ]);
            }
        });

        return redirect('master/grade-steps')->with('msg_success', 'Grade and steps have been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            MasGrade::findOrFail($id)->delete();

            return back()->with('msg_success', 'Grade and steps have been deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'You cannot delete this grade and steps, as it is associated with other data in the system.');
        }
    }
}
