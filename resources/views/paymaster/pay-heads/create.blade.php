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
                        <select name="payhead_type" id="pay_head_type" class="form-control" required>
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
                        <label for="account_head_id">Account Head</label>
                        <select name="account_head_id" id="account__head_id" class="form-control" required>
                            <option value="" disabled selected>Select an option</option>
                            @foreach($accountHeads as $accountHead)
                            <option value="{{ $accountHead->id }}">{{ $accountHead->name }}</option>
                            @endforeach
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
                        <select name="calculation_method" id="calculation_method" class="form-control">
                            <option value="" disabled selected >Select your option</option>
                            @foreach(config('global.calculation_methods_for_payheads') as $key => $value)
                            <option value="{{ $key }}" {{ old('calculation_method') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="calculated_on_container">
                        <label for="calculated_on">Calculated On</label>
                        <select name="calculated_on" id="calculated_on" class="form-control">
                            <option value="" disabled selected >Select your option</option>
                            @foreach(config('global.calculated_on') as $key => $value)
                            <option value="{{ $key }}" {{ old('calculated_on') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Dynamic Forms -->
                    <div id="actualmethod_form" class="calculation_method">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" name="amount" value="">
                        </div>
                    </div>
                    <div id="divisionmethod_form" class="calculation_method ">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" name="amount" value="">
                        </div>
                    </div>
                    <div id="payslab_form" class="calculation_method ">
                        <div class="form-group">
                            <label for="payslab">Pay Slab</label>
                            <select class="form-control" name="mas_pay_slab_id" id="mas_pay_slab_id">
                                <option value="" disabled selected>Select Pay Slab</option>
                                @foreach($paySlabs as $paySlab)
                                <option value="{{ $paySlab->id }}">{{ $paySlab->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="paygroup_form" class="calculation_method ">
                        <div class="form-group">
                            <label for="paygroup">Pay Group</label>
                            <select class="form-control" name="mas_pay_group_id" id="mas_pay_group_id">
                                <option value="" disabled selected>Select Pay Group</option>
                                @foreach($payGroups as $payGroup)
                                <option value="{{ $payGroup->id }}">{{ $payGroup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="percentagemethod_form" class="calculation_method ">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" name="amount" value="">
                        </div>
                    </div>
                    <div id="formulamethod_form" class="calculation_method ">
                        <div class="form-group">
                            <label for="formula">Formula</label>
                            <textarea class="form-control" name="formula"></textarea>
                        </div>
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

<style>
    .hidden {
        display: none;
    }
</style>

@include('layouts.includes.delete-modal')

@endsection

@push('page_scripts')
<script>
 $(document).ready(function() {
    $('#calculation_method').on('change', function() {
        var selection = $(this).val();
        $(".calculation_method").hide();
        $("#calculated_on_container").show(); // Show by default

        switch (selection) {
            case "1":
                $("#actualmethod_form").show();
                break;
            case "2":
                $("#divisionmethod_form").show();
                break;
            case "3":
                $("#payslab_form").show();
                break;
            case "4":
                $("#paygroup_form").show();
                break;
            case "5":
                $("#percentagemethod_form").show();
                break;
            case "6":
                $("#formulamethod_form").show();
                break;
            case "7":
                $("#employeewisemethod_form").show(); // Ensure this element exists
                break;
            default:
                $(".calculation_method").hide();
        }

        // Hide 'calculated_on' field if specific calculation methods are selected
        if (["1", "6", "7"].includes(selection)) {
            $("#calculated_on_container").hide();
        } else {
            $("#calculated_on_container").show();
        }
    });

    // Trigger change event on page load if a method is already selected
    $('#calculation_method').trigger('change');
});

</script>
@endpush