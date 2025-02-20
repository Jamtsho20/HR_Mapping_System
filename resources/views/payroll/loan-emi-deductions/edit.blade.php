@extends('layouts.app')
@section('page-title', 'Loan / Device Emi')
@section('buttons')
    <a href="{{ route('loan-emi-deductions.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to List</a>
@endsection
@section('content')

    <form action="{{ route('loan-emi-deductions.update', $loanEMIDeduction->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="mas_pay_head_id">Deduction <span class="text-danger">*</span></label>
                        <select name="mas_pay_head_id" id="mas_pay_head_id" class="form-control" required="required">
                            <option value="{{ $loanEMIDeduction->payHead->id }}">{{ $loanEMIDeduction->payHead->name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="mas_employee_id">Employee <span class="text-danger">*</span></label>
                        <select name="mas_employee_id" id="mas_employee_id" class="form-control" required="required">
                            <option value="{{ $loanEMIDeduction->employee->id }}">
                                {{ $loanEMIDeduction->employee->emp_id_name }}
                                ({{ $loanEMIDeduction->employee->employee_id }})</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="start_date">Start date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="start_date"
                            value="{{ old('start_date', $loanEMIDeduction->start_date) }}" required="required">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="loan_type_id">Loan Type <span class="text-danger">*</span></label>
                        <select name="loan_type_id" id="loan_type_id" class="form-control select2" required="required">
                            <option value="">Select</option>
                            @foreach ($loanTypes as $id => $name)
                                <option value="{{ $id }}"
                                    {{ old('loan_type_id', $loanEMIDeduction->loan_type_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="loan_number">Loan Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="loan_number" required="required"
                            value="{{ $loanEMIDeduction->loan_number }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="amount">EMI <span class="text-danger">*</span></label>
                        <input type="number" step="any" class="form-control" name="amount"
                            value="{{ $loanEMIDeduction->amount }}" required="required">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="custom-switch">
                            <input type="checkbox" name="recurring" id="recurring" class="custom-switch-input"
                                value="1" {{ old('recurring', $loanEMIDeduction->recurring) ? 'checked' : '' }}>
                            <span class="custom-switch-indicator"></span> &nbsp;<span>Recurring</span> <span
                                class="text-danger">*</span>
                        </label>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="custom-switch">
                            <input type="checkbox" name="paid_off_early" class="custom-switch-input" value="1"
                                {{ old('paid_off_early', $loanEMIDeduction->is_paid_off) ? 'checked' : '' }}>
                            <span class="custom-switch-indicator"></span> &nbsp;<span>Paid off early</span>
                        </label>
                    </div>
                    <div class="form-group col-md-6" id="recurring_months_container"
                        style="{{ old('recurring', $loanEMIDeduction->recurring) ? '' : 'display: none;' }}">
                        <label for="recurring_months">Recurring no. of months <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="recurring_months" id="recurring_months"
                            value="{{ old('recurring_months', $loanEMIDeduction->recurring_months) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="2">{{ old('remarks', $loanEMIDeduction->remarks) }}</textarea>
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

            $('#recurring').trigger('change');
        });
    </script>
@endpush
