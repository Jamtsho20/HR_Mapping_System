<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasCompany;
use App\Models\MasDzongkhag;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master/companies,view')->only('index');
        $this->middleware('permission:master/companies,create')->only('store');
        $this->middleware('permission:master/companies,edit')->only('update');
        $this->middleware('permission:master/companies,delete')->only('destroy');
    }
    public function create(Request $request)
    {
        $privileges = $request->instance();
        $dzongkhags = MasDzongkhag::orderBy('dzongkhag')->get();
        return view('masters.companies.create', compact('dzongkhags', 'privileges'));
    }


    /**
     * Display a listing of companies
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();

        $query = MasCompany::orderBy('name');

        // Apply filter if search query exists
        if ($request->filled('company')) {
            $query->where('name', 'like', '%' . $request->company . '%');
        }


        $companies = $query->paginate(config('global.pagination'));

        return view('masters.companies.index', compact('companies', 'privileges'));
    }


    /**
     * Store a newly created company
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:mas_company,code',
            'address'     => 'required|string',
            'description' => 'nullable|string',
        ]);

        MasCompany::create($request->only([
            'name',
            'code',
            'address',
            'description',
        ]));

        return redirect('master/companies')
            ->with('msg_success', 'Company created successfully');
    }


    /**
     * Edit company
     */
    public function edit($id)
    {
        $company = MasCompany::findOrFail($id);
        $dzongkhags = MasDzongkhag::orderBy('dzongkhag')->get();
        return view('masters.companies.edit', compact('company', 'dzongkhags'));
    }

    /**
     * Update company
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:mas_company,code,' . $id,
            'address'     => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $company = MasCompany::findOrFail($id);
        $company->update($request->only([
            'name',
            'code',
            'address',
            'description',
        ]));

        return redirect('master/companies')
            ->with('msg_success', 'Company updated successfully');
    }

    /**
     * Remove company
     */
    public function destroy($id)
    {
        try {
            MasCompany::findOrFail($id)->delete();

            return back()->with('msg_success', 'Company deleted successfully');
        } catch (\Exception $e) {
            return back()->with(
                'msg_error',
                'Company cannot be deleted as it is used in other modules.'
            );
        }
    }
}
