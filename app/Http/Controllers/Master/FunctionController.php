<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FunctionModel;
use App\Models\MasCompany;
use Illuminate\Support\Facades\DB;


class FunctionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/functions,view')->only('index');
        $this->middleware('permission:master/functions,create')->only('store');
        $this->middleware('permission:master/functions,edit')->only('update');
        $this->middleware('permission:master/functions,delete')->only('destroy');
    }

    /**
     * Display a listing of functions
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $privileges = $request->instance();

        // Check if user is admin
        $isAdmin = $user->roles()->where('name', 'Administrator')->exists();

        $query = FunctionModel::orderBy('name');

        // Role-based function filtering
        if (!$isAdmin) {
            $companyId = $user->empJob?->mas_company_id;

            if ($companyId) {
                $query->where('mas_company_id', $companyId);
            }
        }

        // Companies for dropdown
        $companies = MasCompany::where('status', 'active')
            ->when(!$isAdmin, function ($q) use ($user) {
                $q->where('id', $user->empJob?->mas_company_id);
            })
            ->orderBy('name')
            ->get();

        // Filters
        if ($request->filled('function')) {
            $query->where('name', 'like', '%' . $request->function . '%');
        }
        if ($request->filled('company')) {
            $query->where('mas_company_id', $request->company);
        }
        if ($request->filled('designation')) {
            $query->whereHas('designations', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->designation . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $functions = $query->paginate(config('global.pagination'));

        return view(
            'masters.functions.index',
            compact('functions', 'privileges', 'companies')
        );
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $privileges = $request->instance();
        $companies = \App\Models\MasCompany::where('status', 'active')->orderBy('name')->get();
        return view(
            'masters.functions.create',
            compact('privileges', 'companies')
        );
    }

    /**
     * Store new function
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'mas_company_id'    => 'required|exists:mas_company,id',
            'approved_strength' => 'required|integer|min:0',
            'current_strength'  => 'nullable|integer|min:0',
            'status'            => 'required|in:active,inactive',
            'designations.*.name'   => 'required|string|max:255',
            'designations.*.status' => 'required|in:active,inactive',
        ]);

        DB::transaction(function () use ($request) {

            //  Create Function
            $function = FunctionModel::create([
                'name'              => $request->name,
                'description'       => $request->description,
                'mas_company_id'    => $request->mas_company_id,
                'approved_strength' => $request->approved_strength,
                'current_strength'  => $request->current_strength ?? 0,
                'status'            => $request->status,
            ]);

            // 2Create Designations linked to this Function
            if ($request->has('designations')) {
                foreach ($request->designations as $designation) {
                    $function->designations()->create([
                        'name'   => $designation['name'],
                        'status' => $designation['status'],
                    ]);
                }
            }
        });

        return redirect('master/functions')
            ->with('msg_success', 'Function and Designations created successfully');
    }

    /**
     * Edit function
     */
    public function edit($id)
    {
        $function = FunctionModel::findOrFail($id);
        $companies = \App\Models\MasCompany::where('status', 'active')->orderBy('name')->get();

        return view(
            'masters.functions.edit',
            compact('function', 'companies')
        );
    }
    /**
     * Update function
     */

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'mas_company_id'      => 'required|exists:mas_company,id',
            'approved_strength'   => 'required|integer|min:0',
            'current_strength'    => 'nullable|integer|min:0',
            'status'              => 'required|in:active,inactive',
            'designations'        => 'nullable|array',
            'designations.*.name' => 'required|string|max:255',
            'designations.*.status' => 'required|in:active,inactive',
        ]);

        $function = FunctionModel::findOrFail($id);

        // Update function fields
        $function->update($request->only([
            'name',
            'description',
            'mas_company_id',
            'approved_strength',
            'current_strength',
            'status'
        ]));

        // Handle deleted designations
        if ($request->filled('deleted_designations')) {
            $deletedIds = explode(',', $request->deleted_designations);
            $function->designations()->whereIn('id', $deletedIds)->delete();
        }

        // Handle designations (update or create)
        if ($request->has('designations')) {
            foreach ($request->designations as $des) {
                if (isset($des['id']) && $des['id']) {
                    // Existing designation, update it
                    $designation = $function->designations()->find($des['id']);
                    if ($designation) {
                        $designation->update([
                            'name'   => $des['name'],
                            'status' => $des['status'],
                        ]);
                    }
                } else {
                    // New designation, create it
                    $function->designations()->create([
                        'name'   => $des['name'],
                        'status' => $des['status'],
                    ]);
                }
            }
        }

        return redirect()->route('functions.index')
            ->with('msg_success', 'Function and designations updated successfully');
    }

    /**
     * Remove function
     */
    public function destroy($id)
    {
        try {
            FunctionModel::findOrFail($id)->delete();

            return back()->with(
                'msg_success',
                'Function deleted successfully'
            );
        } catch (\Exception $e) {
            return back()->with(
                'msg_error',
                'Function cannot be deleted as it is used in other modules.'
            );
        }
    }
}
