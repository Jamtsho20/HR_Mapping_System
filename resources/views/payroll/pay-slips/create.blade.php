@extends('layouts.app')
@section('page-title', 'Pay Slip')
@section('buttons')
    <a href="{{ route('pay-slips.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Payslip
        List</a>
@endsection
@section('content')
    <form action="{{ route('pay-slips.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="form-group col-md-6">
                    <label for="for_month">For Month <span class="text-danger">*</span></label>
                    <input type="month" class="form-control" name="for_month" required="required">
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                    <a href="{{ url('payroll/pay-slips') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
                </div>
            </div>
        </div>
    </form>

    @include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush
