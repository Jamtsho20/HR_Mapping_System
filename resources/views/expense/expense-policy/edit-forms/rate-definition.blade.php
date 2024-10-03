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
        <input type="checkbox" id="chkAttachmentRequired" name="rate_definition[attachment_required]" value="1" {{$expensePolicy->rateDefinition->attachment_required ? 'checked' : '' }}>
    </div>
</div>
<div class="row"><span class="col-sm-4 ">Travel type <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="travel_type" name="rate_definition[travel_type]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{$expensePolicy->rateDefinition->travel_type == 1 ? 'selected' : '' }}>Domestic</option>

        </select>
    </div>

</div>
<div class="row"> <span class="col-sm-4 ">Rate Currency <span class="text-danger">*</span></span>
    <div class="col-sm-2">
        <select class="form-control" id="leave-year" name="rate_definition[rate_currency]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{$expensePolicy->rateDefinition->rate_currency == 1 ? 'selected' : '' }}>Single Currency</option>
        </select>
    </div>
    <div class="col-sm-2">
        <select class="form-control" id="leave-year" name="rate_definition[currency]">
            <option value="" disabled selected hidden>Nu</option>
            <option value="1" {{$expensePolicy->rateDefinition->currency == 1 ? 'selected' : '' }}>Nu.</option>
        </select>
    </div>

</div>
<div class="row">
    <span class="col-sm-4 ">Rate Limit <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="" name="rate_definition[rate_limit]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{$expensePolicy->rateDefinition->rate_limit == 1 ? 'selected' : '' }}>Monthly</option>
            <option value="2" {{$expensePolicy->rateDefinition->rate_limit == 2 ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>
</div>



<br>
&nbsp;&nbsp;

<div class="tab-pane">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="expense-rules" class="table table-condensed table-bordered table-striped table-sm">
                    <thead>
                        <tr role="row">
                            <th>#</th>
                            <th>Grade</th>
                            <th>Region</th>
                            <th>Limit Amount</th>
                            <th>START DATE</th>
                            <th>END DATE</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expensePolicy->rateDefinition->ExpensePolicyRule as $key => $rule)
                        <input type="hidden" name="rate_definition_rule[{{$key}}][expense_policy_plan_id]" class="form-control resetKeyForNew" value="{{$expensePolicy->rateDefinition->id}}">
                        <tr>
                            <td class="text-center">
                                <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                            </td>

                            <td class="text-center">
                                <select name="rate_definition_rule[{{ $key }}][mas_grade_step_id]" class="form-control form-control-sm resetKeyForNew" required>
                                    <option value="" disabled selected hidden>SELECT ONE</option>
                                    @foreach($gradeSteps as $step)
                                    @php
                                    $gradeStepsArray = is_array(json_decode($rule->mas_grade_step_id, true)) ? json_decode($rule->mas_grade_step_id, true) : [$rule->mas_grade_step_id];
                                    @endphp
                                    <option value="{{ $step->id }}" {{ in_array($step->id, $gradeStepsArray) ? 'selected' : '' }}>
                                        {{ $step->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="text-center">
                                <select class="form-control resetKeyForNew" name="rate_definition_rule[{{$key}}][region]">
                                    <option value="" disabled selected hidden>Select Region</option>
                                    @foreach($regions as $region)
                                    <option value="{{$region->id}}" {{$rule->mas_region_id == $region->id ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="text-center">
                                <input type="number" placeholder="Enter limit amount" class="form-control resetKeyForNew" name="rate_definition_rule[{{$key}}][limit_amount]" value="{{$rule->limit_amount}}">
                            </td>

                            <td class="text-center">
                                <input type="date" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker resetKeyForNew" name="rate_definition_rule[{{$key}}][start_date]" value="{{$rule->start_date}}">
                            </td>

                            <td class="text-center">
                                <input type="date" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker resetKeyForNew" name="rate_definition_rule[{{$key}}][end_date]" value="{{$rule->end_date}}">
                            </td>

                            <td class="text-center">
                                <select class="form-control resetKeyForNew" name="rate_definition_rule[{{$key}}][status]">
                                    <option value="" disabled selected hidden>Select Status</option>
                                    @foreach(config('global.status') as $key => $value)
                                    <option value="{{ $key }}" {{$rule->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="notremovefornew">
                            <td colspan="6"></td>
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

<script>
    document.getElementById('select-all').addEventListener('change', function() {
        var selectBox = document.querySelector('select[name="rate_definition_rule[AAAAA][mas_grade_step_id][]"]');
        var options = selectBox.options;

        for (var i = 0; i < options.length; i++) {
            options[i].selected = this.checked;
        }

        // Trigger the change event for Select2 to update the UI
        $(selectBox).trigger('change');
    });
</script>