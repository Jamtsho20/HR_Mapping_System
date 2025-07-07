<?php

namespace App\Http\Controllers\Sifa;

use App\Http\Controllers\Controller;
use App\Models\SifaDocument;
use App\Models\SifaRegistration;
use App\Models\User;
use Illuminate\Http\Request;

class SifaRegisteredUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sifa/sifa-registered-user,view')->only('index', 'show');
        $this->middleware('permission:sifa/sifa-registered-user,create')->only('store');
        $this->middleware('permission:sifa/sifa-registered-user,edit')->only('update');
        $this->middleware('permission:sifa/sifa-registered-user,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $query = SifaRegistration::with(['employee.empJob.designation', 'employee.empJob.section', 'employee.empJob.department']);

        // if ($request->has('search') && $request->search) {
        //     $search = $request->search;
        //     $query->whereHas('employee', function ($q) use ($search) {
        //         $q->where('username', 'like', '%' . $search . '%');
        //     });
        // }
        $employees = User::select('id', 'name', 'employee_id', 'username', 'title')
            ->orderBy('name')
            ->get()
            ->map(function ($emp) {
                $emp->emp_id_name = $emp->employee_id . ' - ' . $emp->name;
                return $emp;
            });
        if ($request->filled('employee')) {
            $query->where('mas_employee_id', $request->employee);
        }


        $sifaRegistrations = $query->paginate(50); // Adjust pagination as needed
        return view('sifa.sifa-registered-user.index', compact('privileges', 'sifaRegistrations', 'employees'));
    }

    public function show($id, Request $request)
    {

        $sifaRegistration = SifaRegistration::with(['SifaNomination', 'SifaDependent', 'SifaDocument'])->findOrFail($id);
        $user = empDetails($sifaRegistration->created_by);
        $sifaDocuments = SifaDocument::where('sifa_registration_id', $id)->first();

        return view('sifa.sifa-registered-user.show', compact('user', 'sifaRegistration', 'sifaDocuments'));
    }
}
