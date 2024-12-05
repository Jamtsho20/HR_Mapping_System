@extends('layouts.app')
@section('page-title', 'Edit Pay Head')
@section('content')
    <form action="{{ route('pay-heads.update', $payHead->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payhead_type">Pay Head Type <span class="text-danger">*</span></label>
                            <select name="payhead_type" id="payhead_type" class="form-control" required>
                                <option value="" disabled>Select an option</option>
                                <option value="1" {{ $payHead->payhead_type == 1 ? 'selected' : '' }}>Allowance
                                </option>
                                <option value="2" {{ $payHead->payhead_type == 2 ? 'selected' : '' }}>Deductions
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="account_head_id">Account Head <span class="text-danger">*</span></label>
                            <select name="account_head_id" id="account_head_id" class="form-control" required>
                                <option value="" disabled>Select an option</option>
                                @foreach ($accountHeads as $accountHead)
                                    <option value="{{ $accountHead->id }}"
                                        {{ $payHead->account_head_id == $accountHead->id ? 'selected' : '' }}>
                                        {{ $accountHead->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="general_ledger_code">GL Code <span class="text-danger">*</span></label>
                            <input type="text" name="general_ledger_code" id="general_ledger_code" class="form-control"
                                value="{{ $payHead->general_ledger_code }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ $payHead->name }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="code">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control"
                                value="{{ $payHead->code }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="calculation_method">Calculation Method <span class="text-danger">*</span></label>
                            <select name="calculation_method" id="calculation_method" class="form-control" required>
                                <option value="" disabled>Select your option</option>
                                <option value="1" {{ $payHead->calculation_method == 1 ? 'selected' : '' }}>Actual
                                    Amount</option>
                                <option value="2" {{ $payHead->calculation_method == 2 ? 'selected' : '' }}>Division
                                    Method</option>
                                <option value="3" {{ $payHead->calculation_method == 3 ? 'selected' : '' }}>On Pay
                                    Slab</option>
                                <option value="4" {{ $payHead->calculation_method == 4 ? 'selected' : '' }}>On Pay
                                    Group</option>
                                <option value="5" {{ $payHead->calculation_method == 5 ? 'selected' : '' }}>Percentage
                                    Method</option>
                                <option value="6" {{ $payHead->calculation_method == 6 ? 'selected' : '' }}>By Formula
                                </option>
                                <option value="7" {{ $payHead->calculation_method == 7 ? 'selected' : '' }}>Employee
                                    Wise</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" id="calculated_on_wrapper"
                            @if (in_array($payHead->calculation_method, [1, 6, 7])) style="display: none;"
                        @else
                        style="display: block;" @endif>
                            <label for="calculated_on">Calculated On <span class="text-danger">*</span></label>
                            <select name="calculated_on" id="calculated_on" class="form-control">
                                <option value="" disabled>Select your option</option>
                                <option value="1" {{ $payHead->calculated_on == 1 ? 'selected' : '' }}>Basic Pay
                                </option>
                                <option value="2" {{ $payHead->calculated_on == 2 ? 'selected' : '' }}>Gross Pay
                                </option>
                                <option value="3" {{ $payHead->calculated_on == 3 ? 'selected' : '' }}>Net Pay
                                </option>
                                <option value="4" {{ $payHead->calculated_on == 4 ? 'selected' : '' }}>PIT Net Pay
                                </option>
                                <option value="5" {{ $payHead->calculated_on == 5 ? 'selected' : '' }}>Lumpsum
                                </option>
                                <option value="6" {{ $payHead->calculated_on == 6 ? 'selected' : '' }}>Pay Scale Base
                                    Pay</option>
                                <option value="7" {{ $payHead->calculated_on == 7 ? 'selected' : '' }}>By Formula
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="pay_slab_wrapper"
                                @if ($payHead->calculation_method == 3) style="display: block;"
                            @else
                            style="display: none;" @endif>
                                <label for="mas_pay_slab_id">Pay Slab <span class="text-danger">*</span></label>
                                <select name="mas_pay_slab_id" id="mas_pay_slab_id" class="form-control">
                                    <option value="" disabled>Select Pay Slab</option>
                                    @foreach ($paySlabs as $paySlab)
                                        <option value="{{ $paySlab->id }}"
                                            {{ $payHead->mas_pay_slab_id == $paySlab->id ? 'selected' : '' }}>
                                            {{ $paySlab->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="pay_group_wrapper"
                                @if ($payHead->calculation_method == 4) style="display: block;"
                            @else
                            style="display: none;" @endif>
                                <label for="mas_pay_group_id">Pay Group <span class="text-danger">*</span></label>
                                <select name="mas_pay_group_id" id="mas_pay_group_id" class="form-control">
                                    <option value="" disabled>Select Pay Group</option>
                                    @foreach ($payGroups as $payGroup)
                                        <option value="{{ $payGroup->id }}"
                                            {{ $payHead->mas_pay_group_id == $payGroup->id ? 'selected' : '' }}>
                                            {{ $payGroup->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group" id="amount_wrapper"
                            @if (in_array($payHead->calculation_method, [1, 2, 5])) style="display: block;"
                        @else
                        style="display: none;" @endif>
                            <label for="amount">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                value="{{ $payHead->amount }}">
                        </div>

                        <div class="form-group" id="formula_wrapper"
                            @if ($payHead->calculation_method == 6) style="display: block;"
                        @else
                        style="display: none;" @endif>
                            <label for="formula">Formula <span class="text-danger">*</span></label>
                            <textarea name="formula" id="formula" class="form-control">{{ $payHead->formula }}</textarea>
                        </div>

                    </div>
                </div>

                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                    <a href="{{ url('paymaster/pay-heads') }}" class="btn btn-danger"><i class="fa fa-undo"></i>
                        CANCEL</a>
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

            function toggleFields() {
                const method = parseInt(calculationMethodSelect.value);

                amountWrapper.style.display = [1, 2, 5].includes(method) ? 'block' : 'none';
                calculatedOnWrapper.style.display = [1, 6, 7].includes(method) ? 'none' : 'block';
                paySlabWrapper.style.display = method === 3 ? 'block' : 'none';
                payGroupWrapper.style.display = method === 4 ? 'block' : 'none';
                formulaWrapper.style.display = method === 6 ? 'block' : 'none';
            }

            calculationMethodSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
@endsection
