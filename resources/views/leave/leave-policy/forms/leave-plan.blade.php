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
        <input type="checkbox" id="chkAttachmentRequired" name="leave_plan[attachment_required]" value="1" {{ old('leave_policy.attachment_required') ? 'checked' : '' }}>
    </div>
</div>
<div class="row"><span class="col-sm-4 ">Gender <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="gender" name="leave_plan[gender]" required>
            <option value="" disabled selected hidden>Select your option</option>
            @foreach(config('global.gender_with_all') as $key => $value)
            <option value="{{ $key }}" {{old('leave_plan.gender') == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>

</div>
<div class="row"> <span class="col-sm-4 ">Leave Year <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="leave-year" name="leave_plan[leave_year]" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{old('leave_plan.leave_year') == 1 ? 'selected' : '' }}>Financial Year</option>
            <option value="2" {{old('leave_plan.leave_year') == 2 ? 'selected' : '' }}>Calender Year</option>
        </select>
    </div>

</div>
<div class="row">
    <span class="col-sm-4 ">Credit Frequency <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="credit-frequency" name="leave_plan[credit_frequency]" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{old('leave_plan.credit_frequency') == 1 ? 'selected' : '' }}>Monthly</option>
            <option value="2" {{old('leave_plan.credit_frequency') == 2 ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>
</div>
<div class="row">
    <span class="col-sm-4 ">Credit<span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="credit-frequency" name="leave_plan[credit]" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{old('leave_plan.credit') == 1 ? 'selected' : '' }}>Start Of Period</option>
            <option value="2" {{old('leave_plan.credit') == 2 ? 'selected' : '' }}>End Of Period</option>
        </select>
    </div>
</div>
<!-- leave limit -->
<div class="row">
    <span class="col-sm-4">Leave Limits</span>
    <div class="col-sm-8" style="padding-left: 2.3%;">
        <div class="row ">
            @foreach(config('global.leave_limits') as $key => $value)
            <div class="col-sm-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="leave_plan[leave_limits][]" value="{{ $key }}"
                        {{ in_array($key, old('leave_plan.leave_limits', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" style="font-weight: 400;">
                        {{ $value }}
                    </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- can avail -->
<div class="row">
    <span class="col-sm-4">Can Avail In <span class="text-danger">*</span></span>

    @foreach($employmentTypes as $employmentType)
    @if($employmentType->id != 1)
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailprobationperiad" name="leave_plan[can_avail_in][]"
                    value="{{$employmentType->id}}" class="can_avail" {{ old('leave_plan.can_avail_in') ? 'checked' : '' }}>&nbsp;{{$employmentType->name}}
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
                <table id="qualifications" class="table table-condensed table-bordered table-striped table-sm">
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
                        <tr>
                            <td class="text-center">
                                <a href="#" class="delete-table-row btn btn-danger btn-sm"><i
                                        class="fa fa-times"></i></a>
                            </td>
                            <td class="text-center">
                                <label class="custom-control custom-checkbox">
                                    <input id="select-all" type="checkbox" class="custom-control-input resetKeyForNew"
                                        name="example-checkbox1" value=""> <span
                                        class="custom-control-label">Select All</span>
                                </label>

                                <select class="form-control select2 select2-hidden-accessible resetKeyForNew"
                                    data-placeholder="Choose Grade" multiple tabindex="-1" style="width: 100%"
                                    aria-hidden="true" name="leave_policy_rule[AAAAA][mas_grade_step_id][]">
                                    @foreach($gradeSteps as $step)
                                    <option value="{{ $step->id }}"
                                        {{ (is_array(old('leave_policy_rule.AAAAA.mas_grade_step_id')) && in_array($step->id, old('leave_policy_rule.AAAAA.mas_grade_step_id'))) ? 'selected' : '' }}>
                                        {{ $step->name }}
                                    </option>
                                    @endforeach
                                </select>


                            </td>
                            <td class="text-center">
                                <input type="number" min="0" maxlength="5" name="leave_policy_rule[AAAAA][duration]"
                                    class="form-control resetKeyForNew" id="duration" placeholder="Duration"
                                    value="{{ old('leave_policy_rule.AAAAA.duration') }}">
                            </td>

                            <td class="text-center">
                                <select class="form-control resetKeyForNew" id="UOM" name="leave_policy_rule[AAAAA][uom]">
                                    <option value="" disabled selected hidden>Select your option</option>
                                    <option value="1" {{ old('leave_policy_rule.AAAAA.uom') == 1 ? 'selected' : '' }}>Day</option>
                                    <option value="2" {{ old('leave_policy_rule.AAAAA.uom') == 2 ? 'selected' : '' }}>Month</option>
                                    <option value="3" {{ old('leave_policy_rule.AAAAA.uom') == 3 ? 'selected' : '' }}>Year</option>
                                </select>
                            </td>

                            <td class="text-center">
                                <input type="date" placeholder="dd-mmm-yyyy" class="resetKeyForNew form-control mycal hasDatepicker"
                                    style="background-color: rgb(255, 255, 255);"
                                    name="leave_policy_rule[AAAAA][start_date]"
                                    value="{{ old('leave_policy_rule.AAAAA.start_date') }}">
                            </td>

                            <td class="text-center">
                                <input type="date" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker resetKeyForNew"
                                    style="background-color: rgb(255, 255, 255);"
                                    name="leave_policy_rule[AAAAA][end_date]"
                                    value="{{ old('leave_policy_rule.AAAAA.end_date') }}">
                            </td>

                            <td class="text-center">
                                <select class="form-control resetKeyForNew" id="ddlislossofpay" name="leave_policy_rule[AAAAA][is_loss_of_pay]">
                                    <option value="" disabled {{ old('leave_policy_rule.AAAAA.is_loss_of_pay') === null ? 'selected' : '' }} hidden>Select your option</option>
                                    <option value="1" {{ old('leave_policy_rule.AAAAA.is_loss_of_pay') === '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('leave_policy_rule.AAAAA.is_loss_of_pay') === '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </td>

                            <td class="text-center">
                                <select class="form-control resetKeyForNew" id="Employeetype" name="leave_policy_rule[AAAAA][mas_employment_type_id]">
                                    <option value="" disabled selected hidden>Select your option</option>
                                    @foreach($employmentTypes as $employmentType)
                                    <option value="{{$employmentType->id}}"
                                        {{ old('leave_policy_rule.AAAAA.mas_employment_type_id') == $employmentType->id ? 'selected' : '' }}>
                                        {{$employmentType->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="text-center">
                                <select class="form-control resetKeyForNew" name="leave_policy_rule[AAAAA][status]">
                                    <option value="" disabled {{ old('leave_policy_rule.AAAAA.status') === null ? 'selected' : '' }}>Select your option</option>
                                    @foreach(config('global.status') as $key => $value)
                                    <option value="{{ $key }}" {{ old('leave_policy_rule.AAAAA.status') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>

                        </tr>
                        <tr class="notremovefornew">
                            <td colspan="8"></td>
                            <td class="text-right">
                                <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 12px"><i class="fa fa-plus"></i> Add New Row</a>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        var selectBox = document.querySelector('select[name="leave_policy_rule[AAAAA][mas_grade_step_id][]"]');
        var options = selectBox.options;

        for (var i = 0; i < options.length; i++) {
            options[i].selected = this.checked;
        }

        // Trigger the change event for Select2 to update the UI
        $(selectBox).trigger('change');
    });
</script>