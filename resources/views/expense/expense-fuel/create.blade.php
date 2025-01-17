@extends('layouts.app')
@section('page-title', 'Fuel Expense')
@section('content')

<form action="{{ route('expense-fuel.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employeename">Employee Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="employeename" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">Location</label>
                        <input type="text" class="form-control" name="location" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="date" class="form-control" name="Date" value="" required="required">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Vehicle Number</label>
                        <input type="text" class="form-control" name="vehiclenumber" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">Distance</label>
                        <input type="text" class="form-control" name="distance" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="">Attachment</label>
                        <input type="file" class="form-control" name="attachment" value="" required="required">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('expense/expense-fuel') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
</div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush