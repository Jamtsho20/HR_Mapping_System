@extends('layouts.app')
@section('page-title', 'Approval Rules')
@section('content')
    <div class="card">
        <form action="{{ route('approval-rules.update', $rule->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header ">
                <h3 class="card-title">Update Approval Rule</h3>
            </div>
            <div class="card-body">
                <div class="row" id="rule-container">
                    <div class="form-group col-4">
                        <label for="mas_approval_head_id">For <span class="text-danger required-marker">*</span></label>
                        <select class="form-control" name="mas_approval_head_id" id="mas_approval_head_id" disabled>
                            <option value="{{ $rule->mas_approval_head_id }}" disabled selected>
                                {{ $rule->approvalHead->name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <label for="approvable_id">Type <span class="text-danger required-marker">*</span></label>
                        <select class="form-control" name="approvable_id" id="approvable_id" disabled>
                            <option value="{{ $rule->approvable->id }}" disabled selected>{{ $rule->approvable->name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <label for="name">Rule Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $rule->name }}"
                            readonly>
                    </div>
                    <div class="form-group col-4">
                        <label for="start_date">Start Date </label>
                        <input type="date" class="js-datepicker form-control js-datepicker" id="start_date"
                            name="start_date" value="{{ $rule->start_date }}" data-week-start="1" data-autoclose="true"
                            data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy" readonly>
                    </div>
                    <div class="form-group col-4">
                        <label for="end_date">End Date </label>
                        <input type="date" class="js-datepicker form-control js-datepicker" id="end_date"
                            name="end_date" value="{{ $rule->end_date }}" data-week-start="1" data-autoclose="true"
                            data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy" required>
                    </div>

                    <div class="form-group col-4">
                        <label for="is_active">Status <span class="text-danger required-marker">*</span></label>
                        <select class="form-control" name="is_active" required>
                            @foreach (config('global.status') as $key => $type)
                                <option value="{{ $key }}" {{ $key == $rule->is_active ? 'selected' : '' }}>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-check"></i> Update
                </button>
                <a href="{{ route('approval-rules.index') }}" class="btn btn-danger">CANCEL</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Conditions</h3>
            <button class="btn btn-primary" id="conditions-btn"><i class="fa fa-plus"></i> New Condition</button>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tbl_all_condition" class="table table-striped custom-table m-b-0 ">
                        <thead>
                            <tr>
                                <th style="width:40%">
                                    Formula
                                </th>
                                <th style="width:20%">
                                    Hierarchy Name
                                </th>
                                <th style="display:none;">
                                    Hierarchy Max Level
                                </th>
                                <th style="width:20%">
                                    Single User
                                </th>

                                <th style="width:15%">
                                    Auto Approval
                                </th>
                                <th style="width:5%">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($conditions as $condition)
                                <tr>
                                    <td>{{ $condition->formula_display }}</td>
                                    <td>{{ $condition->systemHierarchy->name ?? '' }}</td>
                                    <td>{{ $condition->is_single_user }}</td>
                                    <td>{{ $condition->auto_approval }}</td>
                                    <td>
                                        @if ($privileges->view)
                                            <a href="{{ route('approval-rule-conditions.edit', $condition->id) }}"
                                                class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i>
                                                Detail</a>
                                        @endif
                                        @if ($privileges->edit)
                                            <a href="{{ route('approval-rule-conditions.edit', $condition->id) }}"
                                                class="btn btn-sm btn-rounded btn-outline-success"><i
                                                    class="fa fa-edit"></i> EDIT</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-danger required-marker">No Data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="conditions">
        <div class="modal-dialog modal-fullscreen p-5" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span
                            aria-hidden="true">×</span></button>
                    <!-- conditions -->
                    <div class="row" id="conditions-row">
                        <h4>Conditions</h4>
                        <br>
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="mas_approval_head_type_id">Type <span
                                        class="text-danger required-marker">*</span></label>
                                <select class="form-control" name="mas_approval_head_type_id"
                                    id="mas_approval_head_type_id" required="required" readonly disabled>
                                    <option value="{{ $rule->approvable->id }}" disabled selected>
                                        {{ $rule->approvable->name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-3">
                            <label for="delimiter">Condition</label>
                            <select class="form-control" name="delimiter" id="delimiter" disabled>
                                <option value="" disabled selected hidden>Select your option</option>
                                <option value="AND">AND</option>
                                <option value="OR">OR</option>
                                <option value="NOT">NOT</option>
                                <option value="(">(</option>
                                <option value=")">)</option>
                            </select>
                        </div>
                        <div class="form-group col-3">
                            <label for="mas_condition_field_id">Fields <span
                                    class="text-danger required-marker">*</span></label>
                            <select class="form-control" name="mas_condition_field_id" id="mas_condition_field_id"
                                required="required">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($fields as $field)
                                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-3" id="operator-container">
                            <label for="operator">Operator <span class="text-danger required-marker">*</span></label>
                            <select class="form-control" name="operator" id="operator" required="required">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($operators as $operator)
                                    <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-3" id="value-container">
                            <label for="value">Value <span class="text-danger required-marker">*</span></label>
                            <input type="number" class="form-control" name="value" id="condition_value" required
                                placeholder="Enter a value">
                        </div>
                    </div>
                    <!-- end of conditions -->

                    <!-- buttons -->
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="addCriteria">Add this to Criteria</button>
                        <button type="reset" class="btn btn-primary">Clear</button>
                    </div>
                    <!-- end of buttons  -->

                    <form action="{{ route('approval-rule-conditions.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="mas_approval_rule_id" value="{{ $rule->id }}">
                        <!-- formula -->
                        <div class="row">
                            <div class="col-12">
                                <label for="formula">Formula :</label>
                                <textarea class="form-control" name="formula_display" id="formula" readonly required></textarea>
                                <input type="hidden" name="formula" id="formula-hidden" required>
                            </div>
                        </div>
                        <!-- end of formula -->
                        <br>

                        <div class="row">
                            <h4>Approval</h4>
                            <br>
                            <!-- hierarchy -->
                            <div class="row">
                                <div class="form-group col-2">
                                    <label style="font-weight:400">
                                        <input type="radio"name="approval_option" class="approval-option"
                                            id="hierarchy" value="1" required>
                                        Hierarchy
                                    </label>
                                </div>
                                <div class="form-group col-4">
                                    <label for="system-hierarchy-id">Name </label>
                                    <select name="system_hierarchy_id" id="system_hierarchy_id" class="form-control"
                                        disabled>
                                        <option value="" disabled selected hidden>Select your option</option>
                                        @foreach ($hierarchies as $hierarchy)
                                            <option value="{{ $hierarchy->id }}">{{ $hierarchy->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label for="max-level">Max Level </label>
                                    <select class="form-control" name="max_level_id" id="max_level" disabled>
                                        <option value="" disabled selected hidden>Select your option</option>
                                    </select>
                                </div>
                            </div>
                            <!-- end of hierarchy -->

                            <!-- single user -->
                            <div class="row">
                                <div class="col-2">
                                    <label style="font-weight:400">
                                        <input type="radio" name="approval_option" class="approval-option"
                                            id="single_user" value="2" required>
                                        Single User
                                    </label>
                                </div>
                                <div class="form-group col-4">
                                    <label for="employee">Employee </label>
                                    <select class="form-control" name="appvl_employee_id" id="employee" disabled>
                                        <option value="" disabled selected hidden>Select your option</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}
                                                ({{ $employee->employee_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- end of single user -->

                            <!-- auto approval -->
                            <div class="row">
                                <div class="col-2">
                                    <label style="font-weight:400">
                                        <input type="radio" name="approval_option" class="approval-option"
                                            id="auto_approval" value="3" required>
                                        Auto Approval
                                    </label>
                                </div>
                            </div>
                            <!-- end of auto approval -->
                        </div>

                        <!-- FYI -->
                        <div class="row my-4">
                            <h4>FYI</h4>
                            <div class="form-group col-2">
                                <label style="font-weight:400">
                                    <input type="checkbox" class="fyi-checkbox" id="fyi">
                                    FYI
                                </label>
                            </div>

                            <div class="col-3">
                                <label for="fyi-level">Frequency </label>
                                <select class="form-control" id="fyi-level" name="fyi_level" disabled>
                                    <option value="" disabled selected hidden>Select your option</option>
                                    @foreach (config('global.level_with_all') as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-3">
                                <label for="fyi-email">Email </label>
                                <input type="email" class="form-control" id="fyi-email" name="fyi_email" disabled>
                            </div>

                            <div class="col-3">
                                <label for="fyi-employee-id">Employee </label>
                                <select class="form-control" id="fyi-employee-id" name="fyi_employee_id" disabled>
                                    <option value="" disabled selected hidden>Select your option</option>

                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}
                                            ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- FYI -->

                        <!-- buttons -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary ">Add</button>
                        </div>
                        <!-- end of buttons  -->

                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#mas_approval_head_id').on('change', function() {
                var approvalHeadId = $(this).val();

                $('#approvable_id').empty().append(
                    '<option value="" disabled selected hidden>Select your option</option>'
                );

                $('#mas_approval_head_type_id').empty().append(
                    '<option value="" disabled selected hidden>Select your option</option>'
                );

                $('#mas_condition_field_id').empty().append(
                    '<option value="" disabled selected hidden>Select your option</option>'
                );

                if (approvalHeadId) {
                    $.ajax({
                        url: '/getapprovalheadtypesbyapprovalhead/' + approvalHeadId,
                        type: 'GET',
                        success: function(response) {
                            if (response && Array.isArray(response)) {
                                $.each(response, function(key, value) {
                                    if (value && value.id && value.name) {
                                        $('#approvable_id').append(
                                            '<option value="' + value.id + '">' +
                                            value.name + '</option>'
                                        );
                                    }
                                });
                            } else {
                                console.error(
                                    'Invalid response format for approval head types:',
                                    response);
                                alert('Failed to fetch approval head types.');
                            }
                        },
                        error: function() {
                            alert('Error: Unable to fetch approval head types.');
                        }
                    });

                    $.ajax({
                        url: '/getapprovalruleconditionfieldsbyhead/' + approvalHeadId,
                        type: 'GET',
                        success: function(response) {
                            if (response && Array.isArray(response)) {
                                $.each(response, function(key, value) {
                                    if (value && value.id && value.name) {
                                        $('#mas_condition_field_id').append(
                                            '<option value="' + value.id + '">' +
                                            value.name + '</option>'
                                        );
                                    }
                                });
                            } else {
                                console.error('Invalid response format for condition fields:',
                                    response);
                                alert('Failed to fetch approval rule condition fields.');
                            }
                        },
                        error: function() {
                            alert('Error: Unable to fetch approval rule condition fields.');
                        }
                    });

                    $('#approvable_id').off('change').on('change', function() {
                        var approvableId = $(this).val();
                        var approvableLabel = $('#approvable_id option:selected').text();

                        $('#mas_approval_head_type_id').empty().append(
                            '<option value="' + approvableId + '" readonly selected>' +
                            approvableLabel + '</option>'
                        );
                    });
                }
            });

            $(document).on('change', 'select[required], input[required], textarea[required]', function() {
                if ($(this).val() !== "") {
                    removeMessage($(this));
                }
            });

            $(document).on('input', 'input[required], textarea[required]', function() {
                if ($(this).val() !== "") {
                    removeMessage($(this));
                }
            });

            function addMessage(curField, message) {
                curField.addClass('validation-error');
                // Remove existing validation messages before adding new ones
                curField.closest('.form-group').find('.validation-message').remove();

                if (curField.closest('.form-group').find('.validation-message').length === 0) {
                    curField.closest('.form-group').find('label').append(
                        "<span style='color:red;' class='validation-message'> " + message + "</span>"
                    );
                }

                if (curField.hasClass('select2')) {
                    curField.closest('.form-group').find('.select2-container').addClass('validation-error');
                }
            }

            function removeMessage(curField) {
                curField.removeClass('validation-error');
                curField.closest('.form-group').find('.validation-message').remove();
                if (curField.hasClass('select2')) {
                    curField.closest('.form-group').find('.select2-container').removeClass('validation-error');
                }
            }

            function validate(container) {
                var formValid = true;

                // Reset all error states before validating again
                container.find('.validation-error').removeClass('validation-error');
                container.find('.validation-message').remove();

                var requiredFields = container.find('input[required], select[required], textarea[required]');
                var regexFields = container.find("[data-regex]");
                var dateFields = container.find("input[type='date']");

                // Validate required fields
                requiredFields.each(function() {
                    var curField = $(this);
                    var fieldValue = $(this).val();

                    if (curField.is('select')) {
                        if (curField.find('option:selected').val() === "") {
                            formValid = false;
                            addMessage(curField, "This field is required!");
                        }
                    } else {
                        if (fieldValue === "") {
                            formValid = false;
                            addMessage(curField, "This field is required!");
                        }
                    }
                });

                // Validate date fields
                dateFields.each(function() {
                    var curField = $(this);
                    var fieldValue = $(this).val();
                    if (fieldValue !== '') {
                        var date = new Date(fieldValue);
                        var maxDate = new Date(curField.attr('max'));
                        if (date > maxDate) {
                            formValid = false;
                            addMessage(curField, "Date must not be greater than " + curField.attr('max'));
                        }
                    }
                });

                // Validate regex fields
                regexFields.each(function() {
                    var curField = $(this);
                    var regCheck = curField.data('regex');
                    var fieldValue = $(this).val();
                    if (fieldValue.trim() !== "") {
                        const regex = new RegExp(regCheck, 'gm');
                        const str = fieldValue.trim();
                        const result = regex.test(str);

                        if (!result) {
                            formValid = false;
                            addMessage(curField, curField.data('message'));
                        } else {
                            curField.removeClass('validation-error');
                            curField.closest('.form-group').find('.validation-message').remove();
                            if (curField.hasClass('select2')) {
                                curField.closest('.form-group').find('.select2-container').removeClass(
                                    'validation-error');
                            }
                        }
                    } else {
                        var isRequiredCheck = curField.attr('data-validation');
                        if (isRequiredCheck !== undefined) {
                            curField.removeClass('validation-error');
                            curField.closest('.form-group').find('.validation-message').remove();
                            if (curField.hasClass('select2')) {
                                curField.closest('.form-group').find('.select2-container').removeClass(
                                    'validation-error');
                            }
                            formValid = false;
                            addMessage(curField, "This field is required!");
                        }
                    }
                });

                return formValid;
            }

            $('#conditions-btn').on('click', function(e) {
                e.preventDefault();

                const isFormValid = validate($("#rule-container"));

                if (isFormValid) {
                    $('#conditions').modal('show');
                } else {
                    return false;
                }
            });

            // When the approval type changes, check if formula is empty and toggle delimiter requirements
            $('#mas_approval_head_type_id').on('change', function() {
                var typeSelected = $('#mas_approval_head_type_id').val();
                var formula = $('#formula').val();
                var formulaHidden = $('#formula-hidden').val();

                // Enable delimiter if type is selected and formula is not empty
                if (typeSelected && formula && formulaHidden) {
                    $('#delimiter').prop('disabled', false);
                    $('#delimiter').prop('required', true);
                    $('label[for="delimiter"]').append(
                        '<span class="text-danger required-marker">*</span>');

                } else {
                    $('#delimiter').prop('disabled', true);
                    $('#delimiter').prop('required', false);
                    $('label[for="delimiter"] .required-marker').remove();
                }
            });

            $('#mas_condition_field_id').on('change', function() {
                var fieldId = $(this).val();

                if (fieldId) {
                    $.ajax({
                        url: '/getapprovalruleconditionfieldbyid/' + fieldId,
                        type: 'GET',
                        success: function(response) {
                            if (response.has_employee_field) {
                                if ($('#employee-container').length === 0) {
                                    $('#value-container').remove();
                                    $('#condition_value').prop('required', false);

                                    $.ajax({
                                        url: '/getemployees',
                                        type: 'GET',
                                        success: function(employeeResponse) {
                                            if (Array.isArray(employeeResponse) &&
                                                employeeResponse.length > 0) {
                                                var employeeDropdown =
                                                    '<div id="employee-container" class="form-group col-3">';
                                                employeeDropdown +=
                                                    '<label for="employee">Employee <span class="text-danger required-marker">*</span></label>';
                                                employeeDropdown +=
                                                    '<select class="form-control" name="mas_employee_id" id="mas_employee_id" required>';
                                                employeeDropdown +=
                                                    '<option value="" disabled selected hidden>Select an employee</option>';

                                                $.each(employeeResponse, function(
                                                    index,
                                                    employee) {
                                                    employeeDropdown +=
                                                        '<option value="' +
                                                        employee.id + '">' +
                                                        employee.name +
                                                        ' (' +
                                                        employee
                                                        .employee_id +
                                                        ')'
                                                    '</option>';
                                                });

                                                employeeDropdown +=
                                                    '</select></div>';

                                                // Append the employee dropdown next to the "Operator" field
                                                $('#operator').closest(
                                                        '.form-group')
                                                    .after(employeeDropdown);
                                            } else {
                                                alert('No employees found.');
                                            }
                                        },
                                        error: function() {
                                            alert('Error fetching employees.');
                                        }
                                    });
                                }
                            } else {
                                if ($('#value-container').length === 0) {
                                    $('#employee-container').remove();
                                    $('#mas_employee_id').prop('required', false);

                                    // If no employee field is required, append a default value container
                                    var defaultValueContainer =
                                        '<div id="value-container" class="form-group col-3">';
                                    defaultValueContainer +=
                                        '<label for="value">Value <span class="text-danger required-marker">*</span></label>';
                                    defaultValueContainer +=
                                        '<input type="number" class="form-control" name="value" id="condition_value" required placeholder="Enter a value">';
                                    defaultValueContainer += '</div>';

                                    // Append the default value container after the Operator field container
                                    $('#operator').closest('.form-group').after(
                                        defaultValueContainer);
                                }
                            }

                        },
                        error: function() {
                            alert('Error: Unable to fetch approval rule condition fields.');
                        }
                    });
                } else {
                    alert('Please select a condition fields.');
                }
            })

            // Add condition to formula
            $('#addCriteria').on('click', function() {
                var delimiter = $('#delimiter').val();
                var field = $('#mas_condition_field_id').val();
                var fieldText = $('#mas_condition_field_id option:selected').text();

                var operator = $('#operator').val();
                var operatorText = $('#operator option:selected').text();

                var value;
                var valueText;

                if ($('#condition_value').length > 0 && $('#condition_value').val()) {
                    value = $('#condition_value').val();
                    valueText = $('#condition_value').val();
                } else if ($('#mas_employee_id').length > 0 && $('#mas_employee_id').val()) {
                    value = $('#mas_employee_id').val();
                    valueText = $('#mas_employee_id option:selected').text();
                }


                var formula = $('#formula').val();
                var formulaHidden = $('#formula-hidden').val();

                const isFormValid = validate($("#conditions-row"));

                if (isFormValid) {
                    // Append delimiter only if formula already has a condition
                    if ((formulaHidden && formula) && delimiter) {
                        formulaHidden = formulaHidden + ' [' + delimiter + '] ' + field + ' ' + operator +
                            ' ' + value;
                        formula = formula + ' ' + delimiter + ' ' + fieldText + ' ' + operatorText + ' ' +
                            valueText;
                    } else {
                        formulaHidden = field + ' ' + operator + ' ' + value;
                        formula = fieldText + ' ' + operatorText + ' ' + valueText;
                    }

                    // Update the formula textarea
                    $('#formula').val(formula);
                    $('#formula-hidden').val(formulaHidden);

                    $('#delimiter').val('').prop('disabled', true).prop('required', false);
                    $('#mas_condition_field_id').val('');
                    $('#operator').val('');
                    $('#condition_value').val('');
                    $('#mas_employee_id').val('');

                    // Enable delimiter if formula is not empty
                    if (formula && formulaHidden) {
                        $('#delimiter').prop('disabled', false);
                        $('#delimiter').prop('required', true);
                        $('label[for="delimiter"]').append(
                            '<span class="text-danger required-marker">*</span>');

                    } else {
                        $('#delimiter').prop('disabled', true);
                        $('#delimiter').prop('required', false);
                        $('label[for="delimiter"] .required-marker').remove();
                    }
                } else {
                    return false;
                }
            });

            // Clear form fields on reset
            function clearConditionFields() {
                $('#delimiter').val('').prop('disabled', true).prop('required', false);
                $('#mas_condition_field_id').val('');
                $('#operator').val('');
                $('#value').val('');
                $('#formula').val('');
                $('#formula-hidden').val('');
            }
            $('button[type="reset"]').on('click', function() {
                clearConditionFields();
            });

            $('input[name="approval_option"]').on('change', function() {
                var selectedOption = $(this).attr('id');

                $('#system_hierarchy_id').prop('disabled', true);
                $('#max_level').prop('disabled', true);
                $('#employee').prop('disabled', true);

                $('#system_hierarchy_id').prop('required', false);
                $('#max_level').prop('required', false);
                $('#employee').prop('required', false);

                if (selectedOption === 'hierarchy') {
                    $('#system_hierarchy_id').prop('disabled', false);
                    $('#max_level').prop('disabled', false);

                    $('#system_hierarchy_id').prop('required', true);
                    $('#max_level').prop('required', true);

                    $('label[for="system-hierarchy-id"]').append(
                        '<span class="text-danger required-marker">*</span>');
                    $('label[for="max-level"]').append(
                        '<span class="text-danger required-marker">*</span>');

                    $('label[for="employee"] .required-marker').remove();

                    $('#employee').val('');
                } else if (selectedOption === 'single_user') {
                    $('#employee').prop('disabled', false);
                    $('#employee').prop('required', true);

                    $('label[for="employee"]').append('<span class="text-danger required-marker">*</span>');

                    $('#system_hierarchy_id').val('');
                    $('#max_level').val('');

                    $('label[for="system-hierarchy-id"] .required-marker').remove();
                    $('label[for="max-level"] .required-marker').remove();
                } else if (selectedOption === 'auto_approval') {
                    $('#system_hierarchy_id').val('');
                    $('#max_level').val('');
                    $('#employee').val('');

                    $('#system_hierarchy_id').prop('required', false);
                    $('#max_level').prop('required', false);
                    $('#employee').prop('required', false);

                    $('label[for="system-hierarchy-id"] .required-marker').remove();
                    $('label[for="max-level"] .required-marker').remove();
                    $('label[for="employee"] .required-marker').remove();
                }
            });

            $('#system_hierarchy_id').on('change', function() {
                var systemHierarchyId = $(this).val();

                if (systemHierarchyId) {
                    $.ajax({
                        url: '/getsystemhierarchylevelsbyhierarchyid/' + systemHierarchyId,
                        type: 'GET',
                        success: function(response) {
                            if (response) {
                                if (response && Array.isArray(response)) {

                                    $('#max_level').empty();
                                    $.each(response, function(
                                        key, value) {
                                        if (value && value.id &&
                                            value.level) {
                                            $('#max_level')
                                                .append(
                                                    '<option value="' +
                                                    value.id +
                                                    '">' + value
                                                    .level +
                                                    '</option>'
                                                );
                                        }
                                    });

                                } else {
                                    alert(
                                        'Failed to fetch approval rule max levels.'
                                    );
                                }
                            }
                        },
                        error: function() {

                            alert('Error: Unable to fetch levels.');
                        }
                    });
                }
            });

            $('#fyi').on('change', function() {
                var fyi = $(this).is(':checked');

                if (fyi) {
                    $('#fyi-level').attr('disabled', false).prop('required', true)
                    $('#fyi-email').attr('disabled', false).prop('required', true)
                    $('#fyi-employee-id').attr('disabled', false).prop('required', true)

                    $('label[for="fyi-level"]').append(
                        '<span class="text-danger required-marker">*</span>');
                    $('label[for="fyi-email"]').append(
                        '<span class="text-danger required-marker">*</span>');
                    $('label[for="fyi-employee-id"]').append(
                        '<span class="text-danger required-marker">*</span>');

                } else {
                    $('#fyi-level').attr('disabled', true).prop('required', false)
                    $('#fyi-email').attr('disabled', true).prop('required', false)
                    $('#fyi-employee-id').attr('disabled', true).prop('required', false)

                    $('#fyi-level').val('')
                    $('#fyi-email').val('')
                    $('#fyi-employee-id').val('')

                    $('label[for="fyi-level"] .required-marker').remove();
                    $('label[for="fyi-email"] .required-marker').remove();
                    $('label[for="fyi-employee-id"] .required-marker').remove();
                }
            });
        });
    </script>
@endpush
