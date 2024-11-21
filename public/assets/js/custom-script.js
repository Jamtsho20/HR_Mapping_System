var hrms = function () {
    function initialize() {
        //Ajax to fetch gewogs based on dzongkhag section
        $(document).on("change", "#dzongkhag_search", function () {
            var dzongkhagId = $("#dzongkhag_search").val();
            if (dzongkhagId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgewogbydzongkhag/" + dzongkhagId,
                    dataType: "JSON",
                    type: "GET",
                    success: function (data) {
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

        $(document).on("change", "#dzongkhag_id", function () {
            var dzongkhagId = $("#dzongkhag_id").val();
            if (dzongkhagId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgewogbydzongkhag/" + dzongkhagId,
                    dataType: "JSON",
                    type: "GET",
                    success: function (data) {
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
        $(document).on("change", "#gewog_id", function () {
            var gewogId = $("#gewog_id").val();
            if (gewogId !== '') {
                //ajax call
                $.ajax({
                    url: "/getvillagebygewog/" + gewogId,
                    dataType: "JSON",
                    type: "GET",
                    success: function (data) {
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
        $(document).on("change", "#mas_dzongkhag_id", function () {
            var dzongkhagId = $("#mas_dzongkhag_id").val();
            if (dzongkhagId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgewogbydzongkhag/" + dzongkhagId,
                    dataType: "JSON",
                    type: "GET",
                    success: function (data) {
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
        $(document).on("change", "#gewog_id", function () {
            var gewogId = $("#gewog_id").val();
            if (gewogId !== '') {
                //ajax call
                $.ajax({
                    url: "/getvillagebygewog/" + gewogId,
                    dataType: "JSON",
                    type: "GET",
                    success: function (data) {
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
        $(document).on("change", "#department_id", function () {
            var departmentId = $("#department_id").val();
            if (departmentId !== '') {
                //ajax call
                $.ajax({
                    url: "/getsectionbydepartment/" + departmentId,
                    dataType: "JSON",
                    type: "GET",
                    success: function (data) {
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
        $(document).on("change", "#grade_id", function () {
            var gradeId = $("#grade_id").val();
            var gradeStepSelect = $("#grade_step_id");
            // var stepPointSelect = $("#step_point");
            if (gradeId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgradestepbygrade/" + gradeId,
                    dataType: "JSON",
                    type: "GET",
                    success: function (data) {
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
        $(document).on("change", "#grade_step_id", function () {
            var gradeStepId = $("#grade_step_id").val();
            if (gradeStepId !== '') {
                //ajax call
                $.ajax({
                    url: "/getpayscalebygradestep/" + gradeStepId,
                    dataType: "JSON",
                    type: "GET",
                    success: function (data) {
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
        $(document).ready(function () {
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
                        success: function (data) {
                            if(data.leavePolicy && !data.leavePolicy.status){
                                alert('You cannot apply leave as leave policy for this leave type has not been enforced, please contact system admin for further information!')
                                formId.find("input, select, textarea").prop("disabled", true); // disable fields in formId only
                                $("#leave_type").prop("disabled", false);
                            }else if(data.leavePolicy && data.leavePolicy.is_information_only){
                                alert('You cannot apply leave as leave policy for this leave type is for information purpose only, please contact system admin for further information!')
                                formId.find("input, select, textarea").prop("disabled", true); // disable fields in formId only
                                $("#leave_type").prop("disabled", false);
                            }else{
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
                        }
                    });
                } else {
                    $("#leave_balance").val('');
                }
            }

            // Trigger on page load (during edit)
            populateLeaveBalance();

            // Trigger on change of leave type
            $(document).on("change", "#leave_type", function () {
                populateLeaveBalance();
            });
        });

        //calculate no of leave days based on from date, to date, excluding holidays
        // $(document).on("change", "#from_date, #to_date, #ddl_from_day, #ddl_to_day", function() {
        //     var fromDate = $("#from_date").val();
        //     var toDate = $("#to_date").val();
        //     var fromDay = $("#ddl_from_day").val();
        //     var toDay = $("#ddl_to_day").val();

        //     if (fromDate !== '' && toDate !== '' && fromDay !== '' && toDay !== '') {
        //         //ajax call
        //         $.ajax({
        //             url: "/getnoofdaysbydate/",
        //             data: { fromDate: fromDate, toDate: toDate, fromDay: fromDay, toDay: toDay},
        //             dataType: "JSON",
        //             type: "GET",
        //             success: function(data) {
        //                 $("#no_of_days").val(data); // set the value for leave balance
        //             }
        //         });
        //     } else {
        //         $("#no_of_days").val('');
        //     }
        // });


        //show employee field for hierarchy level based on selection of approving authority
        // employee_select
        $(document).on("change", ".approving-authority-select", function () {
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
                    success: function (response) {
                        if (response.has_employee_field) {
                            // If there are employees, populate the dropdown
                            if (response.employees.length > 0) {
                                employeeSelect.empty();
                                employeeSelect.append('<option value="" disabled selected hidden>Select Employee</option>');

                                // Populate the employee select with data from the response
                                $.each(response.employees, function (index, employee) {
                                    employeeSelect.append('<option value="' + employee.id + '">' + employee.username + ' - ' + employee.name + '</option>');
                                });
                                // Add the required attribute if has_employee_field is true
                                employeeSelect.attr('required', 'required');
                            } else {
                                alert('Employee has not been set for selected approver, please contact system admin for further information!')
                                // If no employees found, clear and show a placeholder
                                employeeSelect.empty();
                                employeeSelect.append('<option value="" disabled selected hidden>No employees found</option>');
                                // Add the required attribute if has_employee_field is true
                                employeeSelect.attr('required', 'required');
                            }
                        } else {
                            // If 'has_employee_field' is false, hide and clear the dropdown
                            alert('You don`t need to select employee for selected approver!')
                            employeeSelect.empty();
                            employeeSelect.append('<option value="" disabled selected hidden>No employees found</option>');
                            employeeSelect.removeAttr('required'); // Remove required attribute if not needed
                            // employeeSelect.hide();
                        }
                    },
                    error: function () {
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
        $(document).on('change', '#advance_type', function () {
            var advanceTypeId = $(this).val();
            if (advanceTypeId !== '') {
                $.ajax({
                    url: "/getadvancenobyadvancetype/" + advanceTypeId,
                    dataType: "JSON",
                    type: "GET",

                    success: function (response) {
                        $('#advance_no').val(response.advance_no)
                        if (response.sifa_interest_rate != 0) {
                            $('#interest_rate_sifa').val(response.sifa_interest_rate);
                        }
                    },
                    error: function (response) {
                        alert('Something went wrong, please contact system admin for further information!');
                    }
                });
                if (advanceTypeId == 4) { // external api from SOMs will be called here to get Item Types(name, code and amount)
                    $.ajax({
                        url: 'https://external-application.com/api/endpoint', // External API URL
                        dataType: 'JSON',
                        type: 'GET',
                        success: function (response) {
                            // Handle the response from the external API
                            // $('#item_type').val(response.advance_no); // Example field for external response
                        },
                        error: function (response) {
                            console.log(response.error);
                            alert('Something went wrong with the SOM`s API, please contact system admin for further information!');
                        }
                    });
                }
            }
        })

        //generating advance no based on advance types
        $(document).on('change', '#expense_type', function () {
            var expenseTypeId = $(this).val();
            if (expenseTypeId !== '') {
                $.ajax({
                    url: "/getexpensenobyexpensetype/" + expenseTypeId,
                    dataType: "JSON",
                    type: "GET",

                    success: function (response) {
                        $('#expense_no').val(response.expense_no)
                    },
                    error: function (response) {
                        alert('Something went wrong, please contact system admin for further information!');
                    }
                });
            }
        })

        //populate expense details based on selection of expense types for validation purpose
        $(document).ready(function () {
            function getExpenseDetails() {
                const expenseType = $("#expense_type").val();
                const formId = $("#apply_expense");

                if (!expenseType) {
                    $("#amount").val('').removeAttr("max");
                    return;
                }

                $.ajax({
                    url: `/getmaxexpenseamountbyexpensetype/${expenseType}`,
                    dataType: "JSON",
                    type: "GET",
                    success: function (data) {
                        const currentAmount = parseFloat($('#amount').val());
                        if (currentAmount > data.limit_amount) {
                            $("#expense_type").prop("disabled", false);
                            $("#amount").prop('disabled', false);
                            alert(`Expense amount must not exceed Nu. ${data.limit_amount} for region ${data.region_name}!`);
                            $("#amount").val('');
                        }
                        else {
                            formId.find("input, select, textarea").prop("disabled", false);
                        }

                        // Handle attachment requirement
                        if (data.attachment_required) {
                            $("#attachment").attr("required", "required");
                            $("#attachment_required").show();
                        } else {
                            $("#attachment").removeAttr("required");
                            $("#attachment_required").hide();
                        }
                    }
                });
            }

            // Trigger on page load and when expense type or amount changes
            getExpenseDetails();
            $(document).on("change", "#expense_type", getExpenseDetails);
            $(document).on("change", "#amount", getExpenseDetails);
        });

        //get dsa advance details based on select of dsa advance id
        $(document).ready(function () {
            function getDsaAdvanceDetails() {
                const advanceId = $("#advance_no").val();

                if (advanceId !== '') {
                    $.ajax({
                        url: `/getdsaadvancedetailsbyadvanceid/${advanceId}`,
                        dataType: 'JSON',
                        type: 'GET',
                        success: function (data) {
                            console.log(data)
                            if (data['advance_detail'][0].attachment) { // assuming 'file_url' is the URL or path in your response data
                                $('#uploaded-file').html(`
                                    <p>Current Attachment:</p>
                                    <a href="${data['advance_detail'][0].attachment}" target="_blank">View Attachment</a>
                                `);
                            } else {
                                $('#uploaded-file').html('<p>No attachment available.</p>');
                            }
                            // Populate the form fields with the returned data
                            $("#advance_amount").val(parseInt(data['advance_detail'][0].amount) || 0);
                            // $("#total_amount").val(parseInt(data['advance_detail'][0].total_amount) || 0);

                            // Populate table rows
                            $("input[name='dsa_claim_detail[AAAAA][from_date]']").val(data['advance_detail'][0].from_date);
                            $("input[name='dsa_claim_detail[AAAAA][from_location]']").val(data['advance_detail'][0].from_location);
                            $("input[name='dsa_claim_detail[AAAAA][to_date]']").val(data['advance_detail'][0].to_date);
                            $("input[name='dsa_claim_detail[AAAAA][to_location]']").val(data['advance_detail'][0].to_location);

                            // Calculate the total days between from_date and to_date, if needed
                            const fromDate = new Date(data['advance_detail'][0].from_date);
                            const toDate = new Date(data['advance_detail'][0].to_date);
                            const totalDays = Math.ceil((toDate - fromDate) / (1000 * 60 * 60 * 24)) + 1;
                            $("input[name='dsa_claim_detail[AAAAA][total_days]']").val(totalDays);

                            // Calculate and update the total amount
                            calculateTotalAmount();
                            // Populate the remarks if provided
                            $("textarea[name='dsa_claim_detail[AAAAA][remark]']").val(data['advance_detail'][0].remark);
                        },
                        error: function (error) {
                            console.log("Error fetching data", error);
                        }
                    });
                }
            }

            // Function to calculate total amount based on daily and travel allowances
            function calculateTotalAmount() {
                const dailyAllowance = parseInt($("input[name='dsa_claim_detail[AAAAA][daily_allowance]']").val()) || DAILY_ALLOWANCE;
                const travelAllowance = parseInt($("input[name='dsa_claim_detail[AAAAA][travel_allowance]']").val()) || 0;
                const totalDays = parseInt($("input[name='dsa_claim_detail[AAAAA][total_days]']").val()) || 0;

                // Calculate the total amount
                const totalAmount = (dailyAllowance * totalDays) + travelAllowance;
                $("input[name='dsa_claim_detail[AAAAA][total_amount]']").val(totalAmount);
            }

            function calculateTotalDays() {
               
            }

            // Trigger on change of advance_no
            $(document).on("change", "#advance_no", getDsaAdvanceDetails);

            // Trigger calculation only on travel_allowance change
            $(document).on("input", "input[name='dsa_claim_detail[AAAAA][travel_allowance]'], input[name='dsa_claim_detail[AAAAA][total_days]']", calculateTotalAmount);

            // trigger calculation of net payable amount on change of total_amount_adjusted
        });

        //END

        //turn off all the autocomplete feature within the forms
        $('form').find('input').attr('autocomplete', 'off');

        //reset filters
        $('#form-reset').on('click', function () {
            let form = $('#filter-form');
            form.find('select').each(function () {
                $(this).prop('selectedIndex', 0);
            });
            form.find('input').each(function () {
                $(this).val('');
            });
            form.submit();
        });

        // edit modal script
        $('.edit-btn').click(function (e) {
            e.preventDefault(); // Prevent the default action if needed
            var url = $(this).data('url');
            var updateUrl = $(this).data('update-url');

            $.get(url)
                .done(function (data) {
                    // Populate form fields with the fetched data
                    $.each(data, function (key, value) {
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
                .fail(function (error) {
                    console.error('Error:', error);
                });
        });
        // add modal script
        $('.add-btn').click(function (e) {
            e.preventDefault(); // Prevent the default action if needed
            var url = $(this).data('url');
            var updateUrl = $(this).data('update-url');

            $.get(url)
                .done(function (data) {
                    // Populate form fields with the fetched data
                    $.each(data, function (key, value) {
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
                .fail(function (error) {
                    console.error('Error:', error);
                });
        });


    }
    return {
        Initialize: initialize
    }
}();

$(document).ready(function () {
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
    //function to check image size
    //    function validateImage(fileInput) {
    //         // Check if any file is selected
    //         if (!fileInput.files || fileInput.files.length === 0) {
    //             alert("No file selected!");
    //             return false; // Return false if no file is selected
    //         }

    //         // Get the first file from the input (assumes only one file is allowed)
    //         const file = fileInput.files[0];

    //         // Set the maximum allowed size for the image in megabytes (MB)
    //         const maxSizeInMB = 2; // Maximum size is now 2 MB

    //         // Convert the maximum size from megabytes (MB) to bytes
    //         const maxSizeInBytes = maxSizeInMB * 1024 * 1024; // 2 MB = 2 * 1024 * 1024 bytes

    //         // Validate the file size
    //         if (file.size > maxSizeInBytes) {
    //             // If the file size exceeds the maximum limit, display an alert and return false
    //             alert(`File size should not exceed ${maxSizeInMB} MB. Your file size is ${(file.size / (1024 * 1024)).toFixed(2)} MB.`);
    //              return false;
    //         }

    //         // If the file size is valid, display a confirmation and return true
    //         return true;
    //     }
    function validateImage(fileInput) {
        // Check if any file is selected
        if (!fileInput.files || fileInput.files.length === 0) {
            alert("No file selected!");
            return false; // Return false if no file is selected
        }

        // Get the first file from the input (assumes only one file is allowed)
        const file = fileInput.files[0];

        // Set the maximum allowed size for the image in megabytes (MB)
        const maxSizeInMB = 2; // Maximum size is now 2 MB

        // Convert the maximum size from megabytes (MB) to bytes
        const maxSizeInBytes = maxSizeInMB * 1024 * 1024; // 2 MB = 2 * 1024 * 1024 bytes

        // Set allowed file types (MIME types)
        const allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf'];

        // Validate the file type
        if (!allowedFileTypes.includes(file.type)) {
            alert(`Invalid file type. Allowed file types are: PDF, JPEG, PNG.`);
            return false; // Return false if the file type is not allowed
        }

        // Validate the file size
        if (file.size > maxSizeInBytes) {
            // If the file size exceeds the maximum limit, display an alert and return false
            alert(`File size should not exceed ${maxSizeInMB} MB. Your file size is ${(file.size / (1024 * 1024)).toFixed(2)} MB.`);
            return false;
        }

        // // If the file size is valid, display a confirmation and return true
        // alert("File is valid!");
        return true;
    }


    // Usage example: attach an event listener to the file input element
    // Select all input elements of type "file"
    document.querySelectorAll('input[type="file"]').forEach(function (fileInput) {
        fileInput.addEventListener("change", function () {
            validateImage(this); // Call the function with the current file input as the parameter
        });
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
