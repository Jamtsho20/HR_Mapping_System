@extends('layouts.app')
@section('page-title', 'Approval Rules')
@section('content')
    <div class="card">
        <form action="{{ route('approval-rules.store') }}" method="POST">
            @csrf
            <div class="card-header ">
                <h3 class="card-title">Add Approval Rule</h3>
            </div>
            <div class="card-body">
                <div class="row" id="rule-container">
                    <div class="form-group col-4">
                        <label for="mas_approval_head_id">For <span class="text-danger">*</span></label>
                        <select class="form-control" name="mas_approval_head_id" id="mas_approval_head_id" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($heads as $head)
                                <option value="{{ $head->id }}">{{ $head->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <label for="approvable_id">Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="approvable_id" id="approvable_id" required>
                            <option value="" disabled selected hidden>Select your option</option>
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <label for="name">Rule Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group col-4">
                        <label for="start_date">Start Date </label>
                        <input type="date" class="js-datepicker form-control js-datepicker" id="start_date"
                            name="start_date" data-week-start="1" data-autoclose="true" data-today-highlight="true"
                            data-date-format="mm/dd/yy" placeholder="mm/dd/yy" required>
                    </div>
                    <div class="form-group col-4">
                        <label for="end_date">End Date </label>
                        <input type="date" class="js-datepicker form-control js-datepicker" id="end_date"
                            name="end_date" data-week-start="1" data-autoclose="true" data-today-highlight="true"
                            data-date-format="mm/dd/yy" placeholder="mm/dd/yy" required>
                    </div>

                    <div class="form-group col-4">
                        <label for="is_active">Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="is_active" required>
                            @foreach (config('global.status') as $key => $type)
                                <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-check"></i> Save
                </button>
                <a href="{{ route('approval-rules.index') }}" class="btn btn-danger">CANCEL</a>
            </div>
        </form>
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
        });
    </script>
@endpush
