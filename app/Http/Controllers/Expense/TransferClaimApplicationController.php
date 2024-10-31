<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\MasTransferClaim;
use App\Models\TransferClaimApplication;
use Illuminate\Http\Request;

class TransferClaimApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:expense/transfer-claim,view')->only('index');
        $this->middleware('permission:expense/transfer-claim,create')->only('store');
        $this->middleware('permission:expense/transfer-claim,edit')->only('update');
        $this->middleware('permission:expense/transfer-claim,delete')->only('destroy');
    }

    protected $rules = [
        'transfer_cliam' => 'required',
        'current_location' => 'required',
        'new_location' => 'required',
        'distance_travelled' => 'required_if:transfer_cliam,Carriage Charge',
        'amount_claimed' => 'required',
    ];

    protected $messages = [];

    public function index(Request $request)
    {
        $privileges = $request->instance();
               
        return view('expense.transfer-claim.index', compact( 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $travels=MasTransferClaim::get();
        return view('expense.transfer-claim.create',compact('travels'));
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
        $transfer = new TransferClaimApplication();
        $transfer->transfer_cliam = $request->transfer_cliam;
        $transfer->current_location = $request->current_location;
        $transfer->new_location = $request->new_location;
        $transfer->distance_travelled = $request->distance_travelled;
        $transfer->amount_claimed = $request->amount_claimed;
        $transfer->status = 1;
        $transfer->save();

        return redirect('expense/transfer-claim')->with('msg_success', 'Dzongkhag created successfully');
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
