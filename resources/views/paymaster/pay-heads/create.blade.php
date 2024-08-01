@extends('layouts.app')
@section('page-title', 'Create Pay Head')
@section('content')

<form action="{{ route('pay-heads.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="payhead_type">Pay Head Type</label>
                        <select name="payhead_type" id="pay_head_type" class="form-control form-control-sm" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="1">Allowance</option>
                            <option value="2">Deduction</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" value="" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="accounthead_type">Account Head</label>
                        <select name="accounthead_type" id="account_head_type" class="form-control form-control-sm" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="1">Allowance</option>
                            <option value="2">Deduction</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" name="code" value="" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="calculation_method">Calculation Method</label>
                        <select name="calculation_method" id="calculation_method" class="form-control form-control-sm" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="1">Actual Amount</option>
                            <option value="2">Division Method</option>
                            <option value="3">On Pay Slab</option>
                            <option value="4">On Pay Group</option>
                            <option value="5">Percentage Method</option>
                            <option value="6">By Formula</option>
                            <option value="7">Employment Wise</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="calculated_on">Calculated On</label>
                        <select name="calculated_on" id="calculated_on" class="form-control form-control-sm" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="1">Basic Pay</option>
                            <option value="2">Gross Pay</option>
                            <option value="3">Net pay</option>
                            <option value="4">PIT Net Pay</option>
                            <option value="5">Lumpsum</option>
                            <option value="6">Pay Scale Base Pay</option>
                            <option value="7">By Formula</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
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