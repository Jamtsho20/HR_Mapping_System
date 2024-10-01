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
        <input type="checkbox" id="chkAttachmentRequired" name="rate_definition[attachment_required]" value="1" {{ old('leave_policy.attachment_required') ? 'checked' : '' }}>
    </div>
</div>
<div class="row"><span class="col-sm-4 ">Travel type <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="travel_type" name="rate_definition[travel_type]" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{old('rate_definition.travel_type') == 1 ? 'selected' : '' }}>Domestic</option>

        </select>
    </div>

</div>
<div class="row"> <span class="col-sm-4 ">Rate Currency <span class="text-danger">*</span></span>
    <div class="col-sm-2">
        <select class="form-control" id="leave-year" name="rate_definition[rate_currency]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{old('rate_definition.rate_currency') == 1 ? 'selected' : '' }}>Single Currency</option>
        </select>
    </div>
    <div class="col-sm-2">
        <select class="form-control" id="leave-year" name="rate_definition[rate_currency]">
            <option value="" disabled selected hidden>Nu</option>
            <option value="1" {{old('rate_definition.rate_currency') == 1 ? 'selected' : '' }}>Nu.</option>
        </select>
    </div>

</div>
<div class="row">
    <span class="col-sm-4 ">Rate Limit <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="credit-frequency" name="rate_definition[credit_frequency]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="1" {{old('rate_definition.credit_frequency') == 1 ? 'selected' : '' }}>Monthly</option>
            <option value="2" {{old('rate_definition.credit_frequency') == 2 ? 'selected' : '' }}>Yearly</option>
        </select>
    </div>
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
                            <th>Region</th>
                            <th>Limit Amount</th>
                            <th>START DATE</th>
                            <th>END DATE</th>
                            <th>status</th>
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
                                    <input id="select-all" type="checkbox" class="custom-control-input"
                                        name="example-checkbox1" value=""> <span
                                        class="custom-control-label">Select All</span>
                                </label>

                                <select class="form-control select2 select2-hidden-accessible"
                                    data-placeholder="Choose Grade" multiple="" tabindex="-1" style="width: 100%"
                                    aria-hidden="true" name="rate_definition[AAAAA][mas_grade_step_id][]">
                                    @foreach($gradeSteps as $step)
                                    <option value="{{ $step->id }}" {{ (old('rate_definition.AAAAA.mas_grade_step_id') == $step->id) ? 'selected' : '' }}>
                                        {{ $step->name }}
                                    </option>
                                    @endforeach
                                </select>

                            </td>
                            <td class="text-center">
                                <select class="form-control" name="rate_definition[AAAAA][status]">
                                    <option value="" disabled {{ old('rate_definition.AAAAA.status') === null ? 'selected' : '' }}>Select your option</option>
                                    @foreach($regions as $region)
                                    <option value="$region->id" {{ old('rate_definition.AAAAA.status') == $region->id ? 'selected' : '' }}>
                                        {{ $region->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="text-center">
                                <input type="number" placeholder="Enter limit amount" class="form-control mycal hasDatepicker"
                                    style="background-color: rgb(255, 255, 255);"
                                    name="rate_definition[AAAAA][start_date]"
                                    {{old('rate_definition.AAAAA.start_date')}}>
                            </td>
                            <td class="text-center">
                                <input type="date" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker"
                                    style="background-color: rgb(255, 255, 255);"
                                    name="rate_definition[AAAAA][end_date]"
                                    {{old('rate_definition.AAAAA.end_date')}}>
                            </td>
                            <td class="text-center">
                                <input type="date" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker"
                                    style="background-color: rgb(255, 255, 255);"
                                    name="rate_definition[AAAAA][end_date]"
                                    {{old('rate_definition.AAAAA.end_date')}}>
                            </td>

                            <td class="text-center">
                                <select class="form-control" name="rate_definition[AAAAA][status]">
                                    <option value="" disabled {{ old('rate_definition.AAAAA.status') === null ? 'selected' : '' }}>Select your option</option>
                                    @foreach(config('global.status') as $key => $value)
                                    <option value="{{ $key }}" {{ old('rate_definition.AAAAA.status') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>


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
        var selectBox = document.querySelector('select[name="rate_definition[AAAAA][mas_grade_step_id][]"]');
        var options = selectBox.options;

        for (var i = 0; i < options.length; i++) {
            options[i].selected = this.checked;
        }

        // Trigger the change event for Select2 to update the UI
        $(selectBox).trigger('change');
    });
</script>