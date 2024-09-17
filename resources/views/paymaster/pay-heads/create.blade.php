@extends('layouts.app')
@section('page-title', 'Create Pay Head')
@section('content')
<form action="{{ route('pay-heads.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payhead_type">Pay Head Type <span class="text-danger">*</span></label>
                        <select name="payhead_type" id="payhead_type" class="form-control" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="1">Allowance</option>
                            <option value="2">Deductions</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account_head_id">Account Head <span class="text-danger">*</span></label>
                        <select name="account_head_id" id="account_head_id" class="form-control" required>
                            <option value="" disabled selected>Select an option</option>
                            @foreach($accountHeads as $accountHead)
                            <option value="{{ $accountHead->id }}">{{ $accountHead->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" class="form-control"   >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="calculation_method">Calculation Method <span class="text-danger">*</span></label>
                        <select name="calculation_method" id="calculation_method" class="form-control" required>
                            <option value="" disabled selected >Select your option</option>
                            <option value="1">Actual Amount</option>
                            <option value="2">Division Method</option>
                            <option value="3">On Pay Slab</option>
                            <option value="4">On Pay Group</option>
                            <option value="5">Percentage Method</option>
                            <option value="6">By Formula</option>
                            <option value="7">Employee Wise</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="calculated_on_wrapper" style="display: none;">
                        <label for="calculated_on">Calculated On <span class="text-danger">*</span></label>
                        <select name="calculated_on" id="calculated_on" class="form-control">
                            <option value="" disabled selected >Select your option</option>
                            <option value="1">Basic Pay</option>
                            <option value="2">Gross Pay</option>
                            <option value="3">Net Pay</option>
                            <option value="4">PIT Net Pay</option>
                            <option value="5">Lumpsum</option>
                            <option value="6">Pay Scale Base Pay</option>
                            <option value="7">By Formula</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="pay_slab_wrapper" style="display: none;">
                        <label for="mas_pay_slab_id">Pay Slab <span class="text-danger">*</span></label>
                        <select name="mas_pay_slab_id" id="mas_pay_slab_id" class="form-control">
                            <option value="" disabled selected>Select Pay Slab</option>
                            @foreach($paySlabs as $paySlab)
                            <option value="{{ $paySlab->id }}">{{ $paySlab->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="pay_group_wrapper" style="display: none;">
                        <label for="mas_pay_group_id">Pay Group <span class="text-danger">*</span></label>
                        <select name="mas_pay_group_id" id="mas_pay_group_id" class="form-control">
                            <option value="" disabled selected>Select Pay Group</option>
                            @foreach($payGroups as $payGroup)
                            <option value="{{ $payGroup->id }}">{{ $payGroup->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="amount_wrapper" style="display: none;">
                        <label for="amount">Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control">
                    </div>

                    <div class="form-group" id="formula_wrapper" style="display: none;">
                        <label for="formula">Formula <span class="text-danger">*</span></label>
                        <textarea name="formula" id="formula" class="form-control"></textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('paymaster/pay-heads') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </div>
</form>

@include('paymaster.pay-heads.form')
<script>
       document.addEventListener('DOMContentLoaded', function() {
        const calculationMethodSelect = document.getElementById('calculation_method');
        const amountWrapper = document.getElementById('amount_wrapper');
        const calculatedOnWrapper = document.getElementById('calculated_on_wrapper');
        const paySlabWrapper = document.getElementById('pay_slab_wrapper');
        const payGroupWrapper = document.getElementById('pay_group_wrapper');
        const formulaWrapper = document.getElementById('formula_wrapper');
        const nameInput = document.getElementById('name');
        const codeInput = document.getElementById('code');
        function generateAbbreviation(name) {
            return name
                .split(' ')
                .map(word => word.charAt(0).toUpperCase())
                .join('');
        }

        nameInput.addEventListener('input', function() {
            const nameValue = nameInput.value;
            const abbreviation = generateAbbreviation(nameValue);
            codeInput.value = abbreviation;
        });

        function toggleFields() {
            const method = parseInt(calculationMethodSelect.value);

            // Toggle Amount field
            if ([1, 2, 5].includes(method)) {
                amountWrapper.style.display = 'block';
            } else {
                amountWrapper.style.display = 'none';
            }

            // Toggle Calculated On field
            if (![1, 6, 7].includes(method)) {
                calculatedOnWrapper.style.display = 'block';
            } else {
                calculatedOnWrapper.style.display = 'none';
            }

            // Toggle Pay Slab field
            if (method === 3) {
                paySlabWrapper.style.display = 'block';
            } else {
                paySlabWrapper.style.display = 'none';
            }

            // Toggle Pay Group field
            if (method === 4) {
                payGroupWrapper.style.display = 'block';
            } else {
                payGroupWrapper.style.display = 'none';
            }

            // Toggle Formula field
            if (method === 6) {
                formulaWrapper.style.display = 'block';
            } else {
                formulaWrapper.style.display = 'none';
            }
        }

        // Initial toggle on page load
        toggleFields();

        // Add event listener to handle changes
        calculationMethodSelect.addEventListener('change', toggleFields);
    });
</script>
@endsection