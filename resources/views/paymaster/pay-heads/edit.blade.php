@extends('layouts.app')

@section('page-title', 'Edit Pay Head')

@section('content')
<form action="{{ url('paymaster/pay-heads/' . $payHead->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $payHead->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="code">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{ old('code', $payHead->code) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="payhead_type">Pay Head Type <span class="text-danger">*</span></label></label>
                        <select name="payhead_type" class="form-control" required>
                            <option value="" disabled>Select an option</option>
                            <option value="1" {{ old('payhead_type', $payHead->payhead_type) == 1 ? 'selected' : '' }}>Allowance</option>
                            <option value="2" {{ old('payhead_type', $payHead->payhead_type) == 2 ? 'selected' : '' }}>Deduction</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="accounthead_type">Account Head Type <span class="text-danger">*</span></label></label>
                        <select name="accounthead_type" class="form-control" required>
                            <option value="" disabled>Select an option</option>
                            <option value="1" {{ old('accounthead_type', $payHead->accounthead_type) == 1 ? 'selected' : '' }}>Allowance</option>
                            <option value="2" {{ old('accounthead_type', $payHead->accounthead_type) == 2 ? 'selected' : '' }}>Deduction</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="calculation_method">Calculation Method <span class="text-danger">*</span></label></label>
                        <select name="calculation_method" class="form-control" required>
                            <option value="" disabled>Select an option</option>
                            <option value="1" {{ old('calculation_method', $payHead->calculation_method) == 1 ? 'selected' : '' }}>Actual Amount</option>
                            <option value="2" {{ old('calculation_method', $payHead->calculation_method) == 2 ? 'selected' : '' }}>Division Method</option>
                            <option value="3" {{ old('calculation_method', $payHead->calculation_method) == 3 ? 'selected' : '' }}>On Pay Slab</option>
                            <option value="4" {{ old('calculation_method', $payHead->calculation_method) == 4 ? 'selected' : '' }}>On Pay Group</option>
                            <option value="5" {{ old('calculation_method', $payHead->calculation_method) == 5 ? 'selected' : '' }}>Percentage Method</option>
                            <option value="6" {{ old('calculation_method', $payHead->calculation_method) == 6 ? 'selected' : '' }}>By Formula</option>
                            <option value="7" {{ old('calculation_method', $payHead->calculation_method) == 7 ? 'selected' : '' }}>Employment Wise</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="calculated_on">Calculated On <span class="text-danger">*</span></label></label>
                        <select name="calculated_on" class="form-control" required>
                            <option value="" disabled>Select an option</option>
                            <option value="1" {{ old('calculated_on', $payHead->calculated_on) == 1 ? 'selected' : '' }}>Basic Pay</option>
                            <option value="2" {{ old('calculated_on', $payHead->calculated_on) == 2 ? 'selected' : '' }}>Gross Pay</option>
                            <option value="3" {{ old('calculated_on', $payHead->calculated_on) == 3 ? 'selected' : '' }}>Net Pay</option>
                            <option value="4" {{ old('calculated_on', $payHead->calculated_on) == 4 ? 'selected' : '' }}>PIT Net Pay</option>
                            <option value="5" {{ old('calculated_on', $payHead->calculated_on) == 5 ? 'selected' : '' }}>Lumpsum</option>
                            <option value="6" {{ old('calculated_on', $payHead->calculated_on) == 6 ? 'selected' : '' }}>Pay Scale Base Pay</option>
                            <option value="7" {{ old('calculated_on', $payHead->calculated_on) == 7 ? 'selected' : '' }}>By Formula</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="formula">Formula <span class="text-danger">*</span></label></label>
                        <textarea class="form-control" name="formula">{{ old('formula', $payHead->formula) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('paymaster/pay-heads') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><strong>Formula Variables</strong></h5>
            </div>
            <div class="card-body">
                <ul>
                    <li>BASIC_PAY</li>
                    <li>NET_PAY</li>
                    <li>PIT_NET_PAY</li>
                    <li>GROSS_PAY</li>
                    <li>PAY_SCALE_BASE_PAY</li>
                    <li>MONTHS_IN_SERVICE</li>
                    <li>YEARS_IN_SERVICE</li>
                    <li>MONTHS_SINCE_REGULARISATION</li>
                    <li>YEARS_SINCE_REGULARISATION</li>
                    <li>OVERTIME_HOURS</li>
                    <li>HOURLY_WAGE</li>
                    <li>GRADE</li>
                    <li>GRADE_STEP</li>
                </ul>

                <p><strong>Conditional Operators:</strong></p>
                    <ul>
                        <li>IF</li>
                        <li>THEN</li>
                        <li>ELSEIF</li>
                        <li>ENDIF</li>
                    </ul>

                <p><strong>Comparison Operators:</strong></p>
                    <ul>
                        <li>&gt; (greater than)</li>
                        <li>&lt; (less than)</li>
                        <li>&gt;= (greater than or equal to)</li>
                        <li>&lt;= (less than or equal to)</li>
                        <li>== (equal to)</li>
                        <li>!= (not equal to)</li>
                    </ul>

                <p><strong>Logical Operators:</strong></p>
                    <ul>
                        <li>&amp; (AND)</li>
                        <li>|| (OR)</li>
                    </ul>
 
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><strong>Sample Formula</strong></h5>
            </div>
            <div class="card-body">
                <pre>
                    IF ([BASIC_PAY] &lt; 10000)
                    THEN (0.10 * [BASIC_PAY])
                    ELSE
                    THEN (0.20 * [BASIC_PAY])
                    ENDIF
                </pre>

                <p><strong>Note:</strong></p>
                <ol>
                    <li>Wrap Variables in Square Brackets - E.g. [BASIC_PAY]</li>
                    <li>Wrap Expressions in brackets - E.g. (0.3 * [BASIC_PAY])</li>
                    <li>All IF conditions should have a closing ENDIF</li>
                    <li>Each computation expression should have a THEN keyword in front. E.g. THEN ([BASIC_PAY]/12)</li>
                    <li>Each conditional or computation expression should start on a new line</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush
