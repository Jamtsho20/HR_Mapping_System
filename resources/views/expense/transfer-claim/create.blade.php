@extends('layouts.app')
@section('page-title', 'Account Heads')
@section('content')

<form action="{{ route('transfer-claim.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employeeid">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="employeeid" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">Designation</label>
                        <input type="text" class="form-control" name="designation" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">Department</label>
                        <input type="text" class="form-control" name="department" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">Basic Pay</label>
                        <input type="text" class="form-control" name="basicpay" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="transferclaim">Transfer Claim</label>
                        <select name="type" id="transferclaim" class="form-control form-control-sm" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="1">Transfer Grant</option>
                            <option value="2">Carriage Charge</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Current Location</label>
                        <input type="text" class="form-control" name="currentlocation" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">New Location</label>
                        <input type="text" class="form-control" name="newlocation" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">Amount Claimed</label>
                        <input type="text" class="form-control" name="amtclaimed" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">Attachment</label>
                        <input type="file" class="form-control" name="attachment" value="" required="required">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('expense/transfer-claim') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
</div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush