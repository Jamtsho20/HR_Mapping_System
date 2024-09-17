@extends('layouts.app')
@section('page-title', 'Showing Leave Policy Details')
@section('buttons')
<a href="{{ url('leave/leave-policy/')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to leave Policy List</a>
@endsection
@section('content')
@if ($canUpdate === 1)
<div class="d-flex flex-row-reverse">
    <a href="{{ url('leave/leave-policy/' .$leavePolicy->id . '/edit') }}" class="col-sm-2 btn btn-outline-primary btn-block btn-sm "><b><i class="fa fa-edit"></i> Edit Record</b>
    </a>
</div>
<br>

@endif
<div class="row">
    <!--Leave Policy and LeavePlan -->

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <h3 class="card-title mb-1 mt-1">Leave Policy</h3>
                        <br>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Leave Policy</b> <a class="pull-right">{{ $leavePolicy->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Leave Type</b> <a class="pull-right">{{ $leavePolicy->leaveType->name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Description</b> <a class="pull-right">{{ $leavePolicy->description }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Start Date</b> <a class="pull-right">{{$leavePolicy->start_date}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>End Date</b> <a class="pull-right">{{$leavePolicy->end_date}}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <h3 class="card-title mb-1 mt-1">Leave Plan</h3>
                        <br>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Attachment Required</b> <a class="pull-right">{{ $leavePolicy->leavePolicyPlan->attachment_required == 1?'Yes':'no' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Gender</b>
                                @php
                                $gender=$leavePolicy->leavePolicyPlan->gender;
                                @endphp
                                <a class="pull-right">
                                    @if ($gender==1)
                                    Male
                                    @elseif($gender==2)
                                    Female
                                    @else
                                    All
                                    @endif
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Leave Year</b> <a class="pull-right">{{ $leavePolicy->leavePolicyPlan->leave_year ==1 ? 'Calender Year':'Financial Year' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Credi Frequency</b> <a class="pull-right">{{ $leavePolicy->leavePolicyPlan->credit_frequency }}</a>
                            </li>
                            @php
                            // Convert leave_limits to an array if it's a string
                            $leaveLimits = $leavePolicy->leavePolicyPlan->leave_limits ?? '';

                            if (is_string($leaveLimits)) {
                            $leaveLimits = json_decode($leaveLimits); // Decode JSON to array
                            }
                            @endphp

                            @if (!empty($leaveLimits) && is_array($leaveLimits))
                            <li class="list-group-item">
                                <b>Leave Limits</b>
                                <ul class="pull-right">
                                    @foreach(config('global.leave_limits') as $key => $value)
                                    @if(in_array($key, $leaveLimits))
                                    <li>{{ $value }}</li>
                                    @endif
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                            <li class="list-group-item">
                                <b>Can Avail In</b> <a class="pull-right">{{$leavePolicy->end_date}}</a>
                            </li>
                        </ul>
                    </div>


                </div>

            </div>

        </div>
    </div>

    <!-- Leave Rules -->
    <div class="col-md-12">
        <div class="card">
            <h3 class="card-title mb-1 mt-1">Leave Rules</h3>
            <div class="card-body ">
                <div class="table-responsive">
                    <table id="leave-rules" class="table table-condensed table-bordered table-striped table-sm">
                        <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>Grade</th>
                                <th>Duration</th>
                                <th>UOM</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>Is Loss of Pay</th>
                                <th>EMPLOYMENT TYPE</th>
                                <th>STATUS</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leavePolicy->leavePolicyPlan->LeavePolicyRule as $key => $rule)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td class="text-center">
                                    <select name="leave_policy_rule[{{ $key }}][mas_grade_step_id]" class="form-control form-control-sm resetKeyForNew" disabled required>
                                        <option value="" disabled selected hidden>SELECT ONE</option>
                                        @foreach($gradeSteps as $step)
                                        @php
                                        $gradeStepsArray = is_array(json_decode($rule->mas_grade_step_id, true)) ? json_decode($rule->mas_grade_step_id, true) : [$rule->mas_grade_step_id];
                                        @endphp
                                        <option value="{{ $step->id }} "
                                            {{ in_array($step->id, $gradeStepsArray) ? 'selected' : '' }}>
                                            {{ $step->name }}
                                        </option>
                                        @endforeach

                                    </select>
                                </td>

                                <td class="text-center">
                                    <input type="text" disabled min="0" maxlength="5" name="leave_policy_rule[{{$key}}][duration]" class="form-control resetKeyForNew" id="duration" placeholder="Duration" value="{{$rule->duration}}">
                                </td>
                                <td class="text-center">
                                    <select class="form-control resetKeyForNew" id="UOM" name="leave_policy_rule[{{$key}}][uom]" disabled>
                                        <option value="1" {{($rule->uom) == 1 ? 'selected' : '' }}>Day</option>
                                        <option value="2" {{($rule->uom) == 2 ? 'selected' : '' }}>Month</option>
                                        <option value="3" {{($rule->uom) == 3 ? 'selected' : '' }}>Year</option>
                                    </select>
                                </td>

                                <td class="text-center">
                                    <input type="date" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker resetKeyForNew" style="background-color: rgb(255, 255, 255);" name="leave_policy_rule[{{$key}}][start_date]" value="{{$rule->start_date}}" disabled>
                                </td>
                                <td class="text-center">
                                    <input type="date" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker resetKeyForNew" style="background-color: rgb(255, 255, 255);" name="leave_policy_rule[{{$key}}][end_date]" value="{{$rule->end_date}}" disabled>
                                </td>
                                <td class="text-center">
                                    <select class="form-control resetKeyForNew" id="ddlislossofpay" name="leave_policy_rule[{{$key}}][is_loss_of_pay]" disabled>
                                        <option value="0">Select</option>
                                        <option value="1" {{$rule->is_loss_of_pay == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{$rule->is_loss_of_pay == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <select class="form-control resetKeyForNew" id="Employeetype" name="leave_policy_rule[{{$key}}][mas_employment_type_id]" disabled>
                                        <option value="" disabled selected hidden>Select your option</option>
                                        @foreach($employmentTypes as $employmentType)
                                        <option value="{{$employmentType->id}}" {{$rule->mas_employment_type_id == $employmentType->id ? 'selected' : '' }}>{{$employmentType->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center">
                                    <select class="form-control resetKeyForNew" name="leave_policy_rule[{{$key}}][status]" disabled>
                                        <option value="" disabled selected hidden>Select your option</option>
                                        @foreach(config('global.status') as $key => $value)
                                        <option value="{{ $key }}" {{$rule->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endforeach


                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <label class="form-check-label" style="font-weight:400">
                            <input type="checkbox" class="year_proccessing"
                                onclick="toggleInput('chkAllowCarryover', 'txtCarryoverLimit1');"
                                id="chkAllowCarryover"
                                value="1"
                                disabled
                                name="year_end_processing[allow_carry_over]"
                                {{ $leavePolicy->yearEnd->allow_carryover? 'checked' : '' }}>
                            Allow Carryover
                        </label>


                    </div>
                    <div class=" col-6">
                        <div class="row">
                            <div class="col-3"> <span>Carryover Limit</span></div>
                            <div class="col-3">
                                <input type="text" id="txtCarryoverLimit1" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" disabled value="{{$leavePolicy->yearEnd->carryover_limit}}" disabled name="year_end_processing[carryover_limit]">
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <label class="form-check-label" style="font-weight:400">
                            <input type="checkbox" class="year_proccessing" disabled onclick="toggleInput('chkPayYearEnd', 'txtMinBalance', 'txtMaxEncashment');" id="chkPayYearEnd" value="1" name="year_end_processing[pay_at_year_end]" {{ $leavePolicy->yearEnd->pay_at_year_end ? 'checked' : '' }}> Pay at Year end
                        </label>
                    </div>

                    <div class="col-6">
                        <div class="row">
                            <div class="col-3"> <span>Min. Balance Need To be Maintained</span></div>
                            <div class="col-3">
                                <input type="text" id="txtMinBalance" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{$leavePolicy->yearEnd->min_balance_required}}" disabled name="year_end_processing[min_balance_required]">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3"> <span>Maximum Encashment Per Year</span></div>
                            <div class="col-3">
                                <input type="text" id="txtMaxEncashment" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{$leavePolicy->yearEnd->min_encashment_per_year}}" disabled name="year_end_processing[min_encashment_per_year] ">
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <label class="form-check-label" style="font-weight:400">
                            <input disabled type="checkbox" class="year_proccessing" onclick="toggleInput('chkCarryForwardEL', 'txtCarryForwardLimit');" id="chkCarryForwardEL" value="1" name="year_end_processing[carry_forward_to_el]" {{ $leavePolicy->yearEnd->carry_forward_to_el ? 'checked' : '' }}> Carry Forward to EL
                        </label>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-3"> <span>Carry Forward Limit</span></div>
                            <div class="col-3">
                                <input type="text" disabled id="txtCarryForwardLimit" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{$leavePolicy->yearEnd->carry_forward_limit}}" disabled name="year_end_processing[carry_forward_limit]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    @endsection
    @push('page_scripts')
    <script>
        $(document).ready(function() {
            $('.btn-tool').on('click', function() {
                var icon = $(this).find('i');
                icon.toggleClass('fa-plus fa-minus'); // Toggle the icon
            });
        });
    </script>
    @endpush