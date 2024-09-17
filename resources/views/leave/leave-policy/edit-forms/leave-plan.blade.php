<style>
    .row {
        margin-bottom: 0.5rem;
    }

    .form-check {
        padding-left: 0 !important;
    }
</style>

<div class="row">
    <span class="col-sm-4 "> Attachment Required </span>
    <div class="col-sm-4 ">
        <input type="checkbox" id="chkAttachmentRequired" name="leave_plan[attachment_required]" value="1" {{ $leavePolicy->leavePolicyPlan->attachment_required ? 'checked' : '' }}>
    </div>
</div>
<div class="row"><span class="col-sm-4 ">Gender <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="gender" name="leave_plan[gender]" required>
            <option value="" disabled selected hidden>Select your option</option>
            @foreach(config('global.gender') as $key => $rule)
            <option value="{{ $key }}" {{$leavePolicy->leavePolicyPlan->gender == $key ? 'selected' : '' }}>{{ $rule }}</option>
            @endforeach
        </select>
    </div>

</div>
<div class="row"> <span class="col-sm-4 ">Leave Year <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="leave-year" name="leave_plan[leave_year]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{$leavePolicy->leavePolicyPlan->leave_year == 1 ? 'selected' : '' }}>Financial Year</option>
            <option value="2" {{$leavePolicy->leavePolicyPlan->leave_year == 2 ? 'selected' : '' }}>Calender Year</option>
        </select>
    </div>

</div>
<div class="row">
    <span class="col-sm-4 ">Credit Frequency <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="credit-frequency" name="leave_plan[credit_frequency]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{$leavePolicy->leavePolicyPlan->credit_frequency == 1 ? 'selected' : '' }}>Monthly</option>
            <option value="2" {{$leavePolicy->leavePolicyPlan->credit_frequency == 2 ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>
</div>
<div class="row">
    <span class="col-sm-4 ">Credit<span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="credit-frequency" name="leave_plan[credit]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{$leavePolicy->leavePolicyPlan->credit == 1 ? 'selected' : '' }}>Monthly</option>
            <option value="2" {{$leavePolicy->leavePolicyPlan->credit == 2 ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>
</div>
<!-- leave limit -->
<div class="row">
    <span class="col-sm-4">Leave Limits</span>
    <div class="col-sm-8" style="padding-left: 2.3%;">
        <div class="row ">
            @foreach(config('global.leave_limits') as $key => $rule)
            <div class="col-sm-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="leave_plan[leave_limits][]" value="{{ $key }}"
                        {{ in_array($key, (array) json_decode($leavePolicy->leavePolicyPlan->leave_limits)) ? 'checked' : '' }}>
                    <label class="form-check-label" style="font-weight: 400;">
                        {{ $rule }}
                    </label>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

<!-- can avail -->
<div class="row">
    <span class="col-sm-4">Can Avail In</span>
    @foreach($employmentTypes as $employmentType)
    @if($employmentType->id != 0)
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailprobationperiad" name="leave_plan[can_avail_in][]" value="{{$employmentType->id}}" class="can_avail" {{in_array($employmentType->id, json_decode($leavePolicy->leavePolicyPlan->can_avail_in)) ? 'checked' : '' }}>&nbsp;{{$employmentType->name}}
            </label>
        </div>
    </div>
    @endif
    @endforeach
</div>
<br>
&nbsp;&nbsp;

<div class="tab-pane">
    <div class="card">
        <div class="card-body p-0">
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
                        <input type="hidden" name="leave_policy_rule[{{$key}}][leave_policy_plan_id]" class="form-control resetKeyForNew" value="{{$leavePolicy->leavePolicyPlan->id}}">
                        <input type="hidden" name="leave_policy_rule[{{$key}}][leave_policy_plan_id]" class="form-control resetKeyForNew" value="{{$leavePolicy->leavePolicyPlan->id}}">
                        <tr>
                            <td class="text-center">
                                <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                            </td>

                            <td class="text-center">
                                <select name="leave_policy_rule[{{ $key }}][mas_grade_step_id]" class="form-control form-control-sm resetKeyForNew" required>
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
                                <input type="text" min="0" maxlength="5" name="leave_policy_rule[{{$key}}][duration]" class="form-control resetKeyForNew" id="duration" placeholder="Duration" value="{{$rule->duration}}">
                            </td>
                            <td class="text-center">
                                <select class="form-control resetKeyForNew" id="UOM" name="leave_policy_rule[{{$key}}][uom]">
                                    <option value="1" {{($rule->uom) == 1 ? 'selected' : '' }}>Day</option>
                                    <option value="2" {{($rule->uom) == 2 ? 'selected' : '' }}>Month</option>
                                    <option value="3" {{($rule->uom) == 3 ? 'selected' : '' }}>Year</option>
                                </select>
                            </td>

                            <td class="text-center">
                                <input type="date" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker resetKeyForNew" style="background-color: rgb(255, 255, 255);" name="leave_policy_rule[{{$key}}][start_date]" value="{{$rule->start_date}}">
                            </td>
                            <td class="text-center">
                                <input type="date" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker resetKeyForNew" style="background-color: rgb(255, 255, 255);" name="leave_policy_rule[{{$key}}][end_date]" value="{{$rule->end_date}}">
                            </td>
                            <td class="text-center">
                                <select class="form-control resetKeyForNew" id="ddlislossofpay" name="leave_policy_rule[{{$key}}][is_loss_of_pay]">
                                    <option value="0">Select</option>
                                    <option value="1" {{$rule->is_loss_of_pay == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{$rule->is_loss_of_pay == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <select class="form-control resetKeyForNew" id="Employeetype" name="leave_policy_rule[{{$key}}][mas_employment_type_id]">
                                    <option value="" disabled selected hidden>Select your option</option>
                                    @foreach($employmentTypes as $employmentType)
                                    <option value="{{$employmentType->id}}" {{$rule->mas_employment_type_id == $employmentType->id ? 'selected' : '' }}>{{$employmentType->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <select class="form-control resetKeyForNew" name="leave_policy_rule[{{$key}}][status]">
                                    <option value="" disabled selected hidden>Select your option</option>
                                    @foreach(config('global.status') as $key => $value)
                                    <option value="{{ $key }}" {{$rule->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="notremovefornew">
                            <td colspan="8"></td>
                            <td class="text-right">
                                <a href="#" class="add-table-row btn btn-sm btn-primary" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>