<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\MasSite;
use App\Models\MasTransferType;
use App\Models\ReceivedSerial;
use App\Models\User;
use Illuminate\Http\Request;

class AssetTransferApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:asset/asset-transfer,view')->only('index', 'show');
        $this->middleware('permission:asset/asset-transfer,create')->only('store');
        $this->middleware('permission:asset/asset-transfer,edit')->only('update');
        $this->middleware('permission:asset/asset-transfer,delete')->only('destroy');
    }

    protected $rules = [
        'transfer_type' => 'required',
        'transfer_date' => 'required|date',
        'from_employee' => 'required',
        'to_employee' => 'required|different:from_employee',
        'from_site' => 'required',
        'to_site' => 'required',
        'reason_of_transfer' => 'required',
        'details.*.asset_no' => 'required',

    ];

    protected $messages = [

    ];

    public function index(Request $request)
    {
        $privileges = $request->instance();
      
        return view('asset.asset-transfer.index',compact('privileges'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = MasTransferType::whereStatus(1)->get(['id', 'name']);
        $employees = User::whereIsActive(1)->whereNotIn('employee_id', [0, 99999])->get();
        $sites = MasSite::get(['id', 'name']);
        $assetNos = ReceivedSerial::where('is_commissioned', 1)->get(['id', 'asset_serial_no']);
        return view('asset.asset-transfer.create', compact('employees', 'types', 'sites', 'assetNos'));
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
