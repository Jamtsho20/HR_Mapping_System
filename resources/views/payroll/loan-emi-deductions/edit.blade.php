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
                            ({{ $loanEMIDeduction->employee->employee_id }})
                        </option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="employee_cid">CID No</label>
                    <input type="text" id="employee_cid" class="form-control" value="{{ $loanEMIDeduction->employee->cid_no ?? '' }}" readonly>
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
                            {{ $name }}
                        </option>
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
                    @if(isset($outstandingAmount) && $outstandingAmount > 0)
                    <div class="">
                        <p class="info-green">
                            <strong>Outstanding Amount:</strong><br>
                            Closing Balance ({{ \Carbon\Carbon::parse($latestRepayment->month)->format('F Y') }}): Nu. {{ number_format($remainingPrincipal, 2) }}<br>
                            Accrued Interest (till {{ now()->format('d M, Y') }}): Nu. {{ number_format($accruedInterest, 2) }}<br>
                            <strong>Total Outstanding to be Paid:</strong> Nu. {{ number_format($outstandingAmount, 2) }}<br><br>
                        </p>
                        <input type="hidden" id="remaining_outstanding" class="form-control info-green p-3 pt-0 fw-bold" value="{{ number_format($outstandingAmount, 2, '.', '') }}" readonly />
                    </div>
                    @endif
                    <input type="number" step="any" class="form-control" name="amount"
                        value="{{ $loanEMIDeduction->amount }}" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="branch_code">Branch Code </label>
                    <input type="text" class="form-control" name="branch_code" value="{{ $loanEMIDeduction->branch_code }}">
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
    const employeeCIDs = @json($employees->pluck('cid_no', 'id'));

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

        // Set initial CID on load
        const selectedEmpId = $('#mas_employee_id').val();
        if (selectedEmpId && employeeCIDs[selectedEmpId]) {
            $('#employee_cid').val(employeeCIDs[selectedEmpId]);
        }

        // Update CID on change (if employee dropdown is editable in future)
        $('#mas_employee_id').on('change', function () {
            const empId = $(this).val();
            $('#employee_cid').val(employeeCIDs[empId] ?? '');
        });
    });
</script>
@endpush