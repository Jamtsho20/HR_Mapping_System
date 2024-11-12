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
    private $filePath = 'images/files/';

    protected $rules = [
        'transfer_claim' => 'required',
        'current_location' => 'required',
        'new_location' => 'required',
        'distance_travelled' => 'required_if:transfer_claim,Carriage Charge',
        'amount_claimed' => 'required',
    ];

    protected $messages = [];

    public function index(Request $request)
    {
        $privileges = $request->instance();
        $empIdName = LoggedInUserEmpIdName();
        $user = loggedInUser();

        $transferClaims = TransferClaimApplication::where('created_by', $user)->get();

        return view('expense.transfer-claim.index', compact('privileges', 'transferClaims', 'empIdName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empIdName = LoggedInUserEmpIdName();
        $trasnferClaim = MasTransferClaim::get();
        return view('expense.transfer-claim.create', compact('transfer_claim', 'empIdName'));
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

        if ($request->hasFile('attachment')) {
            // Upload file and get the file path
            $attachmentPath = uploadImageToDirectory($request->file('attachment'), $this->filePath);

            // Store it as a JSON array
            $attachment = json_encode([$attachmentPath]);
        } else {
            $attachment = $transfer ? $transfer->attachment : json_encode([]); // Empty JSON array if null
        }


        $transfer->transfer_claim = $request->transfer_claim;
        $transfer->current_location = $request->current_location;
        $transfer->new_location = $request->new_location;
        $transfer->distance_travelled = $request->distance_travelled;
        $transfer->amount_claimed = $request->amount_claimed;
        $transfer->attachment = $attachment;
        $transfer->status = 1;
        $transfer->save();

        return redirect('expense/transfer-claim')->with('msg_success', 'Transfer Claim applied successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empIdName = LoggedInUserEmpIdName();
        $transfer = TransferClaimApplication::findOrfail($id);

        return view('expense.transfer-claim.show', compact('transfer', 'empIdName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empIdName = LoggedInUserEmpIdName();
        $trasnferClaim = MasTransferClaim::get();
        $transfer = TransferClaimApplication::findOrfail($id);

        return view('expense.transfer-claim.edit', compact('transfer', 'empIdName', 'trasnferClaim'));
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
        $transfer = TransferClaimApplication::findOrFail($id);

        if ($request->hasFile('attachment')) {
            // Upload file and get the file path
            $attachmentPath = uploadImageToDirectory($request->file('attachment'), $this->filePath);

            // Store it as a JSON array
            $attachment = json_encode([$attachmentPath]);
        } else {
            $attachment = $transfer ? $transfer->attachment : json_encode([]); // Empty JSON array if null
        }

        $transfer->transfer_claim = $request->transfer_claim;
        $transfer->current_location = $request->current_location;
        $transfer->new_location = $request->new_location;
        $transfer->distance_travelled = $request->distance_travelled;
        $transfer->amount_claimed = $request->amount_claimed;
        $transfer->attachment = $attachment ?? $transfer->attachment;
        $transfer->save();

        return redirect('expense/transfer-claim')->with('msg_success', 'Transfer Claim Updated successfully');
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
            TransferClaimApplication::findOrFail($id)->delete();

            return back()->with('msg_success', 'Transfer Claim Application has been deleted');
        } catch (\Exception $e) {
            return back()->with('msg_error', 'Transfer Claim Application cannot be deleted as it is used by other modules.');
        }
    }
}
