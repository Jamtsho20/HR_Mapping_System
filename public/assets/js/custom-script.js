var hrms = function() {
    function initialize() {
        //Ajax to fetch gewogs based on dzongkhag section
        $(document).on("change", "#dzongkhag_search", function() {
            var dzongkhagId = $("#dzongkhag_search").val();
            if (dzongkhagId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgewogbydzongkhag/" + dzongkhagId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gewogs = data;
                        var html = "<option value='' disabled selected hidden>Select Dzongkhag</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .name + "</option>";
                        }
                        $("#gewog_search").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        $(document).on("change", "#dzongkhag_id", function() {
            var dzongkhagId = $("#dzongkhag_id").val();
            if (dzongkhagId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgewogbydzongkhag/" + dzongkhagId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gewogs = data;
                        var html = "<option value='' disabled selected hidden>Select a gewog</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .name + "</option>";
                        }
                        $("#gewog_id").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        //populate village based on selection of gewogs
        $(document).on("change", "#gewog_id", function() {
            var gewogId = $("#gewog_id").val();
            if (gewogId !== '') {
                //ajax call
                $.ajax({
                    url: "/getvillagebygewog/" + gewogId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gewogs = data;
                        var html = "<option value='' disabled selected hidden>Select a gewog</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .village + "</option>";
                        }
                        $("#village_id").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        //dzongkhag and gewog population for present address
        $(document).on("change", "#mas_dzongkhag_id", function() {
            var dzongkhagId = $("#mas_dzongkhag_id").val();
            if (dzongkhagId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgewogbydzongkhag/" + dzongkhagId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gewogs = data;
                        var html = "<option value='' disabled selected hidden>Select a gewog</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .name + "</option>";
                        }
                        $("#mas_gewog_id").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        //populate village based on selection of gewogs
        $(document).on("change", "#gewog_id", function() {
            var gewogId = $("#gewog_id").val();
            if (gewogId !== '') {
                //ajax call
                $.ajax({
                    url: "/getvillagebygewog/" + gewogId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gewogs = data;
                        var html = "<option value='' disabled selected hidden>Select a village</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .village + "</option>";
                        }
                        $("#village_id").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        //populate section based on selection of department
        $(document).on("change", "#department_id", function() {
            var departmentId = $("#department_id").val();
            if (departmentId !== '') {
                //ajax call
                $.ajax({
                    url: "/getsectionbydepartment/" + departmentId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var section = data;
                        var html = "<option value='' disabled selected hidden>Select a section</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .name + "</option>";
                        }
                        $("#section_id").html(html);
                        // $("#section_id").prop('disabled', false);
                    }
                });
            } else {
                var html = "<option value='' disabled selected hidden>No Section Availaible</option>";
                $("#section_id").html(html);
            }
        });

        //populate grade step based on selection of grade
        $(document).on("change", "#grade_id", function() {
            var gradeId = $("#grade_id").val();
            var gradeStepSelect = $("#grade_step_id");
            // var stepPointSelect = $("#step_point");
            if (gradeId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgradestepbygrade/" + gradeId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gradeStep = data;
                        var html = "<option value='' data-starting-salary='' data-point='' disabled selected hidden>Select a grade step</option>";
                        for (var x in data) {
                            html += "<option value='" + data[x].id + "' data-starting-salary='" + data[x].starting_salary + "' data-point='" + data[x].point + "'>" + data[x].name + "</option>";
                        }
                        gradeStepSelect.html(html);
                    }
                });
            } else {
                gradeStepSelect.html("<option value='' data-point='' disabled selected hidden>No grade step availaible.</option>");
                // stepPointSelect.html("<option value='' data-point='' disabled selected hidden>Select a grade step</option>"); // Enable Step Point
            }
        });

        //populate payscale and basic pay based on gradestep
        $(document).on("change", "#grade_step_id", function() {
            var gradeStepId = $("#grade_step_id").val();
            if (gradeStepId !== '') {
                //ajax call
                $.ajax({
                    url: "/getpayscalebygradestep/" + gradeStepId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        // var payScale = data.pay_scale;
                        $("#pay_scale").val(data[0].starting_salary + ' - ' + data[0].increment + ' - ' + data[0].ending_salary); // set the value for pay scale
                        $("#basic_pay").val(data[0].starting_salary); // set the value for pay scale
                    }
                });
            } else {
                $("#pay_scale").val('');
                $("#basic_pay").val('');
            }
        });

        //populate leave balnce based on selection of leaveType
        $(document).ready(function() {
            // Function to populate leave balance based on leaveType
            function populateLeaveBalance() {
                var leaveType = $("#leave_type").val();
                var formId = $("#apply_leave");
                if (leaveType !== '') {
                    // ajax call
                    $.ajax({
                        url: "/getleavebalancebyleavetype/" + leaveType,
                        dataType: "JSON",
                        type: "GET",
                        success: function(data) {
                            $("#leave_balance").val(data.balance); // set the value for leave balance
                            // Disable form fields if balance is 0
                            if (data.balance == 0) {
                                formId.find("input, select, textarea").prop("disabled", true); // disable fields in formId only
                                $("#leave_type").prop("disabled", false);
                            } else {
                                $("form input, form select, form textarea").prop("disabled", false); // enable all input fields
                            }
                            if (data.attachment_required && !$("#attachment").attr('data-has-attachment')) {
                                $("#attachment").attr("required", "required");
                                $("#attachment_required").show();
                            } else {
                                $("#attachment").removeAttr("required");
                                $("#attachment_required").hide();
                            }
                        }
                    });
                } else {
                    $("#leave_balance").val('');
                }
            }
        
            // Trigger on page load (during edit)
            populateLeaveBalance();
        
            // Trigger on change of leave type
            $(document).on("change", "#leave_type", function() {
                populateLeaveBalance();
            });
        });

        //calculate no of leave days based on from date, to date, excluding holidays
        $(document).on("change", "#from_date, #to_date, #ddl_from_day, #ddl_to_day", function() {
            var fromDate = $("#from_date").val();
            var toDate = $("#to_date").val();
            var fromDay = $("#ddl_from_day").val();
            var toDay = $("#ddl_to_day").val();

            if (fromDate !== '' && toDate !== '' && fromDay !== '' && toDay !== '') {
                //ajax call
                $.ajax({
                    url: "/getnoofdaysbydate/",
                    data: { fromDate: fromDate, toDate: toDate, fromDay: fromDay, toDay: toDay},
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        $("#no_of_days").val(data); // set the value for leave balance
                    }
                });
            } else {
                $("#no_of_days").val('');
            }
        });

        //show employee field for hierarchy level based on selection of approving authority
        // employee_select
        $(document).on("change", ".approving-authority-select", function() {
            var approvingAuthorityId = $(this).val();
            var row = $(this).closest('tr');  // Get the current row
            var employeeSelect = row.find('.employee-select');
            
            // Show the employee dropdown by default
            employeeSelect.show();
        
            if (approvingAuthorityId !== '') {
                // Make an AJAX request to fetch employees based on approving authority
                $.ajax({
                    url: "/getemployeebyapprovingauthority/" + approvingAuthorityId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(response) {
                        if (response.has_employee_field) {
                            // If there are employees, populate the dropdown
                            if (response.employees.length > 0) {
                                employeeSelect.empty();
                                employeeSelect.append('<option value="" disabled selected hidden>Select Employee</option>');
                                
                                // Populate the employee select with data from the response
                                $.each(response.employees, function(index, employee) {
                                    employeeSelect.append('<option value="' + employee.id + '">' + employee.username + ' - ' + employee.name + '</option>');
                                });
                            } else {
                                alert('Employee has not been set for selected approver, please contact system admin for further information!')
                                // If no employees found, clear and show a placeholder
                                employeeSelect.empty();
                                employeeSelect.append('<option value="" disabled selected hidden>No employees found</option>');
                            }
                        } else {
                            // If 'has_employee_field' is false, hide and clear the dropdown
                            alert('You don`t need to select employee for selected approver!')
                            employeeSelect.empty();
                            employeeSelect.append('<option value="" disabled selected hidden>No employees found</option>');
                            // employeeSelect.hide();
                        }
                    },
                    error: function() {
                        employeeSelect.empty();
                        employeeSelect.append('<option value="" disabled selected hidden>Error loading employees</option>');
                    }
                });
            } else {
                // If no approving authority selected, clear and hide the dropdown
                employeeSelect.empty();
                employeeSelect.hide();
            }
        });

        //generating advance no based on advance types
        $(document).on('change', '#advance_type', function(){
            var advanceTypeId = $(this).val();
            if(advanceTypeId !== ''){
                $.ajax({
                    url: "/getadvancenobyadvancetype/" + advanceTypeId,
                    dataType: "JSON",
                    type: "GET",

                    success: function(response) {
                        $('#advance_no').val(response.advance_no) 
                        if(response.sifa_interest_rate != 0){
                            $('#interest_rate_sifa').val(response.sifa_interest_rate);
                        }
                    },
                    error: function(response) {
                        alert('Something went wrong, please contact system admin for further information!');
                    }
                });
                if(advanceTypeId == 4){ // external api from SOMs will be called here to get Item Types(name, code and amount)
                    $.ajax({
                        url: 'https://external-application.com/api/endpoint', // External API URL
                        dataType: 'JSON',
                        type: 'GET',
                        success: function(response) {
                            // Handle the response from the external API
                            // $('#item_type').val(response.advance_no); // Example field for external response
                        },
                        error: function(response) {
                            console.log(response.error);
                            alert('Something went wrong with the SOM`s API, please contact system admin for further information!');
                        }
                    });
                }
            }
        })

        //populate expense details based on selection of expense types for validation purpose
        $(document).on('change', '#expense_type', function() {
            var expenseType = $(this).val();
            var formId = $("#expense_form");
            if(expenseType !== ''){
                $.ajax({
                    url: "/getmaxexpenseamountbyexpensetype/" + expenseType,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data){
                        console.log(data)
                    }
                });
            }else{
                return;
            }
        });
        
        //END

        //turn off all the autocomplete feature within the forms
        $('form').find('input').attr('autocomplete', 'off');

        //reset filters
        $('#form-reset').on('click', function() {
            let form = $('#filter-form');
            form.find('select').each(function() {
                $(this).prop('selectedIndex', 0);
            });
            form.find('input').each(function() {
                $(this).val('');
            });
            form.submit();
        });

        // edit modal script
        $('.edit-btn').click(function(e) {
            e.preventDefault(); // Prevent the default action if needed
            var url = $(this).data('url');
            var updateUrl = $(this).data('update-url');

            $.get(url)
                .done(function(data) {
                    // Populate form fields with the fetched data
                    $.each(data, function(key, value) {
                        var field = $('#edit-modal-form').find('[name="' + key + '"]');
                        if (field.length) {
                            field.val(value || '');
                        }
                    });

                    // Set the form's action URL
                    $('#edit-modal-form').attr('action', updateUrl);

                    // Show the modal
                    $('#edit-modal').modal('show');
                })
                .fail(function(error) {
                    console.error('Error:', error);
                });
        });
// add modal script
$('.add-btn').click(function(e) {
    e.preventDefault(); // Prevent the default action if needed
    var url = $(this).data('url');
    var updateUrl = $(this).data('update-url');

    $.get(url)
        .done(function(data) {
            // Populate form fields with the fetched data
            $.each(data, function(key, value) {
                var field = $('#add-modal-form').find('[name="' + key + '"]');
                if (field.length) {
                    field.val(value || '');
                }
            });

            // Set the form's action URL
            $('#add-modal-form').attr('action', updateUrl);

            // Show the modal
            $('#add-modal').modal('show');
        })
        .fail(function(error) {
            console.error('Error:', error);
        });
});

    }
    return {
        Initialize: initialize
    }
}();

$(document).ready(function() {
    hrms.Initialize();
});

//form validation and button for tabs
$(document).ready(function () {
    function updateNavigationButtons() {
        var $currentTab = $('.steps .current');
        var $nextTab = $currentTab.next();

        // Show or hide the Previous button based on the current tab
        if ($currentTab.hasClass('first')) {
            $('#previous-button').hide();
        } else {
            $('#previous-button').show();
        }

        // Show or hide the Next button based on whether there's a next tab
        if ($nextTab.length) {
            $('#next-button').show();
            $('#submit-button').hide();
        } else {
            $('#next-button').hide();
            $('#submit-button').show();
        }
    }

    function validateCurrentForm() {
        var isValid = true;

        // Check all required fields in the current and previous tabs
        $('.content .body:visible').each(function () {
            $(this).find(':input[required]').each(function () {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid'); // Add a class to highlight the input
                } else {
                    $(this).removeClass('is-invalid'); // Remove the class if the input is filled
                }
            });
        });

        return isValid;
    }

    $('#next-button').on('click', function (e) {
        e.preventDefault();

        // Validate the current form before proceeding
        if (!validateCurrentForm()) {
            alert('Please fill all required fields.');
            return;
        }

        // Find the current tab and its content panel
        var $currentTab = $('.steps .current');
        var $currentPanel = $('#' + $currentTab.find('a').attr('aria-controls'));

        // Find the next tab and its content panel
        var $nextTab = $currentTab.next();
        if ($nextTab.length) {
            var $nextPanel = $('#' + $nextTab.find('a').attr('aria-controls'));

            // Update tabs
            $currentTab.removeClass('current').attr('aria-selected', 'false').addClass('disabled').attr('aria-disabled', 'true');
            $nextTab.addClass('current').attr('aria-selected', 'true').removeClass('disabled').attr('aria-disabled', 'false');

            // Update panels
            $currentPanel.hide().attr('aria-hidden', 'true');
            $nextPanel.show().attr('aria-hidden', 'false');

            // Update navigation buttons
            updateNavigationButtons();
        }
    });

    $('#previous-button').on('click', function (e) {
        e.preventDefault();

        // Find the current tab and its content panel
        var $currentTab = $('.steps .current');
        var $currentPanel = $('#' + $currentTab.find('a').attr('aria-controls'));

        // Find the previous tab and its content panel
        var $prevTab = $currentTab.prev();
        if ($prevTab.length) {
            var $prevPanel = $('#' + $prevTab.find('a').attr('aria-controls'));

            // Update tabs
            $currentTab.removeClass('current').attr('aria-selected', 'false').addClass('disabled').attr('aria-disabled', 'true');
            $prevTab.addClass('current').attr('aria-selected', 'true').removeClass('disabled').attr('aria-disabled', 'false');

            // Update panels
            $currentPanel.hide().attr('aria-hidden', 'true');
            $prevPanel.show().attr('aria-hidden', 'false');

            // Update navigation buttons
            updateNavigationButtons();
        }
    });

    $('#submit-button').on('click', function (e) {
        if (!validateCurrentForm()) {
            e.preventDefault();
            alert('Please fill all required fields.');
        } else {
            $('#emp-form').submit();
            $('#leave-form').submit();

        }
    });

    // Initialize navigation buttons
    updateNavigationButtons();
});