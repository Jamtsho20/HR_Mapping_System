@extends('layouts.app')
@section('page-title', 'Loan / Device Emi')
@section('buttons')
<a href="{{ route('loan-emi-deductions.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Payslip
    List</a>
@endsection
@section('content')

<form action="{{ route('loan-emi-deductions.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="for_month">Deduction <span class="text-danger">*</span></label>
                    <select name="mas_pay_head_id" id="mas_pay_head_id" class="form-control" required="required">
                        <option value="">Select</option>
                        @foreach ($payHeads as $payHead)
                        <option value="{{ $payHead->id }}">{{ $payHead->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="mas_employee_id">Employee <span class="text-danger">*</span></label>
                    <select name="mas_employee_id" id="mas_employee_id" class="form-control" required="required">
                        <option value="">Select</option>
                        @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="start_date">Start date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}"
                        required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="loan_type">Loan Type <span class="text-danger">*</span></label>
                    <select name="loan_type" id="loan_type" class="form-control" required="required">
                        <option value="">Select</option>
                        @foreach (config('global.loan_type') as $type)
                        <option value="{{ $type}}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="loan_number">Loan Number <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="loan_number" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="amount">Amount <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="amount" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label class="custom-switch">
                        <input type="checkbox" name="recurring" id="recurring" class="custom-switch-input"
                            value="1">
                        <span class="custom-switch-indicator"></span> &nbsp;<span>Recurring</span> <span
                            class="text-danger">*</span>
                    </label>
                </div>
                <div class="form-group col-md-6">
                    <label class="custom-switch">
                        <input type="checkbox" name="paid_off_early" class="custom-switch-input" value="1">
                        <span class="custom-switch-indicator"></span> &nbsp;<span>Paid off early</span>
                    </label>
                </div>
                <div class="form-group col-md-6" id="recurring_months_container" style="display: none;">
                    <label for="recurring_months">Recurring no. of months <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="recurring_months" id="recurring_months">
                </div>
                <div class="form-group col-md-6">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" name="remarks" id="remarks" rows="2"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ route('loan-emi-deductions.index') }}" class="btn btn-danger"><i class="fa fa-undo"></i>
                    CANCEL</a>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
    $(document).ready(function() {
        $('#recurring').change(function() {
            if ($(this).is(':checked')) {
                $('#recurring_months_container').show();
                $('#recurring_months').attr('required', true);
            } else {
                $('#recurring_months_container').hide();
                $('#recurring_months').removeAttr('required');
            }
        });
    });
</script>
@endpush