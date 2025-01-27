<?php

namespace App\Http\Controllers\Advance;

use App\Http\Controllers\Controller;
use App\Models\MasAdvanceTypes;
use Illuminate\Http\Request;

class AdvanceTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:advance-loan/types,view')->only('index');
        $this->middleware('permission:advance-loan/types,create')->only('store');
        $this->middleware('permission:advance-loan/types,edit')->only('update');
        $this->middleware('permission:advance-loan/types,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $advanceTypes = MasAdvanceTypes::orderBy('name')->paginate(10);  // Fetch advance types with pagination

        return view('advance-loan.types.index', compact('advanceTypes', 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('advance-loan.types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'status' => 'required|boolean'
        ]);

        $advanceType = new MasAdvanceTypes();
        $advanceType->name = $request->name;
        $advanceType->code = $request->code;
        $advanceType->status = $request->status;
        $advanceType->save();

        return redirect('advance-loan/types')->with('msg_success', 'Advance type created successfully');
    }

    public function edit($id)
    {
        $advanceType = MasAdvanceTypes::findOrFail($id);
        return view('advance-loan.types.edit', compact('advanceType'));

    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'status' => 'required|boolean'
        ]);

        $advanceType = MasAdvanceTypes::findOrFail($id);
        $advanceType->name = $request->name;
        $advanceType->code = $request->code;
        $advanceType->status = $request->status;
        $advanceType->save();

        return redirect('advance-loan/types')->with('msg_success', 'Advance type updated successfully');
    }
    public function destroy($id)
    {
        try {
            MasAdvanceTypes::findOrFail($id)->delete();

            return back()->with('msg_success', 'Advance type has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Advance type cannot be deleted as it is used by other modules.');
        }
    }
}
