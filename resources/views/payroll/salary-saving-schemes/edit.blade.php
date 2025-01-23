@extends('layouts.app')
@section('page-title', 'Loan / Device Emi')
@section('buttons')
<a href="{{ route('salary-saving-schemes.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to List</a>
@endsection
@section('content')

<form action="{{ route('salary-saving-schemes.update', $salarySaving->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="pay_head_id">Deduction <span class="text-danger">*</span></label>
                    <select name="pay_head_id" id="pay_head_id" class="form-control" required="required">
                        @foreach ($payHeads as $payHead)
                            <option value="{{ $payHead->id }}" >{{ $payHead->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="employee_id">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" id="employee_id" class="form-control select2" required="required">
                        <option value="">Select</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $employee->id == $salarySaving->employee_id ? 'selected' : ''}}>{{ $employee->emp_id_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="policy_number">Policy Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="policy_number" value="{{ $salarySaving->policy_number }}" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="amount">Amount<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="amount" step="any" value="{{ $salarySaving->amount }}" required="required">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ route('salary-saving-schemes.index') }}" class="btn btn-danger"><i class="fa fa-undo"></i>
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